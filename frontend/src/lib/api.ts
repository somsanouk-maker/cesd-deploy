const API_URL = process.env.NEXT_PUBLIC_API_URL ?? "http://localhost:8000";

export class ApiError extends Error {
  status: number;
  constructor(message: string, status: number) {
    super(message);
    this.status = status;
  }
}

async function apiFetch<T>(
  path: string,
  locale: string,
  init?: RequestInit
): Promise<T> {
  const url = new URL(`/api/v1${path}`, API_URL);
  if (!url.searchParams.has("locale")) {
    url.searchParams.set("locale", locale);
  }

  const res = await fetch(url.toString(), {
    ...init,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
      ...init?.headers,
    },
    // Public content should stay reasonably fresh without hammering the API.
    next: { revalidate: 60 },
  });

  if (!res.ok) {
    const body = await res.json().catch(() => null);
    throw new ApiError(body?.message ?? res.statusText, res.status);
  }

  return res.json();
}

export interface Paginated<T> {
  data: T[];
  meta?: {
    current_page: number;
    last_page: number;
    total: number;
  };
}

export interface Laboratory {
  id: number;
  code: string;
  name: string;
  description: string | null;
  location_no: number | null;
  building: string | null;
  floor: string | null;
  room_name: string | null;
  photo_url: string | null;
  responsible_staff: string | null;
  equipment_count: number;
}

export interface EquipmentCategory {
  id: number;
  name: string;
}

export interface Equipment {
  id: number;
  code: string;
  name: string;
  brand: string | null;
  model: string | null;
  specification: string | null;
  capability: string | null;
  availability_status: "available" | "in_use" | "maintenance" | "retired";
  photo_url: string | null;
  manual_url: string | null;
  laboratory: { id: number; code: string; name: string } | null;
  category: { id: number; name: string } | null;
  accessories?: { id: number; name: string; brand: string | null; model: string | null; quantity: number }[];
}

export interface ServiceItem {
  id: number;
  slug: string;
  name: string;
  category: string;
  description: string | null;
  icon: string | null;
}

export interface TrainingCourse {
  id: number;
  title: string;
  description: string | null;
  start_date: string | null;
  end_date: string | null;
  capacity: number | null;
  fee: number | null;
  mode: string;
}

export interface NewsItem {
  id: number;
  slug: string;
  title: string;
  excerpt: string | null;
  body: string | null;
  cover_image_url: string | null;
  published_at: string | null;
}

export interface SiteSettings {
  contact_email?: string;
  contact_phone?: string;
  address?: string;
  facebook_url?: string;
}

export interface AboutContent {
  title: string;
  background: string | null;
  vision: string | null;
  mission: string | null;
  objectives: string[];
  organization: {
    director: string | null;
    deputyDirector: string | null;
    admin: string | null;
    technical: string | null;
    innovation: string | null;
  };
}

export const api = {
  laboratories: (locale: string) =>
    apiFetch<Paginated<Laboratory>>("/laboratories", locale),
  laboratory: (locale: string, code: string) =>
    apiFetch<{ data: Laboratory }>(`/laboratories/${code}`, locale),

  equipmentCategories: (locale: string) =>
    apiFetch<{ data: EquipmentCategory[] }>("/equipment-categories", locale),
  equipment: (
    locale: string,
    params: {
      q?: string;
      laboratory_id?: string;
      category_id?: string;
      availability_status?: string;
      page?: number;
    } = {}
  ) => {
    const query = new URLSearchParams();
    Object.entries(params).forEach(([key, value]) => {
      if (value !== undefined && value !== "") query.set(key, String(value));
    });
    const qs = query.toString();
    return apiFetch<Paginated<Equipment>>(
      `/equipment${qs ? `?${qs}` : ""}`,
      locale
    );
  },
  equipmentItem: (locale: string, code: string) =>
    apiFetch<{ data: Equipment }>(`/equipment/${code}`, locale),

  services: (locale: string) =>
    apiFetch<{ data: ServiceItem[] }>("/services", locale),
  service: (locale: string, slug: string) =>
    apiFetch<{ data: ServiceItem }>(`/services/${slug}`, locale),

  trainingCourses: (locale: string) =>
    apiFetch<{ data: TrainingCourse[] }>("/training-courses", locale),
  trainingCourse: (locale: string, id: string | number) =>
    apiFetch<{ data: TrainingCourse }>(`/training-courses/${id}`, locale),

  news: (locale: string) => apiFetch<Paginated<NewsItem>>("/news", locale),
  newsItem: (locale: string, slug: string) =>
    apiFetch<{ data: NewsItem }>(`/news/${slug}`, locale),

  settings: (locale: string) =>
    apiFetch<{ data: SiteSettings }>("/settings", locale),

  about: (locale: string) =>
    apiFetch<{ data: AboutContent }>("/about", locale),

  submitContact: (
    locale: string,
    payload: {
      name: string;
      email: string;
      phone?: string;
      subject: string;
      message: string;
    }
  ) =>
    apiFetch<{ data: { id: number } }>("/contact", locale, {
      method: "POST",
      body: JSON.stringify(payload),
      next: undefined,
      cache: "no-store",
    }),

  submitServiceRequest: (
    locale: string,
    payload: {
      service_id: number;
      laboratory_id?: number | null;
      title: string;
      description: string;
      sample_information?: string;
      required_date?: string;
      contact_name: string;
      contact_email: string;
      contact_phone?: string;
      organization?: string;
    }
  ) =>
    apiFetch<{ data: { request_no: string } }>("/service-requests", locale, {
      method: "POST",
      body: JSON.stringify(payload),
      next: undefined,
      cache: "no-store",
    }),
};
