import { defineRouting } from "next-intl/routing";

export const routing = defineRouting({
  locales: ["lo", "en"],
  defaultLocale: "lo",
  localePrefix: "always",
});

export type AppLocale = (typeof routing.locales)[number];
