<?php
session_start();
/*RECUPERA E SALVA OS DADOS DO CARTÃO DO CLIENTE VIA POST EM SESSION*/
$_SESSION['chave'] = $_POST['payment_token'];

