<?php
$email = $_POST['email'];
$token = $_POST['token'];
$senha = $_POST['senha'];
$repetirSenha = $_POST['repetirSenha'];

require_once "conexao.php";
$conexao = conectar();
$sql = "SELECT * FROM `recuperar-senha` WHERE email='$email' AND 
        token='$token'";
$resultado = executarSQL($conexao, $sql);
$recuperar = mysqli_fetch_assoc($resultado);

if ($recuperar == null) {
    echo "Email ou token incorreto. Tente fazer um novo pedido 
          de recuperação de senha.";
    die();
} else {
    // verificar a validade do pedido (data_criacao)
    // verificar se o link jah foi usado
    date_default_timezone_set('America/Sao_Paulo');
    $agora = new DateTime('now');
    $data_criacao = DateTime::createFromFormat(
        'Y-m-d H:i:s',
        $recuperar['data_criacao']
    );
    $umDia = DateInterval::createFromDateString('1 day');
    $dataExpiracao = date_add($data_criacao, $umDia);

    if ($agora > $dataExpiracao) {
        echo "Essa solicitação de recuperação de senha expirou!
              Faça um novo pedido de recuperação de senha.";
        die();
    }

    if ($recuperar['usado'] == 1) {
        echo "Esse pedido de recuperação de senha já foi utilizado
        anteriormente! Para recuperar a senha faça um novo pedido
        de recuperação de senha.";
        die();
    }

    if ($senha != $repetirSenha) {
        echo "A senha que você digitou é diferente da senha que
              você digitou no repetir senha. Por favor tente 
              novamente!";
        die();
    }

    $sql2 = "UPDATE usuario SET senha='$senha' WHERE 
             email='$email'";
    executarSQL($conexao, $sql2);
    $sql3 = "UPDATE `recuperar-senha` SET usado=1 WHERE 
             email='$email' AND token='$token'";
    executarSQL($conexao, $sql3);

    echo "Nova senha cadastrada com sucesso! Faça o login para 
          acessar o sistema.<br>";
    echo "<a href='index.php'>Acessar sistema</a>";
}
