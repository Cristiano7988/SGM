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

// Props

export interface Props {
    errors: any,
    session: Session,
}

export interface IndexProps<T> extends Props {
    pagination: Pagination<T>;
}

export interface IndexPacoteProps extends IndexProps<PacoteAndExtraColumns>, PacoteRelations {}
export interface FormPacoteProps extends Props, PacoteRelations {
    pacote: Pacote;
}
export interface ShowPacoteProps {
    pacote: PacoteAndExtraColumns
}

export interface IndexTurmaProps extends IndexProps<TurmaAndExtraColumns>, TurmaRelations {}
export interface FormTurmaProps extends Props, TurmaRelations {
    turma: Turma;
}
export interface ShowTurmaProps {
    turma: TurmaAndExtraColumns
}

// Relations

export interface TurmaRelations {
    nucleos: Nucleo[],
    dias: Dia[],
    tipos_de_aula: TipoDeAula[]
}

export interface NucleoRelations {
    turmas: Turma[],
}

export interface PacoteRelations {
    nucleos: Nucleo[],
}

// Filtros

type FiltroValor = string | number | boolean | undefined;

type FiltrosBase<Chaves extends string> = {
    [K in Chaves]?: FiltroValor;
} & {
    [key: string]: FiltroValor; // <- Isso permite acesso dinâmico via string
};

export type FiltrosTurma = FiltrosBase<'disponivel' | 'nucleoId' | 'diaId' | 'tipoDeAulaId'>;
export type FiltrosPacote = FiltrosBase<'ativo' | 'nucleoId'>;

// Form Elements

export interface FormProps<T> {
    data: T;
    processing: boolean;
    submit: (e: React.FormEvent) => void;
    setData: (key: string, value: any) => void;
    errors: any;
}

export interface FormPacoteContentProps extends FormProps<Pacote> {
    props: PacoteRelations
}

export interface FormTurmaContentProps extends FormProps<Turma> {
    props: TurmaRelations
}



// Models

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
    tipo_de_aula_id: number,
}

export interface TurmaAndExtraColumns extends Turma {
    vagas_preenchidas: number,
    tipo_de_aula: Tipo,
    dia: Dia,
    nucleo: Nucleo,
} 

export interface Pacote {
    id: number;
    nome: string;
    ativo: boolean;
    nucleo_id: number;
    valor: number;
}

export interface PacoteAndExtraColumns extends Pacote {
    valor_formatado: string;
    nucleo: Nucleo;
}
