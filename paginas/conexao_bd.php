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
}catch(PDOException $e){
    echo "Erro: " . $e->getMessage();
}
?>