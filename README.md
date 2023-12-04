## Sistema de Gerenciamento de Matrículas [API]

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
  