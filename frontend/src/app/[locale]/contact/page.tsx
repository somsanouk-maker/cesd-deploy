import { getTranslations } from "next-intl/server";
import { api } from "@/lib/api";
import { safe } from "@/lib/safe";
import { PageHeader } from "@/components/ui/page-header";
import { Card } from "@/components/ui/card";
import { ContactForm } from "@/components/forms/contact-form";

export default async function ContactPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  const t = await getTranslations({ locale, namespace: "Contact" });
  const settings = await safe(api.settings(locale), { data: {} });

  return (
    <div>
      <PageHeader title={t("title")} subtitle={t("subtitle")} />

      <div className="mx-auto grid max-w-5xl gap-8 px-4 py-14 lg:grid-cols-5">
        <div className="lg:col-span-2 space-y-4">
          <Card>
            <h2 className="text-sm font-semibold uppercase text-slate-500">
              {t("addressTitle")}
            </h2>
            <p className="mt-2 text-slate-700">
              {settings.data.address ?? t("address")}
            </p>
          </Card>
          <Card>
            <h2 className="text-sm font-semibold uppercase text-slate-500">
              {t("phoneTitle")}
            </h2>
            <p className="mt-2 text-slate-700">
              {settings.data.contact_phone ?? "+856 21 xxx xxx"}
            </p>
          </Card>
          <Card>
            <h2 className="text-sm font-semibold uppercase text-slate-500">
              {t("emailTitle")}
            </h2>
            <p className="mt-2 text-slate-700">
              {settings.data.contact_email ?? "cesd@nuol.edu.la"}
            </p>
          </Card>
        </div>

        <div className="lg:col-span-3">
          <Card>
            <h2 className="text-lg font-bold text-brand-dark">
              {t("formTitle")}
            </h2>
            <div className="mt-4">
              <ContactForm />
            </div>
          </Card>
        </div>
      </div>
    </div>
  );
}
