"use client";

import { ReactNode, useEffect } from "react";
import { useTranslations } from "next-intl";
import { Link, usePathname, useRouter } from "@/i18n/navigation";
import { useAuth } from "@/lib/auth-context";
import { PageHeader } from "@/components/ui/page-header";

export default function PortalLayout({ children }: { children: ReactNode }) {
  const t = useTranslations("Portal");
  const { user, isLoading } = useAuth();
  const router = useRouter();
  const pathname = usePathname();

  useEffect(() => {
    if (!isLoading && !user) {
      router.replace("/login");
    }
  }, [isLoading, user, router]);

  if (isLoading || !user) {
    return null;
  }

  const navLinks = [
    { href: "/portal", label: t("nav.dashboard") },
    { href: "/portal/requests", label: t("nav.requests") },
    { href: "/portal/bookings", label: t("nav.bookings") },
    { href: "/portal/trainings", label: t("nav.trainings") },
  ];

  return (
    <div>
      <PageHeader title={t("dashboardTitle")} subtitle={t("welcome", { name: user.name })} />

      <div className="mx-auto max-w-5xl px-4 py-10">
        <nav className="flex flex-wrap gap-2 border-b border-slate-200 pb-4">
          {navLinks.map((link) => {
            const active =
              link.href === "/portal" ? pathname === "/portal" : pathname.startsWith(link.href);
            return (
              <Link
                key={link.href}
                href={link.href}
                className={`rounded-full px-4 py-2 text-sm font-semibold transition-colors ${
                  active
                    ? "bg-brand text-white"
                    : "bg-slate-100 text-slate-600 hover:bg-slate-200"
                }`}
              >
                {link.label}
              </Link>
            );
          })}
        </nav>

        <div className="mt-8">{children}</div>
      </div>
    </div>
  );
}
