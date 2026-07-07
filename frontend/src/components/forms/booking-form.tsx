"use client";

import { FormEvent, useState } from "react";
import { useLocale, useTranslations } from "next-intl";
import { Link } from "@/i18n/navigation";
import { useAuth } from "@/lib/auth-context";
import { api, ApiError } from "@/lib/api";

export function BookingForm({
  bookableType,
  bookableId,
}: {
  bookableType: "equipment" | "laboratory";
  bookableId: number;
}) {
  const t = useTranslations("Booking");
  const locale = useLocale();
  const { user, token } = useAuth();
  const [status, setStatus] = useState<"idle" | "submitting" | "success" | "error">("idle");
  const [bookingNo, setBookingNo] = useState<string | null>(null);
  const [errorMessage, setErrorMessage] = useState<string | null>(null);

  const typeLabel = bookableType === "equipment" ? t("equipment") : t("laboratory");

  if (!user || !token) {
    return (
      <div className="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-5 text-sm text-slate-600">
        <p>{t("loginRequired")}</p>
        <Link
          href="/login"
          className="mt-3 inline-block rounded-full bg-brand px-5 py-2 text-sm font-semibold text-white hover:scale-105 transition-transform"
        >
          {t("logIn")}
        </Link>
      </div>
    );
  }

  async function handleSubmit(e: FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setStatus("submitting");
    setErrorMessage(null);
    const formEl = e.currentTarget;
    const form = new FormData(formEl);

    try {
      const res = await api.createBooking(locale, token!, {
        bookable_type: bookableType,
        bookable_id: bookableId,
        purpose: String(form.get("purpose")),
        start_at: String(form.get("start_at")),
        end_at: String(form.get("end_at")),
      });
      setBookingNo(res.data.booking_no);
      setStatus("success");
      formEl.reset();
    } catch (err) {
      setErrorMessage(err instanceof ApiError ? err.message : null);
      setStatus("error");
    }
  }

  if (status === "success") {
    return (
      <div className="rounded-xl bg-emerald-50 p-5 text-sm text-emerald-800">
        {t("successMessage", { bookingNo: bookingNo ?? "" })}
      </div>
    );
  }

  return (
    <form onSubmit={handleSubmit} className="rounded-xl border border-slate-200 bg-white p-5">
      <h3 className="font-semibold text-brand-dark">{t("title", { type: typeLabel })}</h3>

      {user.roles?.includes("student") && (
        <p className="mt-2 text-xs text-amber-700">{t("advisorNote")}</p>
      )}

      <div className="mt-4 space-y-4">
        <div>
          <label className="text-sm font-medium text-slate-700">{t("purpose")}</label>
          <textarea
            name="purpose"
            required
            rows={2}
            className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
          />
        </div>
        <div className="grid gap-4 sm:grid-cols-2">
          <div>
            <label className="text-sm font-medium text-slate-700">{t("startAt")}</label>
            <input
              type="datetime-local"
              name="start_at"
              required
              className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
            />
          </div>
          <div>
            <label className="text-sm font-medium text-slate-700">{t("endAt")}</label>
            <input
              type="datetime-local"
              name="end_at"
              required
              className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
            />
          </div>
        </div>
      </div>

      <button
        type="submit"
        disabled={status === "submitting"}
        className="mt-4 rounded-full bg-brand px-6 py-2.5 text-sm font-semibold text-white transition-transform hover:scale-105 disabled:opacity-60"
      >
        {t("submit")}
      </button>

      {status === "error" && (
        <p className="mt-3 text-sm font-medium text-red-600">
          {errorMessage ?? t("errorMessage")}
        </p>
      )}
    </form>
  );
}
