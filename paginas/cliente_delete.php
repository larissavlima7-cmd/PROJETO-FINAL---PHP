<?php
include "if_isset.php"; //importa o aquivo if_isset 

//vai usar o id como parametro, ele pega o id que queremos excluir
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    //executa e deleta o cliente do banco de dados
    $sql = "DELETE FROM clientes WHERE id = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // condição da conclusão da execução
    if ($stmt->execute()) {
        // Se deletou com sucesso, volta para a página de visualização
        header("Location: cliente_read.php");
        exit();
    } else {
        //se der errdo, vai exibir a mensagem de erro
        echo "Erro ao tentar excluir o cliente.";
    }
} else {
    // Se nenhum ID foi enviado, retorna para a página de listagem
    header("Location: cliente_read.php");
    exit();
}
?>