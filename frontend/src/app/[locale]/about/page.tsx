import Image from "next/image";
import { getTranslations } from "next-intl/server";
import { PageHeader } from "@/components/ui/page-header";
import { Card } from "@/components/ui/card";
import { api } from "@/lib/api";
import { safe } from "@/lib/safe";

const GALLERY = [
  { src: "/images/facility/drone-1.jpg", span: "sm:col-span-2 sm:row-span-2" },
  { src: "/images/facility/exhibition-1.jpg", span: "" },
  { src: "/images/facility/entrance-1.jpg", span: "" },
  { src: "/images/facility/exterior-1.jpg", span: "" },
  { src: "/images/facility/exterior-2.jpg", span: "" },
  { src: "/images/facility/exhibition-2.jpg", span: "sm:col-span-2" },
  { src: "/images/facility/drone-2.jpg", span: "" },
];

export default async function AboutPage({
  params,
}: {
  params: Promise<{ locale: string }>;
}) {
  const { locale } = await params;
  const t = await getTranslations({ locale, namespace: "About" });

  const about = await safe(api.about(locale), {
    data: {
      title: t("title"),
      background: null,
      vision: null,
      mission: null,
      objectives: [],
      organization: {
        director: null,
        deputyDirector: null,
        admin: null,
        technical: null,
        innovation: null,
      },
    },
  });
  const content = about.data;

  return (
    <div>
      <PageHeader title={content.title || t("title")} />

      <div className="mx-auto max-w-4xl space-y-12 px-4 py-14">
        {content.background && (
          <section>
            <h2 className="text-xl font-bold text-brand-dark">
              {t("backgroundTitle")}
            </h2>
            <p className="mt-3 leading-relaxed text-slate-700">
              {content.background}
            </p>
          </section>
        )}

        <section>
          <h2 className="text-xl font-bold text-brand-dark">
            {t("galleryTitle")}
          </h2>
          <div className="mt-4 grid auto-rows-[140px] grid-cols-2 gap-3 sm:grid-cols-4">
            {GALLERY.map((item) => (
              <div
                key={item.src}
                className={`relative overflow-hidden rounded-xl ${item.span}`}
              >
                <Image
                  src={item.src}
                  alt=""
                  fill
                  sizes="(min-width: 640px) 25vw, 50vw"
                  className="object-cover transition-transform hover:scale-105"
                />
              </div>
            ))}
          </div>
        </section>

        {(content.vision || content.mission) && (
          <section className="grid gap-6 sm:grid-cols-2">
            {content.vision && (
              <Card>
                <h2 className="text-lg font-bold text-brand-dark">
                  {t("visionTitle")}
                </h2>
                <p className="mt-2 leading-relaxed text-slate-700">
                  {content.vision}
                </p>
              </Card>
            )}
            {content.mission && (
              <Card>
                <h2 className="text-lg font-bold text-brand-dark">
                  {t("missionTitle")}
                </h2>
                <p className="mt-2 leading-relaxed text-slate-700">
                  {content.mission}
                </p>
              </Card>
            )}
          </section>
        )}

        {content.objectives.length > 0 && (
          <section>
            <h2 className="text-xl font-bold text-brand-dark">
              {t("objectivesTitle")}
            </h2>
            <ul className="mt-3 list-inside list-disc space-y-2 text-slate-700">
              {content.objectives.map((objective) => (
                <li key={objective}>{objective}</li>
              ))}
            </ul>
          </section>
        )}

        {(content.organization.director || content.organization.deputyDirector) && (
          <section>
            <h2 className="text-xl font-bold text-brand-dark">
              {t("orgTitle")}
            </h2>
            <div className="mt-4 space-y-3">
              {content.organization.director && (
                <Card className="border-brand bg-brand-light">
                  <p className="font-semibold text-brand-dark">
                    {content.organization.director}
                  </p>
                </Card>
              )}
              {content.organization.deputyDirector && (
                <Card>
                  <p className="font-semibold text-slate-800">
                    {content.organization.deputyDirector}
                  </p>
                </Card>
              )}
              <div className="grid gap-3 sm:grid-cols-3">
                {content.organization.admin && (
                  <Card>
                    <p className="text-sm font-medium text-slate-700">
                      {content.organization.admin}
                    </p>
                  </Card>
                )}
                {content.organization.technical && (
                  <Card>
                    <p className="text-sm font-medium text-slate-700">
                      {content.organization.technical}
                    </p>
                  </Card>
                )}
                {content.organization.innovation && (
                  <Card>
                    <p className="text-sm font-medium text-slate-700">
                      {content.organization.innovation}
                    </p>
                  </Card>
                )}
              </div>
            </div>
          </section>
        )}
      </div>
    </div>
  );
}
