import { getTranslations } from "next-intl/server";
import { notFound } from "next/navigation";
import { Link } from "@/i18n/navigation";
import { api, ApiError } from "@/lib/api";
import { PageHeader } from "@/components/ui/page-header";
import { Card } from "@/components/ui/card";

function formatDate(value: string | null, locale: string) {
  if (!value) return "—";
  return new Date(value).toLocaleDateString(locale === "lo" ? "lo-LA" : "en-US", {
    year: "numeric",
    month: "long",
    day: "numeric",
  });
}

export default async function TrainingDetailPage({
  params,
}: {
  params: Promise<{ locale: string; id: string }>;
}) {
  const { locale, id } = await params;
  const t = await getTranslations({ locale, namespace: "Training" });

  let course;
  try {
    const res = await api.trainingCourse(locale, id);
    course = res.data;
  } catch (err) {
    if (err instanceof ApiError && err.status === 404) notFound();
    throw err;
  }

  return (
    <div>
      <PageHeader title={course.title} />

      <div className="mx-auto max-w-3xl px-4 py-14">
        <Link href="/training" className="text-sm font-semibold text-brand hover:underline">
          ← {t("backToList")}
        </Link>

        {course.description && (
          <p className="mt-6 leading-relaxed text-slate-700">
            {course.description}
          </p>
        )}

        <Card className="mt-6">
          <dl className="grid gap-3 sm:grid-cols-2">
            <div>
              <dt className="text-xs font-semibold uppercase text-slate-500">
                {t("startDate")}
              </dt>
              <dd className="text-slate-800">
                {formatDate(course.start_date, locale)}
              </dd>
            </div>
            <div>
              <dt className="text-xs font-semibold uppercase text-slate-500">
                {t("endDate")}
              </dt>
              <dd className="text-slate-800">
                {formatDate(course.end_date, locale)}
              </dd>
            </div>
            <div>
              <dt className="text-xs font-semibold uppercase text-slate-500">
                {t("capacity")}
              </dt>
              <dd className="text-slate-800">{course.capacity ?? "—"}</dd>
            </div>
            <div>
              <dt className="text-xs font-semibold uppercase text-slate-500">
                {t("fee")}
              </dt>
              <dd className="text-slate-800">
                {course.fee ? `${course.fee.toLocaleString()} LAK` : t("free")}
              </dd>
            </div>
          </dl>
        </Card>

        <div className="mt-8">
          <Link
            href={{ pathname: "/request-service", query: { course: course.id } }}
            className="inline-block rounded-full bg-accent px-6 py-3 text-sm font-semibold text-slate-900 hover:scale-105 transition-transform"
          >
            {t("register")}
          </Link>
        </div>
      </div>
    </div>
  );
}
