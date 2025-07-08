import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';
import {
    Nucleo,
    RelacionadasAoNucleo,
    Pacote,
    RelacionadasAoPacote,
    Turma,
    RelacionadasATurma,
    Periodo,
    RelacionadasAoPeriodo,
    Aluno,
    RelacionadasAoAluno,
 } from './models';

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

// Nucleo

export interface IndexPropsNucleo extends IndexProps<Nucleo>, RelacionadasAoNucleo {};
export interface ShowPropsNucleo { nucleo: Nucleo & RelacionadasAoNucleo; };
export interface EditPropsNucleo extends Props, FormProps, RelacionadasAoNucleo { nucleo: Nucleo; };
export interface CreatePropsNucleo extends EditPropsNucleo {};

// Pacote

export interface IndexPropsPacote extends IndexProps<Pacote>, RelacionadasAoPacote {};
export interface ShowPropsPacote { pacote: Pacote & RelacionadasAoPacote; };
export interface EditPropsPacote extends Props, FormProps, RelacionadasAoPacote { pacote: Pacote; };
export interface CreatePropsPacote extends EditPropsPacote {};

// Turma

export interface IndexPropsTurma extends IndexProps<Turma>, RelacionadasATurma {};
export interface ShowPropsTurma { turma: Turma & RelacionadasATurma };
export interface EditPropsTurma extends Props, FormProps, RelacionadasATurma { turma: Turma; };
export interface CreatePropsTurma extends EditPropsTurma {};

// Periodos

export interface IndexPropsPeriodo  extends IndexProps<Periodo>, RelacionadasAoPeriodo {};
export interface ShowPropsPeriodo { periodo: Periodo & RelacionadasAoNucleo; };
export interface EditPropsPeriodo extends Props, FormProps, RelacionadasAoPeriodo { periodo: Periodo; };
export interface CreatePropsPeriodo extends EditPropsPeriodo {};

// Periodos

export interface IndexPropsAluno  extends IndexProps<Aluno>, RelacionadasAoAluno {};

// Filtros

export interface FiltrosType {
    tipo: 'boolean' | 'select';
    label: string;
    nome: string;
    valor?: string | number | boolean;
    opcoes?: [];
    ativo: boolean;
}

// Form Elements
export interface FormProps<T> {
    data: T;
    processing: boolean;
    submit: (e: React.FormEvent) => void;
    setData: (key: string, value: any) => void;
    errors: any;
    props: any;
}