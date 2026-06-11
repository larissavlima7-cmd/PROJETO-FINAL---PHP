<?php
session_start();
if (!isset($_SESSION['id_usuario'])) { //vai confirmar se a pessoa realmete fez o login, e não acessou direto pelo endereço
    header("Location: index.php");
    exit;
}
require_once "conexao_bd.php";
?>