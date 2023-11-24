## Sistema de Gerenciamento de Matrículas [API]

# Usuários:

[index]:  
  
# Tipos de busca:  
[Padrão]  
Ex.: alunos=1,2&matriculas=\*&situacoes=2&transacoes=\*&forma_de_pagamento=3
Busca usuários que possuem alunos 1 e 2 cuja a situação da matrícula seja cursando e cuja transação tenha sido feita com paypal  
  
Usuários -> alunos -> matrículas -> transações -> forma de pagamento  
                            |  
                        situações  
  
--------------------------------------------------------------------------------------  
  
Ex.: alunos=\*&matriculas=\*turmas=\*&nucleos=2  
Busca usuários que possua qualquer aluno matriculado na turma de um núcleo em específico.  
  
Usuários -> alunos -> matrículas -> turmas -> núcleos  
  
[Que_Fizeram_Transações]  
Ex.: que_fizeram_transacoes=true&transacoes=\*&cupons=\*&matriculas=\*&turmas=1  
Busca usuários que fizeram transações, utilizando cupom de desconto, referente às matrículas de uma turma em específico.  
  
Usuários -> transações -> matrículas -> turmas  
                |  
              cupons  
  
--------------------------------------------------------------------------------------  
  
Ex.: que_fizeram_transacoes=true&transacoes=\*&matriculas=\*&nucleo_do_pacote=true&pacotes=\*&nucleos=1,3  
Busca usuários que fizeram transações referentes à qualquer matrícula cuja o pacote pertença à alguns núcleos.  
Obs.: Não terá um caso onde a matrícula (do aluno ou da transação) tenha um pacote cujo núcleo relacionado seja diferente do núcleo relacionado à turma associada na matrícula, pois o pacote escolhido na matrícula pertence ao mesmo núcleo da turma na qual o aluno se matriculou.  
  
Usuários -> transações -> matrículas -> pacotes -> núcleos  
  