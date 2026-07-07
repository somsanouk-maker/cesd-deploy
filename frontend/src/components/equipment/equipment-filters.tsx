"use client";

import { useEffect, useRef, useState } from "react";
import { useTranslations } from "next-intl";
import { usePathname, useRouter } from "@/i18n/navigation";
import type { EquipmentCategory, Laboratory } from "@/lib/api";

export function EquipmentFilters({
  laboratories,
  categories,
  initial,
}: {
  laboratories: Laboratory[];
  categories: EquipmentCategory[];
  initial: {
    q?: string;
    laboratory_id?: string;
    category_id?: string;
    availability_status?: string;
  };
}) {
  const t = useTranslations("Equipment");
  const router = useRouter();
  const pathname = usePathname();
  const [q, setQ] = useState(initial.q ?? "");
  const debounceRef = useRef<ReturnType<typeof setTimeout> | null>(null);

  function updateParam(key: string, value: string) {
    const params = new URLSearchParams(window.location.search);
    if (value) {
      params.set(key, value);
    } else {
      params.delete(key);
    }
    const qs = params.toString();
    router.push(`${pathname}${qs ? `?${qs}` : ""}`);
  }

  useEffect(() => {
    if (debounceRef.current) clearTimeout(debounceRef.current);
    debounceRef.current = setTimeout(() => {
      if (q !== (initial.q ?? "")) updateParam("q", q);
    }, 400);
    return () => {
      if (debounceRef.current) clearTimeout(debounceRef.current);
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [q]);

  return (
    <div className="grid gap-3 sm:grid-cols-4">
      <input
        type="search"
        value={q}
        onChange={(e) => setQ(e.target.value)}
        placeholder={t("searchPlaceholder")}
        className="rounded-lg border border-slate-300 px-3 py-2 text-sm sm:col-span-2"
      />
      <select
        defaultValue={initial.laboratory_id ?? ""}
        onChange={(e) => updateParam("laboratory_id", e.target.value)}
        className="rounded-lg border border-slate-300 px-3 py-2 text-sm"
      >
        <option value="">{t("allLaboratories")}</option>
        {laboratories.map((lab) => (
          <option key={lab.id} value={lab.id}>
            {lab.name}
          </option>
        ))}
      </select>
      <select
        defaultValue={initial.category_id ?? ""}
        onChange={(e) => updateParam("category_id", e.target.value)}
        className="rounded-lg border border-slate-300 px-3 py-2 text-sm"
      >
        <option value="">{t("allCategories")}</option>
        {categories.map((cat) => (
          <option key={cat.id} value={cat.id}>
            {cat.name}
          </option>
        ))}
      </select>
      <select
        defaultValue={initial.availability_status ?? ""}
        onChange={(e) => updateParam("availability_status", e.target.value)}
        className="rounded-lg border border-slate-300 px-3 py-2 text-sm"
      >
        <option value="">{t("allAvailability")}</option>
        <option value="available">{t("available")}</option>
        <option value="in_use">{t("inUse")}</option>
        <option value="maintenance">{t("maintenance")}</option>
        <option value="retired">{t("retired")}</option>
      </select>
    </div>
  );
}
