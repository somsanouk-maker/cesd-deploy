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
    <header className="sticky top-0 z-40 bg-white/95 shadow-sm backdrop-blur">
      {/* Utility bar: locale + account links, kept out of the main nav row */}
      <div className="hidden border-b border-slate-100 bg-slate-50 lg:block">
        <div className="mx-auto flex max-w-7xl items-center justify-end gap-4 px-6 py-1.5">
          {user ? (
            <>
              <Link
                href="/portal"
                className="text-xs font-medium text-slate-500 transition-colors hover:text-brand-dark"
              >
                {t("portal")}
              </Link>
              <button
                type="button"
                onClick={logout}
                className="text-xs font-medium text-slate-500 transition-colors hover:text-brand-dark"
              >
                {t("logout")}
              </button>
            </>
          ) : (
            <Link
              href="/login"
              className="text-xs font-medium text-slate-500 transition-colors hover:text-brand-dark"
            >
              {t("login")}
            </Link>
          )}
          <span className="h-3 w-px bg-slate-200" aria-hidden="true" />
          <LocaleSwitcher />
        </div>
      </div>

      {/* Main nav row */}
      <div className="mx-auto flex max-w-7xl items-center justify-between gap-6 px-6 py-3.5">
        <Link href="/" className="flex shrink-0 items-center gap-2.5 font-bold text-brand-dark">
          <NuolLogo className="h-10 w-10" />
          <span className="hidden text-lg sm:inline">CESD</span>
        </Link>

        <nav className="hidden items-center gap-1 lg:flex">
          {links.map((link) => {
            const active =
              link.href === "/"
                ? pathname === "/"
                : pathname.startsWith(link.href);
            return (
              <Link
                key={link.href}
                href={link.href}
                className={`whitespace-nowrap rounded-full px-3 py-2 text-sm font-medium transition-colors ${
                  active
                    ? "bg-brand-light text-brand-dark"
                    : "text-slate-600 hover:bg-slate-50 hover:text-brand-dark"
                }`}
              >
                {link.label}
              </Link>
            );
          })}
        </nav>

        <Link
          href="/request-service"
          className="hidden shrink-0 rounded-full bg-accent px-5 py-2.5 text-sm font-semibold text-slate-900 shadow-sm transition-transform hover:scale-105 hover:shadow lg:inline-block"
        >
          {t("requestService")}
        </Link>

        <button
          type="button"
          className="rounded-lg p-1.5 text-slate-700 hover:bg-slate-100 lg:hidden"
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
        <nav className="flex flex-col gap-1 border-t border-slate-200 bg-white px-4 py-3 lg:hidden">
          {links.map((link) => {
            const active =
              link.href === "/"
                ? pathname === "/"
                : pathname.startsWith(link.href);
            return (
              <Link
                key={link.href}
                href={link.href}
                onClick={() => setOpen(false)}
                className={`rounded-lg px-3 py-2.5 text-sm font-medium ${
                  active
                    ? "bg-brand-light text-brand-dark"
                    : "text-slate-700 hover:bg-slate-100"
                }`}
              >
                {link.label}
              </Link>
            );
          })}
          {user ? (
            <>
              <Link
                href="/portal"
                onClick={() => setOpen(false)}
                className="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
              >
                {t("portal")}
              </Link>
              <button
                type="button"
                onClick={() => {
                  logout();
                  setOpen(false);
                }}
                className="rounded-lg px-3 py-2.5 text-left text-sm font-medium text-slate-700 hover:bg-slate-100"
              >
                {t("logout")}
              </button>
            </>
          ) : (
            <Link
              href="/login"
              onClick={() => setOpen(false)}
              className="rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100"
            >
              {t("login")}
            </Link>
          )}
          <Link
            href="/request-service"
            onClick={() => setOpen(false)}
            className="mt-2 rounded-full bg-accent px-4 py-2.5 text-center text-sm font-semibold text-slate-900 shadow-sm"
          >
            {t("requestService")}
          </Link>
          <div className="mt-3 border-t border-slate-100 pt-3">
            <LocaleSwitcher />
          </div>
        </nav>
      )}
    </header>
  );
}
