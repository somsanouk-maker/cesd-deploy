import { getTranslations } from "next-intl/server";
import { Link } from "@/i18n/navigation";
import { api } from "@/lib/api";
import { safe } from "@/lib/safe";
import { PageHeader } from "@/components/ui/page-header";
import { Card, Badge } from "@/components/ui/card";
import { LabIcon } from "@/components/icons";

export default async function LaboratoriesPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  const t = await getTranslations({ locale, namespace: "Laboratories" });
  const labs = await safe(api.laboratories(locale), { data: [] });

  return (
    <div>
      <PageHeader title={t("title")} subtitle={t("subtitle")} />

      <div className="mx-auto max-w-6xl px-4 py-14">
        <div className="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          {labs.data.map((lab) => (
            <Link key={lab.id} href={`/laboratories/${lab.code}`}>
              <Card className="h-full !p-0 overflow-hidden">
                <div className="relative flex h-36 items-center justify-center bg-brand-light">
                  {lab.photo_url ? (
                    // eslint-disable-next-line @next/next/no-img-element
                    <img
                      src={lab.photo_url}
                      alt={lab.name}
                      className="h-full w-full object-cover"
                    />
                  ) : (
                    <LabIcon code={lab.code} className="h-12 w-12 text-brand" />
                  )}
                  <div className="absolute left-3 top-3 flex h-9 w-9 items-center justify-center rounded-full bg-white/90 shadow">
                    <LabIcon code={lab.code} className="h-5 w-5 text-brand-dark" />
                  </div>
                </div>
                <div className="p-5">
                  <div className="flex items-start justify-between gap-2">
                    <Badge>{lab.code}</Badge>
                    {lab.equipment_count > 0 && (
                      <Badge tone="slate">{lab.equipment_count} items</Badge>
                    )}
                  </div>
                  <h3 className="mt-3 text-lg font-semibold text-slate-800">
                    {lab.name}
                  </h3>
                  {lab.room_name && (
                    <p className="mt-1 text-sm text-slate-500">
                      {lab.building} · {lab.room_name}
                    </p>
                  )}
                  {lab.description && (
                    <p className="mt-2 line-clamp-2 text-sm text-slate-600">
                      {lab.description}
                    </p>
                  )}
                </div>
              </Card>
            </Link>
          ))}
        </div>
      </div>
    </div>
  );
}
