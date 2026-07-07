"use client";

import { FormEvent, useState } from "react";
import { useTranslations } from "next-intl";
import { Link, useRouter } from "@/i18n/navigation";
import { useAuth } from "@/lib/auth-context";
import { PageHeader } from "@/components/ui/page-header";

export default function LoginPage() {
  const t = useTranslations("Auth");
  const router = useRouter();
  const { login } = useAuth();
  const [status, setStatus] = useState<"idle" | "submitting" | "error">("idle");

  async function handleSubmit(e: FormEvent<HTMLFormElement>) {
    e.preventDefault();
    setStatus("submitting");
    const form = new FormData(e.currentTarget);

    try {
      await login(String(form.get("email")), String(form.get("password")));
      router.push("/portal");
    } catch {
      setStatus("error");
    }
  }

  return (
    <div>
      <PageHeader title={t("loginTitle")} subtitle={t("loginSubtitle")} />

      <div className="mx-auto max-w-md px-4 py-14">
        <form onSubmit={handleSubmit} className="space-y-4">
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
              className="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"
            />
          </div>

          <button
            type="submit"
            disabled={status === "submitting"}
            className="w-full rounded-full bg-brand px-6 py-3 text-sm font-semibold text-white transition-transform hover:scale-105 disabled:opacity-60"
          >
            {t("submitLogin")}
          </button>

          {status === "error" && (
            <p className="text-sm font-medium text-red-600">{t("errorMessage")}</p>
          )}
        </form>

        <p className="mt-6 text-center text-sm text-slate-600">
          {t("noAccount")}{" "}
          <Link href="/register" className="font-semibold text-brand hover:underline">
            {t("createAccount")}
          </Link>
        </p>
      </div>
    </div>
  );
}
