import { getTranslations } from "next-intl/server";
import { notFound } from "next/navigation";
import { Link } from "@/i18n/navigation";
import { api, ApiError } from "@/lib/api";
import { PageHeader } from "@/components/ui/page-header";
import { Card, Badge } from "@/components/ui/card";
import { BookingForm } from "@/components/forms/booking-form";

export default async function EquipmentDetailPage({
  params,
}: {
  params: Promise<{ locale: string; code: string }>;
}) {
  const { locale, code } = await params;
  const t = await getTranslations({ locale, namespace: "Equipment" });

  let equipment;
  try {
    const res = await api.equipmentItem(locale, code);
    equipment = res.data;
  } catch (err) {
    if (err instanceof ApiError && err.status === 404) notFound();
    throw err;
  }

  return (
    <div>
      <PageHeader title={equipment.name} subtitle={equipment.code} />

      <div className="mx-auto max-w-4xl px-4 py-14">
        <Link href="/equipment" className="text-sm font-semibold text-brand hover:underline">
          ← {t("backToList")}
        </Link>

        {equipment.photo_url && (
          // eslint-disable-next-line @next/next/no-img-element
          <img
            src={equipment.photo_url}
            alt={equipment.name}
            className="mt-6 max-h-96 w-full rounded-xl border border-slate-200 object-contain bg-slate-50"
          />
        )}

        <div className="mt-6 grid gap-6 sm:grid-cols-2">
          <Card>
            <p className="text-xs font-semibold uppercase text-slate-500">
              {t("code")}
            </p>
            <p className="mt-1 text-slate-800">{equipment.code}</p>
          </Card>
          <Card>
            <p className="text-xs font-semibold uppercase text-slate-500">
              {t("brand")}
            </p>
            <p className="mt-1 text-slate-800">
              {[equipment.brand, equipment.model].filter(Boolean).join(" · ") || "—"}
            </p>
          </Card>
          {equipment.laboratory && (
            <Card>
              <p className="text-xs font-semibold uppercase text-slate-500">
                Laboratory
              </p>
              <Link
                href={`/laboratories/${equipment.laboratory.code}`}
                className="mt-1 block font-medium text-brand hover:underline"
              >
                {equipment.laboratory.name}
              </Link>
            </Card>
          )}
          <Card>
            <p className="text-xs font-semibold uppercase text-slate-500">
              {t("filterAvailability")}
            </p>
            <div className="mt-1">
              <Badge>{t(equipment.availability_status)}</Badge>
            </div>
          </Card>
        </div>

        {equipment.specification && (
          <Card className="mt-6">
            <h2 className="font-semibold text-brand-dark">
              {t("specification")}
            </h2>
            <p className="mt-2 whitespace-pre-line text-sm text-slate-700">
              {equipment.specification}
            </p>
          </Card>
        )}

        {equipment.capability && (
          <Card className="mt-6">
            <h2 className="font-semibold text-brand-dark">
              {t("capability")}
            </h2>
            <p className="mt-2 whitespace-pre-line text-sm text-slate-700">
              {equipment.capability}
            </p>
          </Card>
        )}

        {equipment.accessories && equipment.accessories.length > 0 && (
          <Card className="mt-6">
            <h2 className="font-semibold text-brand-dark">{t("accessories")}</h2>
            <ul className="mt-3 space-y-2">
              {equipment.accessories.map((acc) => (
                <li key={acc.id} className="text-sm text-slate-700">
                  {acc.name}
                  {acc.model && (
                    <span className="text-slate-500"> — {acc.model}</span>
                  )}
                </li>
              ))}
            </ul>
          </Card>
        )}

        <div className="mt-8 flex flex-wrap gap-3">
          <Link
            href="/request-service"
            className="inline-block rounded-full bg-accent px-6 py-3 text-sm font-semibold text-slate-900 hover:scale-105 transition-transform"
          >
            {t("requestThisEquipment")}
          </Link>
          {equipment.manual_url && (
            <a
              href={equipment.manual_url}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-block rounded-full border border-brand px-6 py-3 text-sm font-semibold text-brand hover:bg-brand-light"
            >
              {t("downloadDatasheet")}
            </a>
          )}
        </div>

        <div className="mt-8">
          <BookingForm bookableType="equipment" bookableId={equipment.id} />
        </div>
      </div>
    </div>
  );
}
