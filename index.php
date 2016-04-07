<?php
session_start();
/*REQUITE TODAS AS CLASSES NECESSARIAS*/
require './vendor/autoload.php';
/*ABRE A CLASSE GERENCIANET*/
use Gerencianet\Exception\GerencianetException;
use Gerencianet\Gerencianet;
/*ADICIONA O JSON QUE CONTEM client_id & client_secret */
$file = file_get_contents('config.json');
$options = json_decode($file, true);

/*ADICIONA O ITEM OU ITENS A SER VENDIDOS*/
$items = [
  [
    'name' => 'Arroz Cristal',
    'amount' => 1,
    'value' => 1300
  ],
  [
    'name' => 'Contra File',
    'amount' => 2,
    'value' => 4000
  ]
];

$body = [
  'items' => $items
];
/*ENVIA O ITEM OU ITENS PARA GERENCIANET*/
/*RECUPERA um ARRAY COM TODOS OS DADOS DO PRODUTO SENDO COMPRO*/
/*Array ( 
[code] => 200 
[data] => Array ( 
[charge_id] => 42594 
[status] => new 
[total] => 9300 
[custom_id] => [created_at] => 2016-04-04 17:07:12 ) )
PARA CONTINUAR O PROCESSO VOCÊ PRECISA DO [charge_id] => 42594
*/
try {
    $api = new Gerencianet($options);
    $charge = $api->createCharge([], $body);
	/*VAMOS ENTÃO ARMAZENAR O PRODUTO NA SESSÃO*/
	$_SESSION['idcompra'] = $charge["data"]["charge_id"];
	
   /*CASO OCORRA ALGUM ERRO VAI SER PRINTADO DA TELA O ERRO PARA SER TRATADO*/
} catch (GerencianetException $e) {
    print_r($e->code);
    print_r($e->error);
    print_r($e->errorDescription);
} catch (Exception $e) {
    print_r($e->getMessage());
}
if(isset($_POST['boleto'])):
header('Location: pagarboleto.php');
endif;
if(isset($_POST['cartao'])):
header('Location: fim.php');
endif;
?>

<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type='text/javascript'>var s=document.createElement('script');s.type='text/javascript';var v=parseInt(Math.random()*1000000);s.src='https://sandbox.gerencianet.com.br/v1/cdn/f7225d6cae3ad2b9b4d0ba1d3d92fe54/'+v;s.async=false;s.id='f7225d6cae3ad2b9b4d0ba1d3d92fe54';if(!document.getElementById('f7225d6cae3ad2b9b4d0ba1d3d92fe54')){document.getElementsByTagName('head')[0].appendChild(s);};$gn={validForm:true,processed:false,done:{},ready:function(fn){$gn.done=fn;}};</script>
<script type='text/javascript'>
$gn.ready(function(checkout) {
var callback = function(error, response) {
  if(error) {
    console.error(error);
  } else {
    var post = Object();
    post.payment_token = response.data.payment_token;
    // --- Seu token esta armazenado, em response.data.payment_token
    //alert(response.data.payment_token);
    // --- 
    $.ajax({
        type: "POST",
        data: post,
		// --- Importante aqui é o endereço que vai receber o payment_token
        url: "pagarcartao.php"
		// ---
      }).done(function(data) {
        console.log(data);
      });
  }
};

$("#pay[payment='card']").click(function(){
  jQuery('pre').remove();
  checkout.getPaymentToken({
    brand: 'visa',
    number: '4532425338091497',
    cvv: '123',
    expiration_month: '05',
    expiration_year: '2018'
  }, callback);
});
});
</script>
<meta charset="utf-8">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
<div style="width: 80%; margin: 20px auto; text-align:center;">
<form method="post" action="">
<input type="submit" name="boleto" value="Pagar com Boleto" class="btn btn-success">
<input type="submit" name="cartao" id="pay"  payment="card" value="Pagar com Cartão" class="btn btn-warning">
</form>

<h1>Manual</h1>
<p>Crie uma nova aplicação dentro do seu painel do Gerencianet</p>
<hr>
<p>Abra o arquivo config.json e insira o client_id e client_secret da sua aplicação Gerencianet</p>
<hr>
<p>Em config.json no campo sandbox marque true para usar a aplicação em desenvolvimento e false para usar na produção</p>
<hr>
<p>Abra o arquivo index.php na linha 14 adicione os produtos como array</p>
<hr>
<p>Na linha 44 da index.php ele recebe os dados do produto enviado a Gerencianet, eu armazenei em uma sessão</p>
<hr>
<p>Ainda no arquivo index.php linha 62 a 99 é utilizado se for cartão de credito, deve passar os dados do cartão nas linhas 91 a 95, na linha 80 insira o link que vai receber o payment_token da aplicação neste caso usei pagarcartao.php ele vai receber via POST: $_POST['payment_token'] eu armazenei em uma sessão para não perder.</p>
<hr>
<p>Abra o arquivo pagarboleto.php na linha 12 eu coloquei o id da transação que armazenei em uma sessão, nas linhas 15 a 17 informe os dados do cliente, na linha 23 o dia do vencimento do boleto.</p>
<hr>
<p>Ainda no arquivo pagarboleto na linha 34 você recebe uma array da Gerencianet com codigo de barras para criar um boleto personalizado e também o link de um boleto pronto.</p>
<hr>
<p>Abra o arquivo fim.php na linha 11 ele recupera a sessão do id da compra e na linha 12 ele recupera o payment_token armazenado tambem em outra sessão.</p>
<hr>
<p>Ainda no arquivo fim.php nas linhas 15 a 28 informe todos os dados do cliente, na linha 51 vai te retornar os dados da transação</p>
<hr>
<h2>Entre em sua aplicação no Gerencianet</h2>
<p>Se estiver usando desenvolvimento na parte de cartão vai ver que a transação foi paga, se for boleto vai ver que a transação foi gerada.</p>
<hr>
<hr>
<p>webav.com.br</p>
<div>

