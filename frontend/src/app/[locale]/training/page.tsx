import { getTranslations } from "next-intl/server";
import { Link } from "@/i18n/navigation";
import { api } from "@/lib/api";
import { safe } from "@/lib/safe";
import { PageHeader } from "@/components/ui/page-header";
import { Card } from "@/components/ui/card";

function formatDate(value: string | null, locale: string) {
  if (!value) return null;
  return new Date(value).toLocaleDateString(locale === "lo" ? "lo-LA" : "en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
}

export default async function TrainingPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  const t = await getTranslations({ locale, namespace: "Training" });
  const courses = await safe(api.trainingCourses(locale), { data: [] });

  return (
    <div>
      <PageHeader title={t("title")} subtitle={t("subtitle")} />

      <div className="mx-auto max-w-6xl px-4 py-14">
        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {courses.data.map((course) => (
            <Link key={course.id} href={`/training/${course.id}`}>
              <Card className="h-full">
                <h3 className="text-lg font-semibold text-slate-800">
                  {course.title}
                </h3>
                {course.description && (
                  <p className="mt-2 line-clamp-2 text-sm text-slate-600">
                    {course.description}
                  </p>
                )}
                <dl className="mt-3 space-y-1 text-xs text-slate-500">
                  {course.start_date && (
                    <div>
                      {t("startDate")}: {formatDate(course.start_date, locale)}
                    </div>
                  )}
                  <div>
                    {t("fee")}:{" "}
                    {course.fee ? `${course.fee.toLocaleString()} LAK` : t("free")}
                  </div>
                </dl>
              </Card>
            </Link>
          ))}
        </div>
      </div>
    </div>
  );
}
