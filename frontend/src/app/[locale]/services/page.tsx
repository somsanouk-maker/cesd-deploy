import { getTranslations } from "next-intl/server";
import { Link } from "@/i18n/navigation";
import { api } from "@/lib/api";
import { safe } from "@/lib/safe";
import { PageHeader } from "@/components/ui/page-header";
import { Card, Badge } from "@/components/ui/card";
import { ServiceIcon } from "@/components/icons";

export default async function ServicesPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  const t = await getTranslations({ locale, namespace: "Services" });
  const services = await safe(api.services(locale), { data: [] });

  return (
    <div>
      <PageHeader title={t("title")} subtitle={t("subtitle")} />

      <div className="mx-auto max-w-6xl px-4 py-14">
        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {services.data.map((service) => (
            <Link key={service.id} href={`/services/${service.slug}`}>
              <Card className="h-full">
                <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-brand-light text-brand">
                  <ServiceIcon category={service.category} className="h-6 w-6" />
                </div>
                <div className="mt-3">
                  <Badge tone="slate">{service.category}</Badge>
                </div>
                <h3 className="mt-2 text-lg font-semibold text-slate-800">
                  {service.name}
                </h3>
                {service.description && (
                  <p className="mt-2 line-clamp-3 text-sm text-slate-600">
                    {service.description}
                  </p>
                )}
              </Card>
            </Link>
          ))}
        </div>
      </div>
    </div>
  );
}
