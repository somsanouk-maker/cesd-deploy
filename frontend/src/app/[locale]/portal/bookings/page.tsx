"use client";

import { useEffect, useState } from "react";
import { useLocale, useTranslations } from "next-intl";
import { useAuth } from "@/lib/auth-context";
import { api, type Booking } from "@/lib/api";
import { Card, Badge } from "@/components/ui/card";

function statusTone(status: string): "brand" | "green" | "amber" | "slate" {
  if (status === "approved") return "green";
  if (status === "rejected" || status === "cancelled") return "slate";
  return "amber";
}

export default function PortalBookingsPage() {
  const t = useTranslations("Portal");
  const locale = useLocale();
  const { token } = useAuth();
  const [bookings, setBookings] = useState<Booking[] | null>(null);
  const [cancellingId, setCancellingId] = useState<number | null>(null);

  function load() {
    if (!token) return;
    api.myBookings(locale, token).then((res) => setBookings(res.data));
  }

  useEffect(load, [locale, token]);

  async function handleCancel(id: number) {
    if (!token || !window.confirm(t("cancelConfirm"))) return;
    setCancellingId(id);
    try {
      await api.cancelBooking(locale, token, id);
      load();
    } finally {
      setCancellingId(null);
    }
  }

  if (bookings === null) return null;

  if (bookings.length === 0) {
    return <p className="text-sm text-slate-500">{t("noBookings")}</p>;
  }

  return (
    <div className="space-y-4">
      {bookings.map((booking) => (
        <Card key={booking.id} className="flex flex-wrap items-center justify-between gap-3">
          <div>
            <p className="font-semibold text-slate-800">{booking.bookable_name}</p>
            <p className="text-xs text-slate-500">
              {t("bookingNo")}: {booking.booking_no} · {new Date(booking.start_at).toLocaleString()} —{" "}
              {new Date(booking.end_at).toLocaleString()}
            </p>
            <p className="mt-1 text-sm text-slate-600">{booking.purpose}</p>
          </div>
          <div className="flex items-center gap-2">
            <Badge tone={statusTone(booking.status)}>{booking.status.replace(/_/g, " ")}</Badge>
            {(booking.status === "pending_advisor" || booking.status === "pending_staff") && (
              <button
                type="button"
                disabled={cancellingId === booking.id}
                onClick={() => handleCancel(booking.id)}
                className="rounded-full border border-red-300 px-4 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 disabled:opacity-60"
              >
                {t("cancel")}
              </button>
            )}
          </div>
        </Card>
      ))}
    </div>
  );
}
