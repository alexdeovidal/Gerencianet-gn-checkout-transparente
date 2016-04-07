# Gerencianet-gn-checkout-transparente
Método simples e pratico para efetuar o checkout transparente da GerenciaNet
Manual
Crie uma nova aplicação dentro do seu painel do Gerencianet

Abra o arquivo config.json e insira o client_id e client_secret da sua aplicação Gerencianet

Em config.json no campo sandbox marque true para usar a aplicação em desenvolvimento e false para usar na produção

Abra o arquivo index.php na linha 14 adicione os produtos como array

Na linha 44 da index.php ele recebe os dados do produto enviado a Gerencianet, eu armazenei em uma sessão

Ainda no arquivo index.php linha 62 a 99 é utilizado se for cartão de credito, deve passar os dados do cartão nas linhas 91 a 95, na linha 80 insira o link que vai receber o payment_token da aplicação neste caso usei pagarcartao.php ele vai receber via POST: $_POST['payment_token'] eu armazenei em uma sessão para não perder.

Abra o arquivo pagarboleto.php na linha 12 eu coloquei o id da transação que armazenei em uma sessão, nas linhas 15 a 17 informe os dados do cliente, na linha 23 o dia do vencimento do boleto.

Ainda no arquivo pagarboleto na linha 34 você recebe uma array da Gerencianet com codigo de barras para criar um boleto personalizado e também o link de um boleto pronto.

Abra o arquivo fim.php na linha 11 ele recupera a sessão do id da compra e na linha 12 ele recupera o payment_token armazenado tambem em outra sessão.

Ainda no arquivo fim.php nas linhas 15 a 28 informe todos os dados do cliente, na linha 51 vai te retornar os dados da transação

Entre em sua aplicação no Gerencianet
Se estiver usando desenvolvimento na parte de cartão vai ver que a transação foi paga, se for boleto vai ver que a transação foi gerada.

webav.com.br
