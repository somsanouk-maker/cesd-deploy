"use client";

import { useEffect, useState } from "react";
import Image from "next/image";

const SLIDES = [
  "/images/cesd-exterior.jpg",
  "/images/cesd-drone.jpg",
  "/images/facility/exterior-2.jpg",
  "/images/facility/entrance-1.jpg",
  "/images/facility/exhibition-1.jpg",
];

const INTERVAL_MS = 5000;

export function HeroCarousel({ alt }: { alt: string }) {
  const [active, setActive] = useState(0);

  useEffect(() => {
    const id = setInterval(() => {
      setActive((i) => (i + 1) % SLIDES.length);
    }, INTERVAL_MS);
    return () => clearInterval(id);
  }, []);

  return (
    <>
      {SLIDES.map((src, i) => (
        <Image
          key={src}
          src={src}
          alt={i === 0 ? alt : ""}
          fill
          priority={i === 0}
          className={`object-cover transition-opacity duration-1000 ease-in-out ${
            i === active ? "opacity-100" : "opacity-0"
          }`}
        />
      ))}

      <div className="absolute bottom-6 left-1/2 z-10 flex -translate-x-1/2 gap-2">
        {SLIDES.map((src, i) => (
          <button
            key={src}
            type="button"
            aria-label={`Show slide ${i + 1}`}
            onClick={() => setActive(i)}
            className={`h-2 w-2 rounded-full transition-colors ${
              i === active ? "bg-white" : "bg-white/40"
            }`}
          />
        ))}
      </div>
    </>
  );
}
