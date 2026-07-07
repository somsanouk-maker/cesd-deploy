import { getTranslations } from "next-intl/server";
import { api } from "@/lib/api";
import { safe } from "@/lib/safe";
import { PageHeader } from "@/components/ui/page-header";
import { Card } from "@/components/ui/card";
import { ServiceRequestForm } from "@/components/forms/service-request-form";

export default async function RequestServicePage({
  params,
  searchParams,
}: {
  params: Promise<{ locale: string }>;
  searchParams: Promise<{ service?: string }>;
}) {
  const { locale } = await params;
  const sp = await searchParams;
  const t = await getTranslations({ locale, namespace: "ServiceRequest" });

  const [services, laboratories] = await Promise.all([
    safe(api.services(locale), { data: [] }),
    safe(api.laboratories(locale), { data: [] }),
  ]);

  return (
    <div>
      <PageHeader title={t("title")} subtitle={t("subtitle")} />

      <div className="mx-auto max-w-2xl px-4 py-14">
        <Card>
          <ServiceRequestForm
            services={services.data}
            laboratories={laboratories.data}
            defaultServiceId={sp.service}
          />
        </Card>
      </div>
    </div>
  );
}
