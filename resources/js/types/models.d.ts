import type { Config } from 'ziggy-js';

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
    unidade_de_tempo_minima: string;
    unidade_de_tempo_maxima: string;
}

export interface RelacionadasAoNucleo {
    turmas: Turma[];
    pacotes: Pacote[];
}

// Turma

export interface Turma {
    id: number;
    nome: string;
    imagem: string;
    descricao: Array;
    vagas_fora_do_site: number,
    vagas_preenchidas: number
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

export interface RelacionadasATurma {
    nucleo: Nucleo;
    dia: Dia;
    tipo_de_aula: TipoDeAula;
    // Para o filtro de Turma
    nucleos: Nucleo[];
    dias: Dia[];
    tipos_de_aula: TipoDeAula[];
}

// Pacote

export interface Pacote {
    id: number;
    nome: string;
    ativo: boolean;
    nucleo_id: number;
    valor: number;
    valor_formatado: string;
    nucleo: Nucleo;
}

export interface RelacionadasAoPacote {
    periodos: Periodo[];
}

// Periodo

export interface Periodo {
    id: number;
    inicio: string;
    fim: string;
    inicio_formatado: string;
    fim_formatado: string;
    pacote_id: number;
    pacote: Pacote;
}

export interface RelacionadasAoPeriodo {
    // Para o filtro de Periodo
    pacotes: Pacote[];
}

// Dia

export interface Dia {
    id: number,
    nome: string
}

// Tipo de Aula

export interface TipoDeAula {
    id: number,
    tipo: string
}