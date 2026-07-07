"use client";

import { useEffect, useState } from "react";
import { useLocale, useTranslations } from "next-intl";
import { useAuth } from "@/lib/auth-context";
import { api, type MyTrainingRegistration } from "@/lib/api";
import { Card, Badge } from "@/components/ui/card";

function statusTone(status: string): "brand" | "green" | "amber" | "slate" {
  if (status === "attended" || status === "registered") return "green";
  if (status === "no_show" || status === "cancelled") return "slate";
  return "amber";
}

export default function PortalTrainingsPage() {
  const t = useTranslations("Portal");
  const locale = useLocale();
  const { token } = useAuth();
  const [registrations, setRegistrations] = useState<MyTrainingRegistration[] | null>(null);

  useEffect(() => {
    if (!token) return;
    api.myTrainingRegistrations(locale, token).then((res) => setRegistrations(res.data));
  }, [locale, token]);

  if (registrations === null) return null;

  if (registrations.length === 0) {
    return <p className="text-sm text-slate-500">{t("noTrainings")}</p>;
  }

  return (
    <div className="space-y-4">
      {registrations.map((reg) => (
        <Card key={reg.id} className="flex flex-wrap items-center justify-between gap-3">
          <div>
            <p className="font-semibold text-slate-800">{reg.training_course?.title}</p>
            <p className="text-xs text-slate-500">
              {t("registeredAt")}: {new Date(reg.registered_at).toLocaleDateString()}
            </p>
          </div>
          <Badge tone={statusTone(reg.status)}>{reg.status.replace(/_/g, " ")}</Badge>
        </Card>
      ))}
    </div>
  );
}
