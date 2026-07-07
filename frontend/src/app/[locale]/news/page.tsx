import { getTranslations } from "next-intl/server";
import { Link } from "@/i18n/navigation";
import { api } from "@/lib/api";
import { safe } from "@/lib/safe";
import { PageHeader } from "@/components/ui/page-header";
import { Card } from "@/components/ui/card";

export default async function NewsPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  const t = await getTranslations({ locale, namespace: "News" });
  const news = await safe(api.news(locale), { data: [] });

  return (
    <div>
      <PageHeader title={t("title")} subtitle={t("subtitle")} />

      <div className="mx-auto max-w-6xl px-4 py-14">
        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {news.data.map((item) => (
            <Link key={item.id} href={`/news/${item.slug}`}>
              <Card className="h-full">
                {item.published_at && (
                  <p className="text-xs text-slate-500">
                    {new Date(item.published_at).toLocaleDateString(
                      locale === "lo" ? "lo-LA" : "en-US"
                    )}
                  </p>
                )}
                <h3 className="mt-2 text-lg font-semibold text-slate-800">
                  {item.title}
                </h3>
                {item.excerpt && (
                  <p className="mt-2 line-clamp-3 text-sm text-slate-600">
                    {item.excerpt}
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
