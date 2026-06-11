<?php
include "if_isset.php"; // Certifique-se que a conexão está aqui dentro

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepara a query de exclusão de forma segura contra SQL Injection
    $sql = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // Se deletou com sucesso, volta para a página de visualização
        header("Location: usuario_read.php");
        exit();
    } else {
        echo "Erro ao tentar excluir o usuário.";
    }
} else {
    // Se nenhum ID foi enviado, retorna para a lista
    header("Location: usuario_read.php");
    exit();
}
?>