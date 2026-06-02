<?php
session_start();
if (!isset($_SESSION['id_usuario'])) { //vai confirmar se a pessoa realmete fez o login, e não acessou direto pelo endereço
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aromas da Lari</title>
    <style>
        <?php
        require_once "style.css";//incluindo o arquivo css para estilizar 
        ?>
    </style>
</head>
<body>
    <h1>Aromas da Lari 💐✨</h1>
    <p>Olá, <?php echo $_SESSION['nome_usuario'];?>!!</p>
    <br>
    <br>
    <div class="dashboard">
        <a href="usuario_read.php" class="card-btn">Usuários</a>
        <a href="cliente_read.php" class="card-btn">Clientes</a>
        <a href="produto_read.php" class="card-btn">Produtos</a>
        <a href="pedido_read.php" class="card-btn">Pedidos</a>
    </div>


</body>
</html>