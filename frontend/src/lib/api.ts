const API_URL = process.env.NEXT_PUBLIC_API_URL ?? "http://localhost:8000";

export class ApiError extends Error {
  status: number;
  constructor(message: string, status: number) {
    super(message);
    this.status = status;
  }
}

async function apiFetch<T>(
  path: string,
  locale: string,
  init?: RequestInit
): Promise<T> {
  const url = new URL(`/api/v1${path}`, API_URL);
  if (!url.searchParams.has("locale")) {
    url.searchParams.set("locale", locale);
  }

  const res = await fetch(url.toString(), {
    ...init,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      ...init?.headers,
    },
    // Public content should stay reasonably fresh without hammering the API.
    next: { revalidate: 60 },
  });

  if (!res.ok) {
    const body = await res.json().catch(() => null);
    throw new ApiError(body?.message ?? res.statusText, res.status);
  }

  return res.json();
}

/**
 * For authenticated calls made from client components (customer portal).
 * Always runs client-side with a Bearer token, so no Next.js data-cache
 * options apply here — every call should reflect the latest server state.
 */
async function authFetch<T>(
  path: string,
  locale: string,
  token: string,
  init?: RequestInit
): Promise<T> {
  const url = new URL(`/api/v1${path}`, API_URL);
  if (!url.searchParams.has("locale")) {
    url.searchParams.set("locale", locale);
  }

  const res = await fetch(url.toString(), {
    ...init,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      Authorization: `Bearer ${token}`,
      ...init?.headers,
    },
    cache: "no-store",
  });

  if (!res.ok) {
    const body = await res.json().catch(() => null);
    throw new ApiError(body?.message ?? res.statusText, res.status);
  }

  return res.json();
}

export interface Paginated<T> {
  data: T[];
  meta?: {
    current_page: number;
    last_page: number;
    total: number;
  };
}

export interface Laboratory {
  id: number;
  code: string;
  name: string;
  description: string | null;
  location_no: number | null;
  building: string | null;
  floor: string | null;
  room_name: string | null;
  photo_url: string | null;
  responsible_staff: string | null;
  equipment_count: number;
}

export interface EquipmentCategory {
  id: number;
  name: string;
}

export interface Equipment {
  id: number;
  code: string;
  name: string;
  brand: string | null;
  model: string | null;
  specification: string | null;
  capability: string | null;
  availability_status: "available" | "in_use" | "maintenance" | "retired";
  photo_url: string | null;
  manual_url: string | null;
  laboratory: { id: number; code: string; name: string } | null;
  category: { id: number; name: string } | null;
  accessories?: { id: number; name: string; brand: string | null; model: string | null; quantity: number }[];
}

export interface ServiceItem {
  id: number;
  slug: string;
  name: string;
  category: string;
  description: string | null;
  icon: string | null;
}

export interface TrainingCourse {
  id: number;
  title: string;
  description: string | null;
  start_date: string | null;
  end_date: string | null;
  capacity: number | null;
  fee: number | null;
  mode: string;
}

export interface NewsItem {
  id: number;
  slug: string;
  title: string;
  excerpt: string | null;
  body: string | null;
  cover_image_url: string | null;
  published_at: string | null;
}

export interface SiteSettings {
  contact_email?: string;
  contact_phone?: string;
  address?: string;
  facebook_url?: string;
}

export interface AboutContent {
  title: string;
  background: string | null;
  vision: string | null;
  mission: string | null;
  objectives: string[];
  organization: {
    director: string | null;
    deputyDirector: string | null;
    admin: string | null;
    technical: string | null;
    innovation: string | null;
  };
}

export interface AuthUser {
  id: number;
  name: string;
  email: string;
}

export interface AuthResponse {
  user: AuthUser;
  roles?: string[];
  token: string;
}

export interface MyServiceRequest {
  id: number;
  request_no: string;
  title: string;
  description: string;
  sample_information: string | null;
  required_date: string | null;
  status: string;
  quotation_status: "not_quoted" | "quoted" | "accepted" | "declined";
  quoted_amount: string | null;
  quotation_notes: string | null;
  quoted_at: string | null;
  staff_notes: string | null;
  service?: { id: number; name: string };
  laboratory?: { id: number; name: string } | null;
  created_at: string;
}

export interface Booking {
  id: number;
  booking_no: string;
  bookable_type: "equipment" | "laboratory";
  bookable_name: string;
  purpose: string;
  start_at: string;
  end_at: string;
  status: "pending_advisor" | "pending_staff" | "approved" | "rejected" | "cancelled";
  requires_advisor_approval: boolean;
  advisor_note: string | null;
  staff_note: string | null;
  created_at: string;
}

export interface MyTrainingRegistration {
  id: number;
  status: "registered" | "waitlisted" | "attended" | "no_show" | "cancelled";
  registered_at: string;
  training_course?: {
    id: number;
    title: string;
    start_date: string | null;
    end_date: string | null;
  };
}

