# CESD Website & Service Platform — Architecture Plan (Pre-Implementation Review)

Source material reviewed: `Software Requirement Specification.pdf`, `CESD Service Workflow.pdf`,
`Key Concept of CESD website captured.pdf`, `ບົດວິພາກການສ້າງຕັ້ງສູນ CESD.pdf`, and the real JICA
equipment inventory in `assets/equipments/List for Equipment Works as of 30 March 2026.xlsx`
(343 rows, columns: No., Code No. [e.g. `CESD-01`, `CESD-02`], Equipment Name, Unit, Qty, Maker,
Model, Shipping Country, Location No., Building, Floor, Room Name — plus a 25-row Location
reference sheet covering CESD's 11 rooms and the rest of the Faculty of Engineering building).
This confirms the SRS numbers are realistic (one `code` can have several physical line items —
e.g. `CESD-02` XRF spectrometer has 5 sub-rows for desiccator, mortar set, hot plate, etc. — so
the equipment schema needs a parent/accessory relationship, not just a flat list).

No code has been written yet. This document is for review before Phase 1 implementation begins.

---

## 1. Recommended Project Architecture

```
cesd-platform/
├── backend/                 # Laravel 11 API + Filament admin (monolith, single deploy unit)
│   ├── app/Models
│   ├── app/Http/Controllers/Api/V1
│   ├── app/Filament/Resources
│   ├── app/Policies                # role-based access per model
│   ├── database/migrations
│   ├── database/seeders
│   └── routes/api.php
├── frontend/                 # Next.js 14 (App Router) — public site + auth'd portal
│   ├── app/[locale]/(public)/...
│   ├── app/[locale]/(portal)/...   # logged-in customer/student dashboard
│   └── messages/{en,lo}.json       # next-intl dictionaries
└── docs/                     # SRS + this planning set
```

**Why one repo, two apps (not a single Laravel+Blade monolith, not microservices):**
- Filament needs to live inside the Laravel app (it's a Laravel package, not a separate service) —
  so "Backend API" and "Admin Dashboard" are the *same* Laravel codebase, two route groups
  (`/admin` for Filament, `/api/v1/*` for the JSON API). This matches the SRS's own stack choice.
- Microservices are unjustified at MVP scale (single center, expected load is low —
  a university lab, not a consumer app).
- A monorepo keeps the two apps versioned together, which matters here because the API contract
  will change every sprint during MVP build-out.

**Auth strategy (Sanctum, decoupled frontend) — this needs a decision, see §7:**
Recommend **Next.js as a BFF (backend-for-frontend)**: the browser never sees a Laravel token.
Next.js Route Handlers call Laravel's `/api/v1/auth/login`, receive a Sanctum personal-access
token, and store it in an **httpOnly, secure cookie** scoped to the Next.js domain. All subsequent
`fetch` calls from Next.js server components/route handlers attach `Authorization: Bearer <token>`
server-side. This avoids Sanctum's SPA cookie mode (which requires same-parent-domain +
`stateful` config and is fragile across separate hosting providers), while still keeping the
token out of client-side JS. Filament admin auth is separate and uses Laravel's own session guard
(admins never touch the Next.js app).

**Roles & permissions:** `spatie/laravel-permission` (industry standard, integrates natively with
Filament via plugins) rather than hand-rolled `roles`/`permissions`/`user_roles` tables from the
SRS sketch — same concept, battle-tested package, less code to maintain.

**Bilingual content:** two patterns, chosen per entity type (deviates slightly from the SRS's
"translation table for everything" sketch — rationale below):
- **Structured/catalog data** (laboratories, equipment, categories, services, training courses,
  partners): plain `name_en` / `name_lo`, `description_en` / `description_lo` columns on the base
  table. These are short fields, always edited together, and this makes filtering/sorting/search
  trivial in both the API and Filament — no joins needed for an equipment search page.
- **Long-form editorial content** (pages, news, projects): keep the SRS's `*_translations` child
  table pattern (`news` + `news_translations` keyed by `locale`), because editors realistically
  publish an English news post before the Lao translation is ready, and a translation table lets
  each locale have independent `published_at`/draft state. A shared column pair can't express that.

**File storage:** Laravel's filesystem abstraction, `local` disk for MVP (equipment photos, manuals,
reports), config left ready to swap to `s3` driver later (env-var only change, no code change) —
matches the SRS's storage note.

