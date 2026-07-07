"use client";

import { createContext, useContext, useEffect, useState, ReactNode } from "react";
import { useLocale } from "next-intl";
import { api, type AuthUser } from "@/lib/api";

const TOKEN_KEY = "cesd_token";
const USER_KEY = "cesd_user";

type PortalUser = AuthUser & { roles?: string[] };

interface AuthContextValue {
  user: PortalUser | null;
  token: string | null;
  isLoading: boolean;
  login: (email: string, password: string) => Promise<void>;
  register: (payload: {
    name: string;
    email: string;
    password: string;
    phone?: string;
    organization?: string;
  }) => Promise<void>;
  logout: () => void;
}

const AuthContext = createContext<AuthContextValue | null>(null);

export function AuthProvider({ children }: { children: ReactNode }) {
  const locale = useLocale();
  const [user, setUser] = useState<PortalUser | null>(null);
  const [token, setToken] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    const storedToken = localStorage.getItem(TOKEN_KEY);
    const storedUser = localStorage.getItem(USER_KEY);
    if (storedToken && storedUser) {
      setToken(storedToken);
      setUser(JSON.parse(storedUser));
    }
    setIsLoading(false);
  }, []);

  function persist(nextToken: string, nextUser: PortalUser) {
    localStorage.setItem(TOKEN_KEY, nextToken);
    localStorage.setItem(USER_KEY, JSON.stringify(nextUser));
    setToken(nextToken);
    setUser(nextUser);
  }

  async function login(email: string, password: string) {
    const res = await api.login(locale, { email, password });
    persist(res.data.token, { ...res.data.user, roles: res.data.roles });
  }

  async function register(payload: {
    name: string;
    email: string;
    password: string;
    phone?: string;
    organization?: string;
  }) {
    const res = await api.register(locale, payload);
    // Self-registration always creates a "customer" role account.
    persist(res.data.token, { ...res.data.user, roles: ["customer"] });
  }

  function logout() {
    if (token) {
      api.logout(locale, token).catch(() => {
        // Best-effort: clear local state regardless of network/API errors.
      });
    }
    localStorage.removeItem(TOKEN_KEY);
    localStorage.removeItem(USER_KEY);
    setToken(null);
    setUser(null);
  }

  return (
    <AuthContext.Provider value={{ user, token, isLoading, login, register, logout }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth(): AuthContextValue {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error("useAuth must be used within AuthProvider");
  return ctx;
}
