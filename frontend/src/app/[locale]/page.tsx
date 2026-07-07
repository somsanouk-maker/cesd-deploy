import { getTranslations } from "next-intl/server";
import { Link } from "@/i18n/navigation";
import { api } from "@/lib/api";
import { safe } from "@/lib/safe";
import { Card, Badge } from "@/components/ui/card";
import { LabIcon, ServiceIcon } from "@/components/icons";
import { HeroCarousel } from "@/components/home/hero-carousel";
import { SafeImage } from "@/components/news/safe-image";

export default async function HomePage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  const t = await getTranslations({ locale, namespace: "Home" });
  const tCommon = await getTranslations({ locale, namespace: "Common" });

  const [labs, services, news] = await Promise.all([
    safe(api.laboratories(locale), { data: [] }),
    safe(api.services(locale), { data: [] }),
    safe(api.news(locale), { data: [] }),
  ]);

  return (
    <div>
      <section className="relative h-[420px] overflow-hidden text-white sm:h-[520px]">
        <HeroCarousel alt="CESD building, Faculty of Engineering, National University of Laos" />
        <div className="absolute inset-0 bg-gradient-to-br from-brand-dark/90 to-brand/80" />
        <div className="relative mx-auto flex h-full max-w-6xl flex-col justify-center px-4">
          <h1 className="max-w-3xl text-3xl font-bold sm:text-5xl">
            {t("heroTitle")}
          </h1>
          <p className="mt-5 max-w-2xl text-lg text-blue-100">
            {t("heroSubtitle")}
          </p>
          <div className="mt-8 flex flex-wrap gap-3">
            <Link
              href="/request-service"
              className="rounded-full bg-accent px-6 py-3 text-sm font-semibold text-slate-900 transition-transform hover:scale-105"
            >
              {t("cta")}
            </Link>
            <Link
              href="/about"
              className="rounded-full border border-white/60 px-6 py-3 text-sm font-semibold text-white hover:bg-white/10"
            >
              {t("learnMore")}
            </Link>
          </div>
        </div>
      </section>

      <section className="mx-auto max-w-6xl px-4 py-16">
        <div className="flex items-end justify-between">
          <h2 className="text-2xl font-bold text-brand-dark">
            {t("newsTitle")}
          </h2>
          <Link href="/news" className="text-sm font-semibold text-brand hover:underline">
            {t("viewAll")}
          </Link>
        </div>
        <div className="mt-6 grid gap-4 sm:grid-cols-3">
          {news.data.slice(0, 3).map((item) => (
            <Link key={item.id} href={`/news/${item.slug}`}>
              <Card className="h-full !p-0 overflow-hidden">
                {item.cover_image_url && (
                  <SafeImage
                    src={item.cover_image_url}
                    alt={item.title}
                    className="h-40 w-full object-cover"
                  />
                )}
                <div className="p-5">
                  <h3 className="font-semibold text-slate-800">{item.title}</h3>
                  {item.excerpt && (
                    <p className="mt-2 line-clamp-3 text-sm text-slate-600">
                      {item.excerpt}
                    </p>
                  )}
                </div>
              </Card>
            </Link>
          ))}
        </div>
      </section>

      <section className="bg-slate-50 py-16">
        <div className="mx-auto max-w-6xl px-4">
          <div className="flex items-end justify-between">
            <div>
              <h2 className="text-2xl font-bold text-brand-dark">
                {t("labsTitle")}
              </h2>
              <p className="mt-1 text-slate-600">{t("labsSubtitle")}</p>
            </div>
            <Link href="/laboratories" className="text-sm font-semibold text-brand hover:underline">
              {t("viewAll")}
            </Link>
          </div>
          <div className="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {labs.data.length === 0 && (
              <p className="text-sm text-slate-500">{tCommon("loading")}</p>
            )}
            {labs.data.slice(0, 8).map((lab) => (
              <Link key={lab.id} href={`/laboratories/${lab.code}`}>
                <Card className="h-full !p-0 overflow-hidden bg-white">
                  <div className="flex h-28 items-center justify-center bg-brand-light">
                    {lab.photo_url ? (
                      // eslint-disable-next-line @next/next/no-img-element
                      <img
                        src={lab.photo_url}
                        alt={lab.name}
                        className="h-full w-full object-cover"
                      />
                    ) : (
                      <LabIcon code={lab.code} className="h-10 w-10 text-brand" />
                    )}
                  </div>
                  <div className="p-4">
                    <Badge>{lab.code}</Badge>
                    <h3 className="mt-3 font-semibold text-slate-800">
                      {lab.name}
                    </h3>
                    {lab.room_name && (
                      <p className="mt-1 text-xs text-slate-500">{lab.room_name}</p>
                    )}
                  </div>
                </Card>
              </Link>
            ))}
          </div>
        </div>
      </section>

      <section className="mx-auto max-w-6xl px-4 py-16">
        <div className="flex items-end justify-between">
          <div>
            <h2 className="text-2xl font-bold text-brand-dark">
              {t("servicesTitle")}
            </h2>
            <p className="mt-1 text-slate-600">{t("servicesSubtitle")}</p>
          </div>
          <Link href="/services" className="text-sm font-semibold text-brand hover:underline">
            {t("viewAll")}
          </Link>
        </div>
        <div className="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
          {services.data.slice(0, 6).map((service) => (
            <Link key={service.id} href={`/services/${service.slug}`}>
              <Card className="h-full">
                <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-light text-brand">
                  <ServiceIcon category={service.category} className="h-6 w-6" />
                </div>
                <h3 className="mt-3 font-semibold text-slate-800">
                  {service.name}
                </h3>
                {service.description && (
                  <p className="mt-2 line-clamp-2 text-sm text-slate-600">
                    {service.description}
                  </p>
                )}
              </Card>
            </Link>
          ))}
        </div>
      </section>
    </div>
  );
}