export const api = {
  laboratories: (locale: string) =>
    apiFetch<Paginated<Laboratory>>("/laboratories", locale),
  laboratory: (locale: string, code: string) =>
    apiFetch<{ data: Laboratory }>(`/laboratories/${code}`, locale),

  equipmentCategories: (locale: string) =>
    apiFetch<{ data: EquipmentCategory[] }>("/equipment-categories", locale),
  equipment: (
    locale: string,
    params: {
      q?: string;
      laboratory_id?: string;
      category_id?: string;
      availability_status?: string;
      page?: number;
    } = {}
  ) => {
    const query = new URLSearchParams();
    Object.entries(params).forEach(([key, value]) => {
      if (value !== undefined && value !== "") query.set(key, String(value));
    });
    const qs = query.toString();
    return apiFetch<Paginated<Equipment>>(
      `/equipment${qs ? `?${qs}` : ""}`,
      locale
    );
  },
  equipmentItem: (locale: string, code: string) =>
    apiFetch<{ data: Equipment }>(`/equipment/${code}`, locale),

  services: (locale: string) =>
    apiFetch<{ data: ServiceItem[] }>("/services", locale),
  service: (locale: string, slug: string) =>
    apiFetch<{ data: ServiceItem }>(`/services/${slug}`, locale),

  trainingCourses: (locale: string) =>
    apiFetch<{ data: TrainingCourse[] }>("/training-courses", locale),
  trainingCourse: (locale: string, id: string | number) =>
    apiFetch<{ data: TrainingCourse }>(`/training-courses/${id}`, locale),

  news: (locale: string) => apiFetch<Paginated<NewsItem>>("/news", locale),
  newsItem: (locale: string, slug: string) =>
    apiFetch<{ data: NewsItem }>(`/news/${slug}`, locale),

  settings: (locale: string) =>
    apiFetch<{ data: SiteSettings }>("/settings", locale),

  about: (locale: string) =>
    apiFetch<{ data: AboutContent }>("/about", locale),

  submitContact: (
    locale: string,
    payload: {
      name: string;
      email: string;
      phone?: string;
      subject: string;
      message: string;
    }
  ) =>
    apiFetch<{ data: { id: number } }>("/contact", locale, {
      method: "POST",
      body: JSON.stringify(payload),
      next: undefined,
      cache: "no-store",
    }),

  submitServiceRequest: (
    locale: string,
    payload: {
      service_id: number;
      laboratory_id?: number | null;
      title: string;
      description: string;
      sample_information?: string;
      required_date?: string;
      contact_name: string;
      contact_email: string;
      contact_phone?: string;
      organization?: string;
    }
  ) =>
    apiFetch<{ data: { request_no: string } }>("/service-requests", locale, {
      method: "POST",
      body: JSON.stringify(payload),
      next: undefined,
      cache: "no-store",
    }),

  submitPartnershipInquiry: (
    locale: string,
    payload: {
      organization_name: string;
      contact_name: string;
      contact_email: string;
      contact_phone?: string;
      inquiry_type?: string;
      message: string;
    }
  ) =>
    apiFetch<{ data: { id: number } }>("/partnership-inquiries", locale, {
      method: "POST",
      body: JSON.stringify(payload),
      next: undefined,
      cache: "no-store",
    }),

  registerForTraining: (
    locale: string,
    courseId: number | string,
    payload: { name: string; email: string; phone?: string; organization?: string },
    token?: string | null
  ) =>
    apiFetch<{ data: MyTrainingRegistration }>(
      `/training-courses/${courseId}/register`,
      locale,
      {
        method: "POST",
        body: JSON.stringify(payload),
        next: undefined,
        cache: "no-store",
        headers: token ? { Authorization: `Bearer ${token}` } : undefined,
      }
    ),

  bookableAvailability: (
    locale: string,
    bookableType: "equipment" | "laboratory",
    bookableId: number
  ) =>
    apiFetch<{ data: { start_at: string; end_at: string }[] }>(
      `/bookable-availability?bookable_type=${bookableType}&bookable_id=${bookableId}`,
      locale,
      { next: undefined, cache: "no-store" }
    ),

  // Auth
  register: (
    locale: string,
    payload: { name: string; email: string; password: string; phone?: string; organization?: string }
  ) =>
    apiFetch<{ data: AuthResponse }>("/auth/register", locale, {
      method: "POST",
      body: JSON.stringify(payload),
      next: undefined,
      cache: "no-store",
    }),

  login: (locale: string, payload: { email: string; password: string }) =>
    apiFetch<{ data: AuthResponse }>("/auth/login", locale, {
      method: "POST",
      body: JSON.stringify(payload),
      next: undefined,
      cache: "no-store",
    }),

  logout: (locale: string, token: string) =>
    authFetch<{ data: { message: string } }>("/auth/logout", locale, token, {
      method: "POST",
    }),

  me: (locale: string, token: string) =>
    authFetch<{ data: AuthUser & { roles: string[] } }>("/auth/me", locale, token),

  // Customer portal (all require a Bearer token)
  myServiceRequests: (locale: string, token: string) =>
    authFetch<{ data: MyServiceRequest[] }>("/my/service-requests", locale, token),

  myServiceRequest: (locale: string, token: string, id: number | string) =>
    authFetch<{ data: MyServiceRequest }>(`/my/service-requests/${id}`, locale, token),

  respondToQuotation: (
    locale: string,
    token: string,
    id: number | string,
    response: "accepted" | "declined"
  ) =>
    authFetch<{ data: MyServiceRequest }>(
      `/my/service-requests/${id}/quotation-response`,
      locale,
      token,
      { method: "POST", body: JSON.stringify({ response }) }
    ),

  myBookings: (locale: string, token: string) =>
    authFetch<{ data: Booking[] }>("/my/bookings", locale, token),

  createBooking: (
    locale: string,
    token: string,
    payload: {
      bookable_type: "equipment" | "laboratory";
      bookable_id: number;
      purpose: string;
      start_at: string;
      end_at: string;
    }
  ) =>
    authFetch<{ data: Booking }>("/bookings", locale, token, {
      method: "POST",
      body: JSON.stringify(payload),
    }),

  cancelBooking: (locale: string, token: string, id: number | string) =>
    authFetch<{ data: Booking }>(`/bookings/${id}/cancel`, locale, token, {
      method: "POST",
    }),

  myTrainingRegistrations: (locale: string, token: string) =>
    authFetch<{ data: MyTrainingRegistration[] }>(
      "/my/training-registrations",
      locale,
      token
    ),
};
