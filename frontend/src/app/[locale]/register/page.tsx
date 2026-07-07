"use client";

import { FormEvent, useState } from "react";
import { useTranslations } from "next-intl";
import { Link, useRouter } from "@/i18n/navigation";
import { useAuth } from "@/lib/auth-context";
import { PageHeader } from "@/components/ui/page-header";

export default function RegisterPage() {
  const t = useTranslations("Auth");
  const router = useRouter();
  const { register } = useAuth();
  const [status, setStatus] = useState<"idle" | "submitting" | "error">("idle");

  async function handleSubmit(e: FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setStatus("submitting");
    const form = new FormData(e.currentTarget);

    try {
      await register({
        name: String(form.get("name")),
        email: String(form.get("email")),
        password: String(form.get("password")),
        phone: String(form.get("phone") ?? "") || undefined,
        organization: String(form.get("organization") ?? "") || undefined,
      });
      router.push("/portal");
    } catch {
      setStatus("error");
    }
  }

  return (
    <div>
      <PageHeader title={t("registerTitle")} subtitle={t("registerSubtitle")} />

      <div className="mx-auto max-w-md px-4 py-14">
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="text-sm font-medium text-slate-700">{t("name")}</label>
            <input
              name="name"
              required
              className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
            />
          </div>
          <div>
            <label className="text-sm font-medium text-slate-700">{t("email")}</label>
            <input
              type="email"
              name="email"
              required
              className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
            />
          </div>
          <div>
            <label className="text-sm font-medium text-slate-700">{t("password")}</label>
            <input
              type="password"
              name="password"
              required
              minLength={8}
              className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
            />
          </div>
          <div>
            <label className="text-sm font-medium text-slate-700">{t("phone")}</label>
            <input
              name="phone"
              className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
            />
          </div>
          <div>
            <label className="text-sm font-medium text-slate-700">{t("organization")}</label>
            <input
              name="organization"
              className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
            />
          </div>

          <button
            type="submit"
            disabled={status === "submitting"}
            className="w-full rounded-full bg-brand px-6 py-3 text-sm font-semibold text-white transition-transform hover:scale-105 disabled:opacity-60"
          >
            {t("submitRegister")}
          </button>

          {status === "error" && (
            <p className="text-sm font-medium text-red-600">{t("errorMessage")}</p>
          )}
        </form>

        <p className="mt-6 text-center text-sm text-slate-600">
          {t("hasAccount")}{" "}
          <Link href="/login" className="font-semibold text-brand hover:underline">
            {t("signIn")}
          </Link>
        </p>
      </div>
    </div>
  );
}
