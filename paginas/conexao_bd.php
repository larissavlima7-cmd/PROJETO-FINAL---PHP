<?php

$host = "localhost";
$dbname = "perfumaria";
$user = "postgres";
$pass = "postgres";

try{
    $conexao = new PDO(
        "pgsql:host=$host;
        dbname=$dbname",
        $user,
        $pass
    );
    echo "Conexão com o Postgres realizada!<br>";
}catch(PDOException $e){
    echo "Erro: " . $e->getMessage();
}
?>