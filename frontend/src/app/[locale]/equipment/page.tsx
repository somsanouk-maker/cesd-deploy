import { getTranslations } from "next-intl/server";
import { Link } from "@/i18n/navigation";
import { api } from "@/lib/api";
import { safe } from "@/lib/safe";
import { PageHeader } from "@/components/ui/page-header";
import { Card, Badge } from "@/components/ui/card";
import { EquipmentFilters } from "@/components/equipment/equipment-filters";
import { EquipmentCategoryIcon } from "@/components/icons";

const AVAILABILITY_TONE: Record<string, "green" | "amber" | "slate"> = {
  available: "green",
  in_use: "amber",
  maintenance: "amber",
  retired: "slate",
};

export default async function EquipmentPage({
  params,
  searchParams,
}: {
  params: Promise<{ locale: string }>;
  searchParams: Promise<{
    q?: string;
    laboratory_id?: string;
    category_id?: string;
    availability_status?: string;
    page?: string;
  }>;
}) {
  const { locale } = await params;
  const sp = await searchParams;
  const t = await getTranslations({ locale, namespace: "Equipment" });

  const [laboratories, categories, equipment] = await Promise.all([
    safe(api.laboratories(locale), { data: [] }),
    safe(api.equipmentCategories(locale), { data: [] }),
    safe(
      api.equipment(locale, {
        q: sp.q,
        laboratory_id: sp.laboratory_id,
        category_id: sp.category_id,
        availability_status: sp.availability_status,
        page: sp.page ? Number(sp.page) : undefined,
      }),
      { data: [] }
    ),
  ]);

  return (
    <div>
      <PageHeader title={t("title")} subtitle={t("subtitle")} />

      <div className="mx-auto max-w-6xl px-4 py-10">
        <EquipmentFilters
          laboratories={laboratories.data}
          categories={categories.data}
          initial={sp}
        />

        {equipment.data.length === 0 ? (
          <p className="mt-10 text-center text-slate-500">{t("noResults")}</p>
        ) : (
          <div className="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            {equipment.data.map((eq) => (
              <Link key={eq.id} href={`/equipment/${eq.code}`}>
                <Card className="h-full !p-0 overflow-hidden">
                  <div className="flex h-36 items-center justify-center bg-slate-50">
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
                        className="h-12 w-12 text-slate-300"
                      />
                    )}
                  </div>
                  <div className="p-5">
                    <div className="flex items-start justify-between gap-2">
                      <Badge tone="slate">{eq.code}</Badge>
                      <Badge tone={AVAILABILITY_TONE[eq.availability_status]}>
                        {t(eq.availability_status)}
                      </Badge>
                    </div>
                    <h3 className="mt-3 font-semibold text-slate-800">
                      {eq.name}
                    </h3>
                    {(eq.brand || eq.model) && (
                      <p className="mt-1 text-xs text-slate-500">
                        {[eq.brand, eq.model].filter(Boolean).join(" · ")}
                      </p>
                    )}
                    {eq.laboratory && (
                      <p className="mt-2 text-xs font-medium text-brand">
                        {eq.laboratory.name}
                      </p>
                    )}
                  </div>
                </Card>
              </Link>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
