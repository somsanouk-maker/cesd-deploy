import { SVGProps } from "react";

function Base(props: SVGProps<SVGSVGElement>) {
  return (
    <svg
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      strokeWidth={1.5}
      strokeLinecap="round"
      strokeLinejoin="round"
      {...props}
    />
  );
}

export function BeakerIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <path d="M9 3h6M10 3v6.2a2 2 0 0 1-.3 1L5.5 18a2 2 0 0 0 1.7 3h9.6a2 2 0 0 0 1.7-3l-4.2-7.8a2 2 0 0 1-.3-1V3" />
      <path d="M7.5 15h9" />
    </Base>
  );
}

export function MicroscopeIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <path d="M6 21h9" />
      <path d="M10.5 21a5.5 5.5 0 1 1 4-9.3" />
      <path d="M12.5 9 16 5.5" />
      <path d="M14.8 3.8l1.9 1.9" />
      <path d="M9 15.5h4" />
      <path d="M17 21h2a2 2 0 0 0 0-4h-3" />
    </Base>
  );
}

export function AtomIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <circle cx="12" cy="12" r="1.5" />
      <ellipse cx="12" cy="12" rx="9" ry="3.8" />
      <ellipse cx="12" cy="12" rx="9" ry="3.8" transform="rotate(60 12 12)" />
      <ellipse cx="12" cy="12" rx="9" ry="3.8" transform="rotate(120 12 12)" />
    </Base>
  );
}

export function BoltIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <path d="M13 2 4 14h7l-1 8 9-12h-7l1-8Z" />
    </Base>
  );
}

export function CubeIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <path d="m12 3 8 4.5v9L12 21l-8-4.5v-9L12 3Z" />
      <path d="M4.5 7.5 12 12l7.5-4.5" />
      <path d="M12 12v9" />
    </Base>
  );
}

export function DesktopIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <rect x="3" y="4" width="18" height="12" rx="1.5" />
      <path d="M9 20h6M12 16v4" />
    </Base>
  );
}

export function GaugeIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <path d="M4 15a8 8 0 1 1 16 0" />
      <path d="M12 15 15.5 10" />
      <path d="M4 15h1.5M18.5 15H20M6.5 8.5l1 1M17.5 8.5l-1 1" />
    </Base>
  );
}

export function ChipIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <rect x="7" y="7" width="10" height="10" rx="1.5" />
      <rect x="10" y="10" width="4" height="4" />
      <path d="M9 3v2M15 3v2M9 19v2M15 19v2M3 9h2M3 15h2M19 9h2M19 15h2" />
    </Base>
  );
}

export function UsersIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <circle cx="9" cy="8" r="3" />
      <path d="M3.5 20a5.5 5.5 0 0 1 11 0" />
      <circle cx="17" cy="9" r="2.4" />
      <path d="M15.7 13.2a4.5 4.5 0 0 1 5.3 4.4" />
    </Base>
  );
}

export function PhotoIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <rect x="3" y="4" width="18" height="16" rx="1.5" />
      <circle cx="8.5" cy="9.5" r="1.5" />
      <path d="m4 17 5-5 3.5 3.5L17 11l4 6" />
    </Base>
  );
}

export function BriefcaseIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <rect x="3" y="7.5" width="18" height="12" rx="1.5" />
      <path d="M8.5 7.5V6a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v1.5" />
      <path d="M3 12.5h18" />
    </Base>
  );
}

export function ClipboardCheckIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <rect x="5.5" y="4.5" width="13" height="16" rx="1.5" />
      <path d="M9 4.5V3.8a1.3 1.3 0 0 1 1.3-1.3h3.4A1.3 1.3 0 0 1 15 3.8v.7" />
      <path d="m9 13 2 2 4-4.5" />
    </Base>
  );
}

export function ChartBarIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <path d="M4 20V10M10 20V4M16 20v-7M22 20H2" />
    </Base>
  );
}

export function LightBulbIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <path d="M9 18h6" />
      <path d="M10 21h4" />
      <path d="M12 3a6 6 0 0 0-3.5 10.9c.6.4 1 1.1 1 1.9v.2h5v-.2c0-.8.4-1.5 1-1.9A6 6 0 0 0 12 3Z" />
    </Base>
  );
}

export function ChatBubbleIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <path d="M4 5.5h16v11H9.5L5 20v-3.5H4v-11Z" />
    </Base>
  );
}

export function AcademicCapIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <path d="m12 4 10 5-10 5L2 9l10-5Z" />
      <path d="M6 11.5V16c0 1.7 2.7 3 6 3s6-1.3 6-3v-4.5" />
      <path d="M22 9v6" />
    </Base>
  );
}

export function CalendarIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <rect x="3.5" y="5" width="17" height="16" rx="1.5" />
      <path d="M8 3v4M16 3v4M3.5 10h17" />
    </Base>
  );
}

export function WrenchIcon(props: SVGProps<SVGSVGElement>) {
  return (
    <Base {...props}>
      <path d="M14.7 6.3a4 4 0 0 0-5.4 4.9L3 17.5 6.5 21l6.3-6.3a4 4 0 0 0 4.9-5.4l-2.8 2.8-2.5-.5-.5-2.5 2.8-2.8Z" />
    </Base>
  );
}

/** Maps CESD's 11 laboratory codes to a representative icon. */
export function LabIcon({
  code,
  ...props
}: { code: string } & SVGProps<SVGSVGElement>) {
  const Icon =
    {
      CHEM: BeakerIcon,
      EM: MicroscopeIcon,
      XRF: AtomIcon,
      HV: BoltIcon,
      SF: CubeIcon,
      CR: DesktopIcon,
      PM: GaugeIcon,
      EEM: ChipIcon,
      MTG: UsersIcon,
      EXH: PhotoIcon,
      ADMIN: BriefcaseIcon,
    }[code] ?? WrenchIcon;

  return <Icon {...props} />;
}

/** Maps a service's category enum to a representative icon. */
export function ServiceIcon({
  category,
  ...props
}: { category: string } & SVGProps<SVGSVGElement>) {
  const Icon =
    {
      testing: BeakerIcon,
      inspection: ClipboardCheckIcon,
      performance_test: ChartBarIcon,
      joint_rd: LightBulbIcon,
      consulting: ChatBubbleIcon,
      training: AcademicCapIcon,
      facility_booking: CalendarIcon,
    }[category] ?? WrenchIcon;

  return <Icon {...props} />;
}

/** Best-effort icon for an equipment category name, used as a photo placeholder. */
export function EquipmentCategoryIcon({
  categoryName,
  ...props
}: { categoryName?: string | null } & SVGProps<SVGSVGElement>) {
  const map: Record<string, typeof BeakerIcon> = {
    "Imaging & Microscopy": MicroscopeIcon,
    "Spectrometry & Elemental Analysis": AtomIcon,
    "Chemical Analysis Equipment": BeakerIcon,
    "Mechanical & Physical Testing": GaugeIcon,
    "Electrical & Electronic Measurement": ChipIcon,
    "High-Voltage Testing": BoltIcon,
  };
  const Icon = (categoryName && map[categoryName]) || WrenchIcon;

  return <Icon {...props} />;
}
