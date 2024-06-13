<?php

/**
 * Faz uma conexão com o banco de dados MySQL, 
 * na base de dados recuperar-senha.
 * 
 * @return retorna uma conexão com a base de dados, ou em caso 
 * de falha, mata a execução e exibe o erro.
 */
function conectar()
{
    $conexao = mysqli_connect("localhost", "root", "", "recuperar-senha");
    if ($conexao === false) {
        echo "Erro ao conectar à base dados. Nº do erro: " .
            mysqli_connect_errno() . ". " .
            mysqli_connect_error();
        die();
    }
    return $conexao;
}
