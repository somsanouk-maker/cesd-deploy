"use client";

import { FormEvent, useState } from "react";
import { useLocale, useTranslations } from "next-intl";
import { api } from "@/lib/api";

export function PartnershipForm() {
  const t = useTranslations("Partnership");
  const locale = useLocale();
  const [status, setStatus] = useState<"idle" | "submitting" | "success" | "error">("idle");

  async function handleSubmit(e: FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setStatus("submitting");
    const formEl = e.currentTarget;
    const form = new FormData(formEl);

    try {
      await api.submitPartnershipInquiry(locale, {
        organization_name: String(form.get("organization_name")),
        contact_name: String(form.get("contact_name")),
        contact_email: String(form.get("contact_email")),
        contact_phone: String(form.get("contact_phone") ?? "") || undefined,
        inquiry_type: String(form.get("inquiry_type") ?? "") || undefined,
        message: String(form.get("message")),
      });
      setStatus("success");
      formEl.reset();
    } catch {
      setStatus("error");
    }
  }

  if (status === "success") {
    return (
      <div className="rounded-lg bg-emerald-50 p-6 text-emerald-800">
        <h3 className="text-lg font-bold">{t("successTitle")}</h3>
        <p className="mt-2">{t("successMessage")}</p>
      </div>
    );
  }

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <div className="grid gap-4 sm:grid-cols-2">
        <div>
          <label className="text-sm font-medium text-slate-700">{t("organizationName")}</label>
          <input
            name="organization_name"
            required
            className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
          />
        </div>
        <div>
          <label className="text-sm font-medium text-slate-700">{t("contactName")}</label>
          <input
            name="contact_name"
            required
            className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
          />
        </div>
        <div>
          <label className="text-sm font-medium text-slate-700">{t("contactEmail")}</label>
          <input
            type="email"
            name="contact_email"
            required
            className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
          />
        </div>
        <div>
          <label className="text-sm font-medium text-slate-700">{t("contactPhone")}</label>
          <input
            name="contact_phone"
            className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
          />
        </div>
      </div>

      <div>
        <label className="text-sm font-medium text-slate-700">{t("inquiryType")}</label>
        <select
          name="inquiry_type"
          defaultValue=""
          className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
        >
          <option value="">—</option>
          <option value="joint_rd">{t("inquiryTypes.joint_rd")}</option>
          <option value="industry_partnership">{t("inquiryTypes.industry_partnership")}</option>
          <option value="mou">{t("inquiryTypes.mou")}</option>
          <option value="other">{t("inquiryTypes.other")}</option>
        </select>
      </div>

      <div>
        <label className="text-sm font-medium text-slate-700">{t("message")}</label>
        <textarea
          name="message"
          required
          rows={5}
          className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
        />
      </div>

      <button
        type="submit"
        disabled={status === "submitting"}
        className="rounded-full bg-brand px-6 py-3 text-sm font-semibold text-white transition-transform hover:scale-105 disabled:opacity-60"
      >
        {t("submit")}
      </button>

      {status === "error" && (
        <p className="text-sm font-medium text-red-600">{t("errorMessage")}</p>
      )}
    </form>
  );
}
