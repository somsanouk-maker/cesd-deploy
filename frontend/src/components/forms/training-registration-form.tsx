"use client";

import { FormEvent, useState } from "react";
import { useLocale, useTranslations } from "next-intl";
import { useAuth } from "@/lib/auth-context";
import { api, ApiError } from "@/lib/api";

export function TrainingRegistrationForm({ courseId }: { courseId: number }) {
  const t = useTranslations("Training");
  const locale = useLocale();
  const { user, token } = useAuth();
  const [status, setStatus] = useState<"idle" | "submitting" | "success" | "error">("idle");
  const [resultStatus, setResultStatus] = useState<"registered" | "waitlisted" | null>(null);
  const [errorMessage, setErrorMessage] = useState<string | null>(null);

  async function handleSubmit(e: FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setStatus("submitting");
    setErrorMessage(null);
    const formEl = e.currentTarget;
    const form = new FormData(formEl);

    try {
      const res = await api.registerForTraining(
        locale,
        courseId,
        {
          name: String(form.get("name")),
          email: String(form.get("email")),
          phone: String(form.get("phone") ?? "") || undefined,
          organization: String(form.get("organization") ?? "") || undefined,
        },
        token
      );
      setResultStatus(res.data.status === "waitlisted" ? "waitlisted" : "registered");
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
        {resultStatus === "waitlisted" ? t("waitlistedMessage") : t("registeredMessage")}
      </div>
    );
  }

  return (
    <form onSubmit={handleSubmit} className="rounded-xl border border-slate-200 bg-white p-5">
      <div className="grid gap-4 sm:grid-cols-2">
        <div>
          <label className="text-sm font-medium text-slate-700">{t("regName")}</label>
          <input
            name="name"
            required
            defaultValue={user?.name ?? ""}
            className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
          />
        </div>
        <div>
          <label className="text-sm font-medium text-slate-700">{t("regEmail")}</label>
          <input
            type="email"
            name="email"
            required
            defaultValue={user?.email ?? ""}
            className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
          />
        </div>
        <div>
          <label className="text-sm font-medium text-slate-700">{t("regPhone")}</label>
          <input
            name="phone"
            className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
          />
        </div>
        <div>
          <label className="text-sm font-medium text-slate-700">{t("regOrganization")}</label>
          <input
            name="organization"
            className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
          />
        </div>
      </div>

      <button
        type="submit"
        disabled={status === "submitting"}
        className="mt-4 rounded-full bg-accent px-6 py-2.5 text-sm font-semibold text-slate-900 transition-transform hover:scale-105 disabled:opacity-60"
      >
        {t("register")}
      </button>

      {status === "error" && (
        <p className="mt-3 text-sm font-medium text-red-600">
          {errorMessage ?? t("regErrorMessage")}
        </p>
      )}
    </form>
  );
}
