"use client";

import { useEffect, useState } from "react";
import { useParams } from "next/navigation";
import { useLocale, useTranslations } from "next-intl";
import { Link } from "@/i18n/navigation";
import { useAuth } from "@/lib/auth-context";
import { api, type MyServiceRequest } from "@/lib/api";
import { Card, Badge } from "@/components/ui/card";

export default function PortalRequestDetailPage() {
  const t = useTranslations("Portal");
  const locale = useLocale();
  const { token } = useAuth();
  const params = useParams<{ id: string }>();
  const [request, setRequest] = useState<MyServiceRequest | null>(null);
  const [responding, setResponding] = useState(false);

  function load() {
    if (!token) return;
    api.myServiceRequest(locale, token, params.id).then((res) => setRequest(res.data));
  }

  useEffect(load, [locale, token, params.id]);

  async function respond(response: "accepted" | "declined") {
    if (!token) return;
    setResponding(true);
    try {
      const res = await api.respondToQuotation(locale, token, params.id, response);
      setRequest(res.data);
    } finally {
      setResponding(false);
    }
  }

  if (!request) return null;

  return (
    <div>
      <Link href="/portal/requests" className="text-sm font-semibold text-brand hover:underline">
        ← {t("backToDashboard")}
      </Link>

      <Card className="mt-4">
        <div className="flex flex-wrap items-center justify-between gap-3">
          <div>
            <h2 className="text-lg font-bold text-slate-800">{request.title}</h2>
            <p className="text-xs text-slate-500">
              {t("requestNo")}: {request.request_no}
            </p>
          </div>
          <Badge>{request.status.replace(/_/g, " ")}</Badge>
        </div>

        <p className="mt-4 text-sm text-slate-700">{request.description}</p>

        {request.staff_notes && (
          <p className="mt-3 rounded-lg bg-slate-50 p-3 text-sm text-slate-600">
            {request.staff_notes}
          </p>
        )}
      </Card>

      {request.quotation_status !== "not_quoted" && (
        <Card className="mt-6">
          <h3 className="font-semibold text-brand-dark">{t("quotation")}</h3>
          <p className="mt-2 text-2xl font-bold text-slate-800">
            {request.quoted_amount ? `LAK ${Number(request.quoted_amount).toLocaleString()}` : "—"}
          </p>
          {request.quotation_notes && (
            <p className="mt-2 text-sm text-slate-600">{request.quotation_notes}</p>
          )}

          {request.quotation_status === "quoted" && (
            <div className="mt-4 flex gap-3">
              <button
                type="button"
                disabled={responding}
                onClick={() => respond("accepted")}
                className="rounded-full bg-emerald-600 px-5 py-2 text-sm font-semibold text-white transition-transform hover:scale-105 disabled:opacity-60"
              >
                {t("accept")}
              </button>
              <button
                type="button"
                disabled={responding}
                onClick={() => respond("declined")}
                className="rounded-full border border-red-300 px-5 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 disabled:opacity-60"
              >
                {t("decline")}
              </button>
            </div>
          )}

          {request.quotation_status === "accepted" && (
            <p className="mt-3 text-sm font-medium text-emerald-700">{t("quotationAccepted")}</p>
          )}
          {request.quotation_status === "declined" && (
            <p className="mt-3 text-sm font-medium text-red-600">{t("quotationDeclined")}</p>
          )}
        </Card>
      )}
    </div>
  );
}
