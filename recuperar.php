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
include 'config.php';


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
    $mail->Username = $config['email'];    //SMTP username
    $mail->Password = $config['senha_email']; //SMTP password
    //Enable implicit TLS encryption
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    /* TCP port to connect to; use 587 if you have set 
    `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS` */
    $mail->Port = 587;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    //Recipients
    $mail->setFrom($config['email'], 'Aula de Tópicos');
    $mail->addAddress($usuario['email'], $usuario['nome']);     //Add a recipient
    $mail->addReplyTo($config['email'], 'Aula de Tópicos');

    //Content
    $mail->isHTML(true);        //Set email format to HTML
    $mail->Subject = 'Recuperação de Senha do Sistema';
    $mail->Body = 'Olá!<br>
        Você solicitou a recuperação da sua conta no nosso sistema.
        Para isso, clique no link abaixo para realizar a troca de senha:<br>
<a href="' . $_SERVER['SERVER_NAME'] . '/nova-senha.php?email=' . $usuario['email'] .
        '&token=' . $token . '">Clique aqui para recuperar o acesso à sua conta!</a><br>
        <br>
        Atenciosamente<br>
        Equipe do sistema...';

    $mail->send();
    echo 'Email enviado com sucesso!<br>Confira o seu email.';
} catch (Exception $e) {
    echo "Não foi possível enviar o email. 
          Mailer Error: {$mail->ErrorInfo}";
}
