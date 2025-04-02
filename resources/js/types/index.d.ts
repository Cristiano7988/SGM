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
export interface SessionType {
    error: string | null,
    success: string | null
}

export interface IndexProps<T> {
    session: Session, 
    pagination: Pagination<T>;
}

export interface IndexPropsTurma {
    session: Session, 
    pagination: Pagination<T>;
    nucleos: Nucleo[],
    dias: Dia[],
    tipos_de_aula: TipoDeAula[]
}

export interface FiltrosTurma {
    disponivel?: boolean
    nucleoId?: string,
    diaId?: string,
    tipoDeAulaId?: string,
    [key: string]: any
}

export interface FiltrosHabilitadosTurma {
    disponivel: boolean
    nucleoId: boolean,
    diaId: boolean,
    tipoDeAulaId: boolean,
    [key: string]: any
}

export interface Props<T> {
    errors: any,
    session: Session,
    [key: string]: T; // This allows for additional model
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
    idade_minima: number;
    unidade_de_tempo_minima: string;
    unidade_de_tempo_maxima: string;
    idade_maxima: number;
    inicio_matricula: string;
    fim_matricula: string;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface Dia {
    id: number,
    nome: string
}

export interface TipoDeAula {
    id: number,
    tipo: string
}

export interface Turma {
    id: number;
    nome: string;
    imagem: string;
    descricao: Array;
    vagas_preenchidas: number,
    vagas_fora_do_site: number,
    vagas_ofertadas: number,
    horario: string,
    disponivel: boolean,
    zoom: string,
    zoom_id: string,
    zoom_senha: string,
    whatsapp: string,
    spotify: string,
    nucleo_id: number,
    dia_id: number,
    dia: Dia,
    tipo_de_aula_id: number,
    tipo_de_aula: Tipo
}
