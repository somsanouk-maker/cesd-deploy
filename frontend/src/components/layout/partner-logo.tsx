"use client";

import { useEffect, useRef, useState } from "react";

/**
 * Renders the National University of Laos logo if it has been placed at
 * public/images/nuol-logo.png. Until that file exists, this silently
 * renders nothing instead of a broken-image icon — drop the file in and it
 * appears automatically on the next load, no code changes needed.
 *
 * The src is assigned imperatively after mount (rather than via the JSX
 * `src` prop) so the error listener is attached before the browser attempts
 * the request — with SSR, an eagerly-set `src` can fail and fire `error`
 * before hydration finishes attaching a React `onError` handler.
 */
export function NuolLogo({ className = "h-9 w-9" }: { className?: string }) {
  const [visible, setVisible] = useState(true);
  const imgRef = useRef<HTMLImageElement>(null);

  useEffect(() => {
    const img = imgRef.current;
    if (!img) return;

    const handleError = () => setVisible(false);
    img.addEventListener("error", handleError);
    img.src = "/images/nuol-logo.png";

    return () => img.removeEventListener("error", handleError);
  }, []);

  if (!visible) return null;

  return (
    // eslint-disable-next-line @next/next/no-img-element
    <img ref={imgRef} alt="National University of Laos" className={`${className} object-contain`} />
  );
}
