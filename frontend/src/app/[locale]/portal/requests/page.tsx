"use client";

import { useEffect, useState } from "react";
import { useLocale, useTranslations } from "next-intl";
import { Link } from "@/i18n/navigation";
import { useAuth } from "@/lib/auth-context";
import { api, type MyServiceRequest } from "@/lib/api";
import { Card, Badge } from "@/components/ui/card";

function statusTone(status: string): "brand" | "green" | "amber" | "slate" {
  if (status === "completed" || status === "accepted") return "green";
  if (status === "rejected" || status === "declined") return "slate";
  if (status === "under_review" || status === "in_progress") return "amber";
  return "brand";
}

export default function PortalRequestsPage() {
  const t = useTranslations("Portal");
  const locale = useLocale();
  const { token } = useAuth();
  const [requests, setRequests] = useState<MyServiceRequest[] | null>(null);

  useEffect(() => {
    if (!token) return;
    api.myServiceRequests(locale, token).then((res) => setRequests(res.data)).catch(() => setRequests([]));
  }, [locale, token]);

  if (requests === null) return null;

  if (requests.length === 0) {
    return <p className="text-sm text-slate-500">{t("noRequests")}</p>;
  }

  return (
    <div className="space-y-4">
      {requests.map((req) => (
        <Link key={req.id} href={`/portal/requests/${req.id}`}>
          <Card className="flex flex-wrap items-center justify-between gap-3">
            <div>
              <p className="font-semibold text-slate-800">{req.title}</p>
              <p className="text-xs text-slate-500">
                {t("requestNo")}: {req.request_no}
                {req.service && ` · ${req.service.name}`}
              </p>
            </div>
            <div className="flex items-center gap-2">
              <Badge tone={statusTone(req.status)}>{req.status.replace(/_/g, " ")}</Badge>
              {req.quotation_status !== "not_quoted" && (
                <Badge tone={statusTone(req.quotation_status)}>
                  {t("quotation")}: {req.quotation_status}
                </Badge>
              )}
            </div>
          </Card>
        </Link>
      ))}
    </div>
  );
}
