import { getTranslations } from "next-intl/server";
import { notFound } from "next/navigation";
import { Link } from "@/i18n/navigation";
import { api, ApiError } from "@/lib/api";
import { PageHeader } from "@/components/ui/page-header";
import { ArticleBody } from "@/components/news/article-body";
import { SafeImage } from "@/components/news/safe-image";

export default async function NewsDetailPage({
  params,
}: {
  params: Promise<{ locale: string; slug: string }>;
}) {
  const { locale, slug } = await params;
  const t = await getTranslations({ locale, namespace: "News" });

  let item;
  try {
    const res = await api.newsItem(locale, slug);
    item = res.data;
  } catch (err) {
    if (err instanceof ApiError && err.status === 404) notFound();
    throw err;
  }

  return (
    <div>
      <PageHeader title={item.title} />

      <div className="mx-auto max-w-3xl px-4 py-14">
        <Link href="/news" className="text-sm font-semibold text-brand hover:underline">
          ← {t("backToList")}
        </Link>

        {item.cover_image_url && (
          <SafeImage
            src={item.cover_image_url}
            alt={item.title}
            className="mt-6 h-72 w-full rounded-xl object-cover sm:h-96"
          />
        )}

        {item.published_at && (
          <p className="mt-6 text-sm text-slate-500">
            {t("publishedOn")}{" "}
            {new Date(item.published_at).toLocaleDateString(
              locale === "lo" ? "lo-LA" : "en-US"
            )}
          </p>
        )}

        {item.body && (
          <ArticleBody
            html={item.body}
            className="prose prose-slate mt-6 max-w-none leading-relaxed text-slate-700"
          />
        )}
      </div>
    </div>
  );
}
