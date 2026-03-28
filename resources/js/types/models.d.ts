import type { Config } from 'ziggy-js';

export interface User {
    id: number;
    nome: string;
    email: string;
    email_nf: string;
    cpf: string;
    cnpj: string;
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
    pivot: AlunoUser
}

export interface AlunoUser {
    id: number,
    aluno_id: number,
    user_id: number,
    vinculo: string
}

export interface RelacionadasAoUser {
    alunos: Aluno[],
    matriculas: Matricula[]
    // transacoes: Transacao[],
    // emails: Email[],
    // erros: Erro[]
}

export interface Nucleo {
    id: number;
    nome: string;
    imagem: string;
    descricao: string;
    paragrafos_da_descricao: string[];
    idade_minima: number;
    unidade_de_tempo_minima: string;
    unidade_de_tempo_maxima: string;
    idade_maxima: number;
    inicio_matricula: string;
    inicio_matricula_formatada: string;
    fim_matricula: string;
    fim_matricula_formatada: string;
    unidade_de_tempo_minima: string;
    unidade_de_tempo_maxima: string;
}

export interface RelacionadasAoNucleo {
    turmas: Turma[];
}

// Turma

export interface Turma {
    id: number;
    nome: string;
    imagem: string;
    descricao: string;
    paragrafos_da_descricao: string[];
    // vagas_preenchidas: number
    vagas_ofertadas: number,
    disponivel: boolean,
    zoom: string,
    zoom_id: string,
    zoom_senha: string,
    whatsapp: string,
    spotify: string,
    nucleo_id: number,
    nucleo: Nucleo;
}

export interface Aula {
    id: number,
    horario: string,
    dia_id: number,
    turma_id: number
}

export interface RelacionadasATurma {
    nucleo: Nucleo;
    // Para o filtro de Turma
    nucleos: Nucleo[];
}

// Pacote

export interface Pacote {
    id: number | null;
    nome: string;
    ativo: boolean;
    turma_id: number | null;
    valor: number;
    valor_formatado: string;
    vigencia: string
}

export interface RelacionadasAoPacote {
    turma: Turma;
    datas: Data[];
    // Para o filtro de Pacote
    turmas: Turma[];
}

// Data

export interface Data {
    id: number | null;
    dia: string;
    dia_formatado: string;
    pacote_id: number | null;
    pacote?: Pacote;
}

export interface RelacionadasAData {
    // Para o filtro de Data
    pacote: Pacote[];
}

// Aluno
export interface Aluno {
    id: number;
    nome: string;
    data_de_nascimento: string;
    data_de_nascimento_formatada: string;
    idade: string
    pivot: AlunoUser
}

export interface RelacionadasAoAluno {
    users: User[];
    matriculas: Matricula[];
}

export interface Matricula {
    id: number;
    aluno_id: number;
    turma_id: number;
    pacote_id: number;
    situacao_id: number;
}

export interface RelacionadasAMatricula {
    aluno?: Aluno | null;
    turma?: Turma 
    pacote?: Pacote & RelacionadasAoPacote;
    situacao?: Situacao;
    marcacao?: Marcacao;
    // Para o filtro de Matrícula
    alunos?: Aluno[];
    turmas?: Turma[];
    pacotes?: Pacote[];
    situacoes?: Situacao[];
    marcacoes?: Marcacao[];
    users?: User[]
}

export interface Situacao {
    id: number,
    esta: string
}

export interface Marcacao {
    id: number,
    observacao: string,
    cor: string,
    key_code: string
}

export interface FormContentProps<T> {
    initialData: T,
    endpoint: string,
    related: any
};