**i18n on the frontend:** `next-intl` with locale-prefixed routes (`/en/...`, `/lo/...`), default
to `/lo` (Lao is the national language; English is secondary per the SRS but this should be
confirmed — see §7).

---

## 2. Database Schema / Migrations (entities & key relationships)

Grouped by module. `en`/`lo` suffix = bilingual pair on the same row (per the §1 decision).

**Auth & RBAC**
- `users` (id, name, email, password, phone, organization, user_type\* enum: visitor-registered/
  customer/student/staff/unit_head/director/admin, locale_preference, email_verified_at)
- `roles`, `permissions`, `model_has_roles`, `model_has_permissions` (spatie package tables)
- `personal_access_tokens` (Sanctum)

**Content (CMS)**
- `pages`, `page_translations` (locale, title, body, slug, seo_meta)
- `news`, `news_translations` (locale, title, excerpt, body, published_at)
- `projects`, `project_translations` (partner-facing project showcase, distinct from `joint_projects` below)
- `banners` (homepage highlights, sort_order, active_from/to)

**Laboratories & Equipment**
- `laboratories` (code, name_en, name_lo, description_en/lo, location_no, building, floor,
  room_name, responsible_user_id, safety_rules_en/lo, photo, status)
  — `location_no`/`building`/`floor`/`room_name` map directly to the real "Location No." reference
  sheet found in the equipment workbook.
- `equipment_categories` (name_en, name_lo, parent_id nullable for sub-categories)
- `equipment` (code **[not unique alone — see note]**, laboratory_id, category_id, name_en, name_lo,
  brand/maker, model, serial_number, shipping_country, unit, quantity, specification_en/lo,
  capability_en/lo, photo, manual_file, availability_status enum: available/in_use/maintenance/
  retired, responsible_user_id)
  — *Note:* real data shows one `code` (e.g. `CESD-02`) spanning multiple physical line items
  (main unit + accessories). Model this as `equipment` (the catalog record customers see/search,
  one per `code`) with a child `equipment_items` (id, equipment_id, item_no, name, qty, is_accessory)
  table for the individual shipment line items — keeps the public catalog clean while preserving
  the full inventory detail admins need.
- `equipment_documents` (equipment_id, type: manual/certificate/spec_sheet, file_path)
- `equipment_maintenance` (equipment_id, performed_at, performed_by, description, next_due_at) — Phase 3
- `equipment_calibrations` (equipment_id, calibrated_at, certificate_no, valid_until, file_path) — Phase 3

**Services**
- `services`, `service_translations` (category enum: testing/inspection/performance_test/joint_rd/
  consulting/training/facility_booking — matches SRS §"Services" sitemap)
- `service_requests` (request_no, customer_id, service_id, laboratory_id, equipment_id nullable,
  title, description, sample_information, required_date, status enum matching the SRS workflow:
  submitted→admin_review→lab_evaluation→quoted→confirmed→sample_received→testing→
  result_review→report_issued→closed / rejected, assigned_staff_id, quotation_id)
- `service_status_logs` (service_request_id, from_status, to_status, changed_by, note, created_at)
- `quotations` (service_request_id, quotation_no, subtotal, tax, total, valid_until, status:
  draft/sent/accepted/rejected, file_path)
- `quotation_items` (quotation_id, description, qty, unit_price, line_total)
- `payments` — **Phase 3/out of MVP scope per your Phase-1 prompt**, table reserved but unused

