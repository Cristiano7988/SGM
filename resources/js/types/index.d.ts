import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    url: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    [key: string]: unknown;
}

export interface Pagination<T> {
    current_page: number;
    data: T[];
    first_page_url: string;
    from: string|null;
    last_page: number;
    last_page_url: string;
    links: T[];
    next_page_url: string|null
    path: string;
    per_page: number;
    prev_page_url: string|null;
    to: string|null;
    total: number
}

export interface Props<T> {
    pagination: Pagination<T>;
}

export interface User {
    id: number;
    nome: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface Nucleo {
    id: number;
    nome: string;
    imagem: string;
    descricao: Array;
    idade_minima_id: BigInt;
    idade_maxima_id: BigInt;
    inicio_rematricula: string;
    fim_rematricula: string;
    created_at: string;
    updated_at: string;
    idade_maxima: IdadeMinima;
    idade_minima: IdadeMaxima;
    [key: string]: unknown; // This allows for additional properties...
}

export interface IdadeMinima {
    id: number;
    idade: number;
    medida_de_tempo_id: number;
    medida_de_tempo: MedidaDeTempo
}

export interface IdadeMaxima {
    id: number;
    idade: number;
    medida_de_tempo_id: number;
    medida_de_tempo: MedidaDeTempo
}

export interface MedidaDeTempo {
    id: number;
    tipo: string;
}