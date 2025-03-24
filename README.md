## Sistema de Gerenciamento de Matrículas [API]

# Inicializando o projeto - 1ª vez

1) Clonar repositório
    git clone https://github.com/Cristiano7988/SGM.git
2) Instalar dependências
    * composer install
    * npm install
3) Renomer arquivo .env.example para .env
4) Criar a chave encriptada do laravel
    * php artisan key:generate
5) configurar banco de dados:
    * Criar pasta database.sqlite
    * Alterar a variável de ambiente DB_CONNECTION para sqlite
    * Comentar a variável DB_DATABASE
6) Rodar migrations:
    * php artisan migrate
7) Definir as variáveis de ambientes customizadas
    * DEV_NAME, DEV_EMAIL, DEV_PASSWORD
    * CLIENT_NAME, CLIENT_EMAIL, CLIENT_PASSWORD
    * ACCOUNTANT_NAME, ACCOUNTANT_EMAIL, ACCOUNTANT_PASSWORD
8) Configure o mailtrap para ajudar na depuração
    * MAIL_MAILER=smtp
    * MAIL_HOST=smtp.mailtrap.io
    * MAIL_PORT=2525
    * MAIL_USERNAME= <!-- Verificar no Mailtrap em Inboxes na aba Access Rights --> 
    * MAIL_PASSWORD= <!-- Verificar no Mailtrap em Inboxes na aba Access Rights --> 
    * MAIL_ENCRYPTION=tls
9) Rodar php artisan storage:link


# Usuários:

[index]:  
  
# Tipos de busca:  
[Padrão]  
Ex.: alunos=1,2&matriculas=\*&situacoes=2&transacoes=\*&formas_de_pagamento=3
Busca usuários que possuem alunos 1 e 2 cuja a situação da matrícula seja cursando e cuja transação tenha sido feita com paypal  
  
Usuários -> alunos -> matrículas -> transações -> forma de pagamento  
..........................|  
.......................situações  
  
--------------------------------------------------------------------------------------  
  
Ex.: alunos=\*&matriculas=\*turmas=\*&nucleos=2  
Busca usuários que possua qualquer aluno matriculado na turma de um núcleo em específico.  
  
Usuários -> alunos -> matrículas -> turmas -> núcleos  
  
[Transações feitas pelo usuário]  
Ex.: transacoes_feitas_pelo_usuario=*&transacoes=\*&cupons=\*&matriculas=\*&turmas=1  
Busca usuários que fizeram transações, utilizando cupom de desconto, referente às matrículas de uma turma em específico.  
  
Usuários -> transações -> matrículas -> turmas  
................|  
..............cupons  
  
--------------------------------------------------------------------------------------  
  
Ex.: transacoes_feitas_pelo_usuario=true&transacoes=\*&matriculas=\*&pacotes=2,3  
Busca usuários que fizeram transações referentes à qualquer matrícula que tenha os pacotes específicados.  
Obs.: Não está sendo contemplada a relação núcleos -> pacotes.  
  
Usuários -> transações -> matrículas -> pacotes
  