**Booking**
- `bookings` (booking_no, user_id, bookable_type/bookable_id **polymorphic** [equipment or
  laboratory/room — cleaner than separate nullable `equipment_id`/`room_id` columns from the SRS
  sketch], start_datetime, end_datetime, purpose, status: pending/approved/rejected/cancelled/
  completed, approved_by, approved_at)
- `booking_status_logs` (mirrors service_status_logs)

**Training**
- `training_courses`, `training_translations` (title, description, syllabus, start_date, end_date,
  capacity, fee, mode: in_person/online)
- `training_registrations` (course_id, user_id or guest name/email/org, status: registered/
  attended/no_show/cancelled)
- `certificates` (registration_id, certificate_no, issued_at, file_path, qr_code) — QR generation
  wired in Phase 1 schema-wise but verification endpoint is Phase 3 per your scope note

**Partnership**
- `partners` (name_en/lo, type: company/university/government, logo, website, contact_info)
- `partnership_requests` (partner_name, contact info, interest_description, status)
- `joint_projects` (partner_id, title_en/lo, description_en/lo, start_date, end_date, mou_file,
  status)
- `project_outputs` (joint_project_id, title, type: publication/patent/product, file_or_link)

**Reports & CRM**
- `reports` (service_request_id, report_no, title, result_summary, file_path, qr_code,
  verification_token, approved_by, approved_at, issued_at, status) — Phase 3 feature, schema
  stubbed now so `service_requests.status` can reach `report_issued` cleanly later
- `report_verifications` (report_id, verified_at, verifier_ip) — Phase 3
- `feedback` (user_id nullable, service_request_id nullable, rating, comment)
- `contacts` (name, email, phone, subject, message, handled_by) — public contact form

**System**
- `settings` (key, value, group) — site-wide config (contact info, social links, banner text)
- `audit_logs` (user_id, action, subject_type, subject_id, changes json) — via `spatie/laravel-activitylog`
  rather than a hand-rolled table

---

## 3. API Route Plan (`/api/v1`)

```
Public (no auth)
  GET  /pages/{slug}
  GET  /laboratories                 GET /laboratories/{code}
  GET  /equipment                    GET /equipment/{code}         (search: q, laboratory_id, category_id, availability)
  GET  /equipment-categories
  GET  /services                     GET /services/{slug}
  GET  /training-courses             GET /training-courses/{id}
  GET  /news                         GET /news/{slug}
  GET  /projects                     GET /partners
  POST /contact
  POST /partnership-requests
  GET  /settings (public subset)

Auth
  POST /auth/register    POST /auth/login    POST /auth/logout
  POST /auth/forgot-password   POST /auth/reset-password
  GET  /auth/me

Customer / Student (auth:sanctum, role: customer|student|staff|...)
  POST   /service-requests            GET /service-requests            GET /service-requests/{id}
  POST   /service-requests/{id}/confirm      (accept quotation)
  GET    /service-requests/{id}/status-logs
  POST   /bookings                    GET /bookings                    GET /bookings/{id}
  DELETE /bookings/{id}               (cancel own pending booking)
  POST   /training-registrations      GET /training-registrations (own)
  GET    /certificates (own)
  POST   /feedback

Staff / Unit Head (role: lab_staff|unit_head|director)
  GET/PATCH /service-requests/{id}         (evaluate, assign, change status)
  POST      /service-requests/{id}/quotation
  GET/PATCH /bookings/{id}                 (approve/reject)
  GET       /equipment (management view, includes maintenance/calibration — Phase 3)

Admin — handled almost entirely by Filament's own internal routing, not a separate REST surface.
Only expose REST endpoints Filament can't cover (e.g. public search) elsewhere above.
```

All list endpoints: paginated (`?page=`), locale-aware via `Accept-Language` header or `?locale=`
query param, resolved through Laravel API Resources (`EquipmentResource`, etc.) that flatten
`name_en`/`name_lo` into a single localized `name` field for the frontend, keeping Next.js
components language-agnostic.

---

## 4. Frontend Page Structure (Next.js App Router)

