import type { Config } from 'ziggy-js';

export interface User {
    id: number;
    nome: string;
    email: string;
    email_nf: string;
    cpf: string;
    cnpj: string;
    vinculo: string;
    whatsapp: string;
    instagram: string;
    cep: string;
    pais: string;
    estado: string;
    cidade: string;
    bairro: string;
    logradouro: string;
    numero: number | undefined;
    complemento: string;
}

export interface RelacionadasAoUser {
    alunos: Aluno[],
    // transacoes: Transacao[],
    // emails: Email[],
    // tipos: Tipo[],
    // erros: Erro[]
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
    // vagas_preenchidas: number
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
}

export interface RelacionadasAoPacote {
    nucleo: Nucleo;
    periodos: Periodo[];
    // Para o filtro de Pacote
    nucleos: Nucleo[];
}

// Periodo

export interface Periodo {
    id: number;
    inicio: string;
    fim: string;
    // inicio_formatado: string;
    // fim_formatado: string;
    // pacote_id: number;
    // pacote: Pacote;
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

// Aluno
export interface Aluno {
    id: number;
    nome: string;
    data_de_nascimento: string;
    data_de_nascimento_formatada: string;
    idade: string
}

export interface RelacionadasAoAluno {
    users: User[];
    matriculas: Matricula[];
}

export interface Matricula {
    id: number;
    aluno_id: number;
    turma_id: number;
    created_at: string;
    updated_at: string;
    turma: Turma;
}

export interface FormContentProps<T> {
    inicialData: T,
    endpoint: string,
    related: any
};
