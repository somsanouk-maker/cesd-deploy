import { getTranslations } from "next-intl/server";
import { notFound } from "next/navigation";
import { Link } from "@/i18n/navigation";
import { api, ApiError } from "@/lib/api";
import { PageHeader } from "@/components/ui/page-header";
import { Card, Badge } from "@/components/ui/card";
import { EquipmentCategoryIcon } from "@/components/icons";

export default async function LaboratoryDetailPage({
  params,
}: {
  params: Promise<{ locale: string; code: string }>;
}) {
  const { locale, code } = await params;
  const t = await getTranslations({ locale, namespace: "Laboratories" });
  const tEquipment = await getTranslations({ locale, namespace: "Equipment" });

  let lab;
  try {
    const res = await api.laboratory(locale, code);
    lab = res.data;
  } catch (err) {
    if (err instanceof ApiError && err.status === 404) notFound();
    throw err;
  }

  const equipmentRes = await api
    .equipment(locale, { laboratory_id: String(lab.id) })
    .catch(() => ({ data: [] }));

  return (
    <div>
      <PageHeader title={lab.name} subtitle={lab.description ?? undefined} />

      <div className="mx-auto max-w-6xl px-4 py-14">
        <Link href="/laboratories" className="text-sm font-semibold text-brand hover:underline">
          ← {t("backToList")}
        </Link>

        {lab.photo_url && (
          // eslint-disable-next-line @next/next/no-img-element
          <img
            src={lab.photo_url}
            alt={lab.name}
            className="mt-6 h-72 w-full rounded-xl border border-slate-200 object-cover sm:h-96"
          />
        )}

        <div className="mt-6 grid gap-8 lg:grid-cols-3">
          <div className="lg:col-span-2">
            <h2 className="text-lg font-bold text-brand-dark">
              {t("relatedEquipment")}
            </h2>
            {equipmentRes.data.length === 0 ? (
              <p className="mt-3 text-sm text-slate-500">
                {t("noEquipment")}
              </p>
            ) : (
              <div className="mt-4 grid gap-4 sm:grid-cols-2">
                {equipmentRes.data.map((eq) => (
                  <Link key={eq.id} href={`/equipment/${eq.code}`}>
                    <Card className="flex h-full gap-3 !p-3">
                      <div className="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-slate-50">
                        {eq.photo_url ? (
                          // eslint-disable-next-line @next/next/no-img-element
                          <img
                            src={eq.photo_url}
                            alt={eq.name}
                            className="h-full w-full object-cover"
                          />
                        ) : (
                          <EquipmentCategoryIcon
                            categoryName={eq.category?.name}
                            className="h-7 w-7 text-slate-300"
                          />
                        )}
                      </div>
                      <div className="min-w-0">
                        <Badge tone="slate">{eq.code}</Badge>
                        <h3 className="mt-1 truncate font-semibold text-slate-800">
                          {eq.name}
                        </h3>
                        {eq.brand && (
                          <p className="mt-0.5 truncate text-xs text-slate-500">
                            {eq.brand} {eq.model}
                          </p>
                        )}
                        <p className="mt-1 text-xs font-medium text-brand">
                          {tEquipment(eq.availability_status)}
                        </p>
                      </div>
                    </Card>
                  </Link>
                ))}
              </div>
            )}
          </div>

          <div>
            <Card>
              <dl className="space-y-3 text-sm">
                <div>
                  <dt className="font-semibold text-slate-500">
                    {t("location")}
                  </dt>
                  <dd className="text-slate-800">
                    {[lab.building, lab.floor, lab.room_name]
                      .filter(Boolean)
                      .join(" · ") || "—"}
                  </dd>
                </div>
                <div>
                  <dt className="font-semibold text-slate-500">
                    {t("responsibleStaff")}
                  </dt>
                  <dd className="text-slate-800">
                    {lab.responsible_staff ?? "—"}
                  </dd>
                </div>
              </dl>
            </Card>
          </div>
        </div>
      </div>
    </div>
  );
}