```
app/
  [locale]/
    (public)/
      page.tsx                        Home
      about/page.tsx                  Background, Vision/Mission, Objectives, Org chart (tabs or sub-routes)
      about/organization/page.tsx
      laboratories/page.tsx           Lab directory (grid)
      laboratories/[code]/page.tsx    Lab profile (equipment linked, staff, photos, safety rules)
      equipment/page.tsx              Catalog with search/filter (lab, category, availability, keyword)
      equipment/[code]/page.tsx       Equipment detail (specs, manuals, capability, booking CTA)
      services/page.tsx               Service categories overview
      services/[slug]/page.tsx        Service detail + "Request this service" CTA
      training/page.tsx               Course listing
      training/[id]/page.tsx          Course detail + registration form
      projects/page.tsx               Projects & partners
      news/page.tsx                   News & events list
      news/[slug]/page.tsx
      contact/page.tsx
      request-service/page.tsx        Standalone service request form (from SRS sitemap)
    (portal)/                         requires auth (middleware redirect to /login)
      dashboard/page.tsx              Overview: my requests, my bookings, my registrations
      requests/page.tsx               Service request list + status tracker
      requests/[id]/page.tsx
      bookings/page.tsx
      bookings/new/page.tsx
      training/my-registrations/page.tsx
      profile/page.tsx
    (auth)/
      login/page.tsx  register/page.tsx  forgot-password/page.tsx
  layout.tsx                          locale switcher, nav, footer
  middleware.ts                       locale detection + portal auth guard
messages/en.json  messages/lo.json    next-intl dictionaries for UI chrome (not DB content)
```

Locale is a **route prefix** (`/lo`, `/en`) not a hidden cookie-only switch — needed for SEO
(public-facing government/university site) and so the correct language can be linked/shared directly.

---

## 5. Admin Dashboard Resource Structure (Filament)

Navigation groups map to the SRS's org units, gated by `spatie/laravel-permission` + Filament's
`shield` plugin (auto-generates permissions per resource/action):

```
Content
  PageResource, NewsResource, ProjectResource, BannerResource, PartnerResource

Laboratories & Equipment
  LaboratoryResource, EquipmentResource (with EquipmentItemsRelationManager,
    EquipmentDocumentsRelationManager), EquipmentCategoryResource
  (Phase 3: EquipmentMaintenanceResource, EquipmentCalibrationResource)

Services
  ServiceResource
  ServiceRequestResource  — kanban-style status board (Filament has a board/kanban plugin) or
    a status column with bulk actions; QuotationRelationManager on the record page
  BookingResource — calendar widget view (Filament has a calendar plugin) + list view

Training
  TrainingCourseResource (RegistrationsRelationManager, CertificatesRelationManager — cert
    generation Phase 1 as a manual upload, PDF-templated generation Phase 3)

Partnership
  PartnershipRequestResource, JointProjectResource (OutputsRelationManager)

CRM
  Users list filtered to customers, with ServiceHistoryRelationManager
  FeedbackResource, ContactResource (inbox-style, mark handled)
  (Phase 3: ReportResource with QR generation)

Administration
  UserResource, RoleResource (Shield), SettingsPage (Filament custom page), AuditLogResource (read-only)
```

Each resource's visible navigation item and CRUD actions are permission-gated per role, so e.g.
`lab_staff` sees Laboratories/Equipment/Service Requests/Bookings but not Users or Settings;
`unit_head`/`director` get read/approve access across units plus a dashboard widget page
(counts: open requests, pending bookings, upcoming trainings) — the "view statistics" item from
the Key-Concept doc, done with Filament's built-in Widgets rather than a separate analytics stack
for MVP (matches "Phase 3: Analytics dashboard" in the SRS — a *richer* one comes later; a basic
counts widget is cheap enough to include in Phase 1).

---

## 6. Development Phases

**Phase 1 — Public Website + Admin CMS + Basic Requests** *(this is the scope of your second prompt)*
Laravel migrations/models/seeders for all tables above (schema for Phase 2/3 tables included now
so no breaking migrations later, but their UI/business-logic stays dark), Sanctum auth, Filament
CRUD for laboratories/equipment/services/news/service-requests, Next.js public bilingual pages,
service request submission form (no quotation workflow yet — just submit → admin sees it),
equipment search/filter.

