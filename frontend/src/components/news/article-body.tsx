"use client";

import { useEffect, useRef } from "react";

export function ArticleBody({ html, className }: { html: string; className?: string }) {
  const ref = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const container = ref.current;
    if (!container) return;

    const images = container.querySelectorAll("img");
    const handlers: Array<() => void> = [];

    images.forEach((img) => {
      const onError = () => {
        (img.closest("figure") ?? img).remove();
      };
      img.addEventListener("error", onError);
      handlers.push(() => img.removeEventListener("error", onError));
    });

    return () => handlers.forEach((off) => off());
  }, [html]);

  return (
    <div
      ref={ref}
      className={className}
      dangerouslySetInnerHTML={{ __html: html }}
    />
  );
}
