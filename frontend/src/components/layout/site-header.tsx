"use client";

import { useState } from "react";
import { useTranslations } from "next-intl";
import { Link, usePathname } from "@/i18n/navigation";
import { LocaleSwitcher } from "./locale-switcher";
import { NuolLogo } from "./partner-logo";
import { useAuth } from "@/lib/auth-context";

export function SiteHeader() {
  const t = useTranslations("Nav");
  const pathname = usePathname();
  const [open, setOpen] = useState(false);
  const { user, logout } = useAuth();

  const links = [
    { href: "/", label: t("home") },
    { href: "/about", label: t("about") },
    { href: "/laboratories", label: t("laboratories") },
    { href: "/equipment", label: t("equipment") },
    { href: "/services", label: t("services") },
    { href: "/training", label: t("training") },
    { href: "/news", label: t("news") },
    { href: "/partnership", label: t("partnership") },
    { href: "/contact", label: t("contact") },
  ];

  return (
    <header className="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
      <div className="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3">
        <Link href="/" className="flex items-center gap-2 font-bold text-brand-dark">
          <NuolLogo className="h-9 w-9" />
          <span className="hidden sm:inline">CESD</span>
        </Link>

        <nav className="hidden items-center gap-5 lg:flex">
          {links.map((link) => {
            const active =
              link.href === "/"
                ? pathname === "/"
                : pathname.startsWith(link.href);
            return (
              <Link
                key={link.href}
                href={link.href}
                className={`text-sm font-medium transition-colors ${
                  active
                    ? "text-brand"
                    : "text-slate-600 hover:text-brand-dark"
                }`}
              >
                {link.label}
              </Link>
            );
          })}
        </nav>

        <div className="hidden items-center gap-3 lg:flex">
          <LocaleSwitcher />
          {user ? (
            <>
              <Link
                href="/portal"
                className="text-sm font-medium text-slate-600 hover:text-brand-dark"
              >
                {t("portal")}
              </Link>
              <button
                type="button"
                onClick={logout}
                className="text-sm font-medium text-slate-600 hover:text-brand-dark"
              >
                {t("logout")}
              </button>
            </>
          ) : (
            <Link
              href="/login"
              className="text-sm font-medium text-slate-600 hover:text-brand-dark"
            >
              {t("login")}
            </Link>
          )}
          <Link
            href="/request-service"
            className="rounded-full bg-accent px-4 py-2 text-sm font-semibold text-slate-900 transition-transform hover:scale-105"
          >
            {t("requestService")}
          </Link>
        </div>

        <button
          type="button"
          className="lg:hidden"
          onClick={() => setOpen((v) => !v)}
          aria-label="Toggle menu"
        >
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
            <path
              d="M4 6h16M4 12h16M4 18h16"
              stroke="currentColor"
              strokeWidth="2"
              strokeLinecap="round"
            />
          </svg>
        </button>
      </div>

      {open && (
        <nav className="flex flex-col gap-1 border-t border-slate-200 px-4 py-3 lg:hidden">
          {links.map((link) => (
            <Link
              key={link.href}
              href={link.href}
              onClick={() => setOpen(false)}
              className="rounded px-2 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100"
            >
              {link.label}
            </Link>
          ))}
          {user ? (
            <>
              <Link
                href="/portal"
                onClick={() => setOpen(false)}
                className="rounded px-2 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100"
              >
                {t("portal")}
              </Link>
              <button
                type="button"
                onClick={() => {
                  logout();
                  setOpen(false);
                }}
                className="rounded px-2 py-2 text-left text-sm font-medium text-slate-700 hover:bg-slate-100"
              >
                {t("logout")}
              </button>
            </>
          ) : (
            <Link
              href="/login"
              onClick={() => setOpen(false)}
              className="rounded px-2 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100"
            >
              {t("login")}
            </Link>
          )}
          <Link
            href="/request-service"
            onClick={() => setOpen(false)}
            className="mt-2 rounded-full bg-accent px-4 py-2 text-center text-sm font-semibold text-slate-900"
          >
            {t("requestService")}
          </Link>
          <div className="mt-2">
            <LocaleSwitcher />
          </div>
        </nav>
      )}
    </header>
  );
}
