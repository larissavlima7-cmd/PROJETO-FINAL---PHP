<?php
session_start(); //isso vai permitir a navegação em outras páginas

 // para não dar erro antes de colocar as informações
if(isset($_POST["nome"], $_POST["senha"])){
    $nome = $_POST["nome"];
    $senha = $_POST["senha"];
    
    //validando para confirmar se existe esse nome no bd
    require_once "conexao_bd.php";
    $sql="SELECT * FROM usuarios WHERE nome = :nome";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(':nome', $nome);
    $stmt-> execute();//resultado

    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);//traz o resultado da busca para o php
    // Vai confirmar se o que digitamos tem no banco de dados
    if($usuario && $usuario ['senha'] == $senha){
        //se tiver ele vai "guardar" e direcionar pra página principal
        $_SESSION['id_usuario']=$usuario['id'];
        $_SESSION['nome_usuario']=$usuario['nome'];

        header("Location: index.php");                
    }else{
        //se não ele vai mostrar a mensagem de erro:
        $erro = "Usuário ou senha inválidos!";
        
    }
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
    <h1>AROMAS DA LARI 💐✨</h1>
    <h3>LOGIN</h3>
    <!-- formulario para digitar as informações do usuario -->
    <form method = "POST">
        <label>Nome: </label>
        <input type="text" name="nome" id="nome" placeholder="Digite seu nome"><br>
        <label>Senha: </label>
        <input type="password" name="senha" id="senha">
        <button type="submit" value="Enviar" class="btn">Entrar</button> 
    </form>

    <?php
    if (isset($erro)) {
        echo "<div id='resp'>$erro</div>";
    }
    ?>
    
</body>
</html>