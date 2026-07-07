"use client";

import { FormEvent, useState } from "react";
import { useLocale, useTranslations } from "next-intl";
import { api } from "@/lib/api";
import type { Laboratory, ServiceItem } from "@/lib/api";

export function ServiceRequestForm({
  services,
  laboratories,
  defaultServiceId,
}: {
  services: ServiceItem[];
  laboratories: Laboratory[];
  defaultServiceId?: string;
}) {
  const t = useTranslations("ServiceRequest");
  const locale = useLocale();
  const [status, setStatus] = useState<"idle" | "submitting" | "success" | "error">(
    "idle"
  );
  const [requestNo, setRequestNo] = useState<string | null>(null);

  async function handleSubmit(e: FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setStatus("submitting");
    const form = new FormData(e.currentTarget);
    const laboratoryId = String(form.get("laboratory_id") ?? "");

    try {
      const res = await api.submitServiceRequest(locale, {
        service_id: Number(form.get("service_id")),
        laboratory_id: laboratoryId ? Number(laboratoryId) : null,
        title: String(form.get("title")),
        description: String(form.get("description")),
        sample_information: String(form.get("sample_information") ?? ""),
        required_date: String(form.get("required_date") ?? ""),
        contact_name: String(form.get("contact_name")),
        contact_email: String(form.get("contact_email")),
        contact_phone: String(form.get("contact_phone") ?? ""),
        organization: String(form.get("organization") ?? ""),
      });
      setRequestNo(res.data.request_no);
      setStatus("success");
      e.currentTarget.reset();
    } catch {
      setStatus("error");
    }
  }

  if (status === "success") {
    return (
      <div className="rounded-lg bg-emerald-50 p-6 text-emerald-800">
        <h3 className="text-lg font-bold">{t("successTitle")}</h3>
        <p className="mt-2">
          {t("successMessage", { requestNo: requestNo ?? "" })}
        </p>
      </div>
    );
  }

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <div>
        <label className="text-sm font-medium text-slate-700">
          {t("service")}
        </label>
        <select
          name="service_id"
          required
          defaultValue={defaultServiceId ?? ""}
          className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
        >
          <option value="" disabled>
            {t("selectService")}
          </option>
          {services.map((service) => (
            <option key={service.id} value={service.id}>
              {service.name}
            </option>
          ))}
        </select>
      </div>

      <div>
        <label className="text-sm font-medium text-slate-700">
          {t("laboratory")}
        </label>
        <select
          name="laboratory_id"
          defaultValue=""
          className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
        >
          <option value="">{t("selectLaboratory")}</option>
          {laboratories.map((lab) => (
            <option key={lab.id} value={lab.id}>
              {lab.name}
            </option>
          ))}
        </select>
      </div>

      <div>
        <label className="text-sm font-medium text-slate-700">
          {t("requestTitle")}
        </label>
        <input
          name="title"
          required
          className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
        />
      </div>

      <div>
        <label className="text-sm font-medium text-slate-700">
          {t("description")}
        </label>
        <textarea
          name="description"
          required
          rows={4}
          className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
        />
      </div>

      <div>
        <label className="text-sm font-medium text-slate-700">
          {t("sampleInformation")}
        </label>
        <textarea
          name="sample_information"
          rows={2}
          className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
        />
      </div>

      <div>
        <label className="text-sm font-medium text-slate-700">
          {t("requiredDate")}
        </label>
        <input
          type="date"
          name="required_date"
          className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
        />
      </div>

      <div className="grid gap-4 sm:grid-cols-2">
        <div>
          <label className="text-sm font-medium text-slate-700">
            {t("contactName")}
          </label>
          <input
            name="contact_name"
            required
            className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
          />
        </div>
        <div>
          <label className="text-sm font-medium text-slate-700">
            {t("contactEmail")}
          </label>
          <input
            type="email"
            name="contact_email"
            required
            className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
          />
        </div>
        <div>
          <label className="text-sm font-medium text-slate-700">
            {t("contactPhone")}
          </label>
          <input
            name="contact_phone"
            className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
          />
        </div>
        <div>
          <label className="text-sm font-medium text-slate-700">
            {t("organization")}
          </label>
          <input
            name="organization"
            className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
          />
        </div>
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
