import { getLocale, getTranslations } from "next-intl/server";
import { Link } from "@/i18n/navigation";
import { NuolLogo } from "./partner-logo";
import { api } from "@/lib/api";
import { safe } from "@/lib/safe";

export async function SiteFooter() {
  const locale = await getLocale();
  const tFooter = await getTranslations("Footer");
  const tNav = await getTranslations("Nav");
  const tContact = await getTranslations("Contact");
  const year = new Date().getFullYear();

  const settings = await safe(api.settings(locale), { data: {} });
  const address = settings.data.address ?? tContact("address");

  return (
    <footer className="border-t border-slate-200 bg-slate-50">
      <div className="mx-auto grid max-w-6xl gap-8 px-4 py-10 sm:grid-cols-3">
        <div>
          <div className="flex items-center gap-2 font-bold text-brand-dark">
            <NuolLogo className="h-9 w-9" />
            <span>CESD</span>
          </div>
          <p className="mt-3 text-sm text-slate-600">{tFooter("tagline")}</p>
          <p className="text-sm text-slate-600">{tFooter("faculty")}</p>
        </div>

        <div>
          <h3 className="text-sm font-semibold uppercase tracking-wide text-slate-500">
            {tFooter("quickLinks")}
          </h3>
          <ul className="mt-3 space-y-2 text-sm">
            <li>
              <Link href="/laboratories" className="text-slate-600 hover:text-brand">
                {tNav("laboratories")}
              </Link>
            </li>
            <li>
              <Link href="/equipment" className="text-slate-600 hover:text-brand">
                {tNav("equipment")}
              </Link>
            </li>
            <li>
              <Link href="/services" className="text-slate-600 hover:text-brand">
                {tNav("services")}
              </Link>
            </li>
            <li>
              <Link href="/training" className="text-slate-600 hover:text-brand">
                {tNav("training")}
              </Link>
            </li>
          </ul>
        </div>

        <div>
          <h3 className="text-sm font-semibold uppercase tracking-wide text-slate-500">
            {tFooter("contactUs")}
          </h3>
          <p className="mt-3 text-sm text-slate-600">{address}</p>
          {settings.data.contact_email && (
            <p className="mt-1 text-sm text-slate-600">
              {settings.data.contact_email}
            </p>
          )}
          {settings.data.contact_phone && (
            <p className="text-sm text-slate-600">{settings.data.contact_phone}</p>
          )}
        </div>
      </div>
      <div className="border-t border-slate-200 py-4 text-center text-xs text-slate-500">
        © {year} CESD — {tFooter("faculty")}. {tFooter("rightsReserved")}
      </div>
    </footer>
  );
}