**Phase 2 — Service Platform**
Full service-request status workflow + quotation management, equipment/facility booking with
calendar + approval, customer portal dashboard, training registration + attendance, partnership
inquiry flow, email notifications (status changes, booking confirmation).

**Phase 3 — Advanced LIMS**
Digital report issuance + QR-code verification (public `/verify/{token}` page), payments,
equipment maintenance/calibration tracking, richer CRM (service history analytics), certificate
auto-generation (PDF templating), AI/search recommendations.

This mirrors the SRS's own 3-phase split; the only refinement is designing the full schema up
front (cheap) while deferring the business logic (expensive) for Phase 2/3 tables, to avoid
destructive migrations mid-project.

---

## 7. Questions to Clarify Before Implementation

**Infrastructure**
1. Where will this be hosted — university server, cloud VM, or shared hosting? Does it support
   two separate processes (PHP-FPM for Laravel + Node for Next.js), or does Next.js need to be
   exported/static for a simpler host? This decides the BFF-auth approach in §1.
2. Will frontend and backend share a parent domain (e.g. `cesd.nuol.edu.la` +
   `api.cesd.nuol.edu.la`)? Needed to finalize whether Sanctum SPA cookie auth is viable instead
   of the token/BFF approach.
3. Do you already have a domain/SSL, or should the MVP plan for `localhost`/staging only for now?

**Identity & accounts**
4. Should Student/Researcher accounts integrate with an existing NUOL SSO/student ID system, or
   is self-registration with email/phone acceptable for MVP?
5. Can Customers/Industry Partners self-register, or must an admin create/approve their accounts
   first (common for B2B service platforms to prevent spam requests)?
6. What should the seeded demo accounts be — one per role is assumed (visitor doesn't need an
   account); confirm the role list/names match exactly: customer, student, lab_staff, unit_head,
   director, admin.

**Content & language**
7. Is Lao or English the default site language? (Assumed Lao-first per national-hub framing, but
   confirm — affects default locale route and SEO priority.)
8. Do you have real Lao text ready for About/Vision/Mission, or should seeders use placeholder
   Lao copy pending content from CESD staff?
9. Logo, brand colors, and any existing NUOL/Faculty of Engineering visual identity guidelines to
   follow for the Next.js theme?

**Equipment data**
10. Should the ~300-item equipment list from `List for Equipment Works as of 30 March 2026.xlsx`
    be imported directly as Phase 1 seed data (I can write an import command), or is that list
    provisional/pending cleanup first?
11. The "Location No." reference sheet includes rooms outside CESD's 11 labs (e.g. Fluid Mechanics
    Lab, Electrical Engineering Lab in other Faculty buildings) — should the platform model the
    *whole Faculty of Engineering's* room/location list, or only CESD's own 11 facilities for MVP?

**Process specifics**
12. For service requests — is there a real quotation/pricing list per service type, or is
    quotation amount manually entered by staff case-by-case for Phase 2?
13. Does CESD need online payment at all in the medium term (bank transfer confirmation might be
    enough given it's a university/government center), or should Phase 3 planning drop "payments"
    entirely in favor of an offline "payment confirmed" checkbox?
14. For bookings — can a Student/Researcher book directly, or must their advisor/unit head
    co-approve (typical for shared university lab equipment)?

**Compliance**
15. Any data-residency or privacy requirements for customer test data/reports (this is likely a
    government-affiliated center — confirm if there's a records-retention policy that should
    shape file storage/deletion behavior)?

---

**Next step:** once you've reviewed this and answered whichever of the §7 questions matter most
for an MVP decision (infra/domain and auth strategy — items 1–3 — are the ones that actually block
writing code; the rest can default sensibly and be adjusted later), I'll proceed with the Phase 1
build as scoped in your second prompt.
