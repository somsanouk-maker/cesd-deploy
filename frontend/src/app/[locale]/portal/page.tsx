"use client";

import { useEffect, useState } from "react";
import { useLocale, useTranslations } from "next-intl";
import { Link } from "@/i18n/navigation";
import { useAuth } from "@/lib/auth-context";
import { api, type MyServiceRequest, type Booking, type MyTrainingRegistration } from "@/lib/api";
import { Card } from "@/components/ui/card";

export default function PortalDashboardPage() {
  const t = useTranslations("Portal");
  const locale = useLocale();
  const { token } = useAuth();
  const [requests, setRequests] = useState<MyServiceRequest[]>([]);
  const [bookings, setBookings] = useState<Booking[]>([]);
  const [trainings, setTrainings] = useState<MyTrainingRegistration[]>([]);

  useEffect(() => {
    if (!token) return;
    api.myServiceRequests(locale, token).then((res) => setRequests(res.data)).catch(() => {});
    api.myBookings(locale, token).then((res) => setBookings(res.data)).catch(() => {});
    api.myTrainingRegistrations(locale, token).then((res) => setTrainings(res.data)).catch(() => {});
  }, [locale, token]);

  return (
    <div className="grid gap-6 sm:grid-cols-3">
      <Card>
        <p className="text-xs font-semibold uppercase text-slate-500">{t("myRequests")}</p>
        <p className="mt-2 text-3xl font-bold text-brand-dark">{requests.length}</p>
        <Link href="/portal/requests" className="mt-3 inline-block text-sm font-semibold text-brand hover:underline">
          {t("viewAll")} →
        </Link>
      </Card>
      <Card>
        <p className="text-xs font-semibold uppercase text-slate-500">{t("myBookings")}</p>
        <p className="mt-2 text-3xl font-bold text-brand-dark">{bookings.length}</p>
        <Link href="/portal/bookings" className="mt-3 inline-block text-sm font-semibold text-brand hover:underline">
          {t("viewAll")} →
        </Link>
      </Card>
      <Card>
        <p className="text-xs font-semibold uppercase text-slate-500">{t("myTrainings")}</p>
        <p className="mt-2 text-3xl font-bold text-brand-dark">{trainings.length}</p>
        <Link href="/portal/trainings" className="mt-3 inline-block text-sm font-semibold text-brand hover:underline">
          {t("viewAll")} →
        </Link>
      </Card>
    </div>
  );
}
