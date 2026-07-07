import { getTranslations } from "next-intl/server";
import { notFound } from "next/navigation";
import { Link } from "@/i18n/navigation";
import { api, ApiError } from "@/lib/api";
import { PageHeader } from "@/components/ui/page-header";
import { Badge } from "@/components/ui/card";
import { ServiceIcon } from "@/components/icons";

export default async function ServiceDetailPage({
  params,
}: {
  params: Promise<{ locale: string; slug: string }>;
}) {
  const { locale, slug } = await params;
  const t = await getTranslations({ locale, namespace: "Services" });

  let service;
  try {
    const res = await api.service(locale, slug);
    service = res.data;
  } catch (err) {
    if (err instanceof ApiError && err.status === 404) notFound();
    throw err;
  }

  return (
    <div>
      <PageHeader title={service.name} />

      <div className="mx-auto max-w-3xl px-4 py-14">
        <Link href="/services" className="text-sm font-semibold text-brand hover:underline">
          ← {t("backToList")}
        </Link>

        <div className="mt-4 flex items-center gap-3">
          <div className="flex h-11 w-11 items-center justify-center rounded-lg bg-brand-light text-brand">
            <ServiceIcon category={service.category} className="h-6 w-6" />
          </div>
          <Badge tone="slate">{service.category}</Badge>
        </div>

        {service.description && (
          <p className="mt-4 leading-relaxed text-slate-700">
            {service.description}
          </p>
        )}

        <div className="mt-8">
          <Link
            href={{ pathname: "/request-service", query: { service: service.id } }}
            className="inline-block rounded-full bg-accent px-6 py-3 text-sm font-semibold text-slate-900 hover:scale-105 transition-transform"
          >
            {t("requestThisService")}
          </Link>
        </div>
      </div>
    </div>
  );
}
