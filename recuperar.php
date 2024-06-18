<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once "conexao.php";
$conexao = conectar();

$email = $_POST['email'];
$sql = "SELECT * FROM usuario WHERE email='$email'";
$resultado = executarSQL($conexao, $sql);

$usuario = mysqli_fetch_assoc($resultado);
if ($usuario == null) {
    echo "Email não cadastrado! Faça o cadastro e 
          em seguida realize o login.";
    die();
}
//gerar um token unico
$token = bin2hex(random_bytes(50));

require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
require_once 'PHPMailer/src/Exception.php';

$mail = new PHPMailer(true);
try {
    // configurações
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->setLanguage('br');
    //$mail->SMTPDebug = SMTP::DEBUG_OFF;  //tira as mensagens de erro
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; //imprime as mensagens de erro
    $mail->isSMTP();                       //envia o email usando SMTP
    $mail->Host = 'smtp.gmail.com';        //Set the SMTP server to send through
    $mail->SMTPAuth = true;                //Enable SMTP authentication
    $mail->Username = 'programacaoiii2021@gmail.com';   //SMTP username
    $mail->Password = 'bpxijhvtgkpxrfmm';               //SMTP password
    //Enable implicit TLS encryption
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    /* TCP port to connect to; use 587 if you have set 
    `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS` */
    $mail->Port = 587;
} catch (Exception $e) {
    echo "Não foi possível enviar o email. 
          Mailer Error: {$mail->ErrorInfo}";
}
