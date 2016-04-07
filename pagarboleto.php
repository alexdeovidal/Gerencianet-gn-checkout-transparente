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
/*INSERI O ID DA COMPRA*/
$params = ['id' => $_SESSION['idcompra']];
/*DADOS DO CLIENTE QUE ESTA COMPRANDO*/
$customer = [
	'name' => 'Alex de Oliveira Vidal',
	'cpf' => '04267484171',
	'phone_number' => '6492022823'
];
/*COLOQUE A DATA DO VENCIMENTO*/
$body = [
  'payment' => [
    'banking_billet' => [
      'expire_at' => '2018-12-12',
      'customer' => $customer
    ]
  ]
];

try {
    $api = new Gerencianet($options);
    $charge = $api->payCharge($params, $body);
	/*COM ESTE ARRAY VOCÊ PODE CRIAR SEU PROPRIO BOLETO UTILIZANDO APENAS O CODIGO DE BARRA DA GERENCIANET OU O PROPRIO BOLETO*/
    echo '<pre>';
    print_r($charge);
	echo '</pre>';
/*CASO OCORRA ALGUM ERRO VAI SER PRINTADO DA TELA O ERRO PARA SER TRATADO*/
} catch (GerencianetException $e) {
    print_r($e->code);
    print_r($e->error);
    print_r($e->errorDescription);
} catch (Exception $e) {
    print_r($e->getMessage());
}