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

$paymentToken = $_SESSION['chave'];
 
$customer = [
  'name' => 'Gorbadoc Oldbuck',
  'email' => 'gordobac@xmd.com.br',
  'birth' => '1984-08-03',
  'cpf' => '04267484171' ,
  'phone_number' => '5144916523'
];
 
$billingAddress = [
  'street' => 'Street 3',
  'number' => 10,
  'neighborhood' => 'Bauxita',
  'zipcode' => '35400000',
  'city' => 'Ouro Preto',
  'state' => 'MG',
];
 
$creditCard = [
  'installments' => 1,
  'billing_address' => $billingAddress,
  'payment_token' => $paymentToken,
  'customer' => $customer
];

$payment = [
  'credit_card' => $creditCard
];
 
$body = [
  'payment' => $payment
];
 
 
try {
    $api = new Gerencianet($options);
    $charge = $api->payCharge($params, $body);
 
    print_r($charge);
} catch (GerencianetException $e) {
    print_r($e->code);
    print_r($e->error);
    print_r($e->errorDescription);
} catch (Exception $e) {
    print_r($e->getMessage());
}