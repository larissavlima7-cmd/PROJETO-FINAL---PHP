<?php
include "if_isset.php"; 

if (isset($_GET['id'])) {
    // Força o ID a ser um número inteiro para evitar problemas 
    $id = (int)$_GET['id']; 

    // Inicia a transação para garantir que a devolução ao estoque e a exclusão aconteçam juntas
    $conexao->beginTransaction();

    try {
        //Busca todos os produtos e quantidades que pertencem a este ID de pedido antes de deletá-lo
        $sql_busca = "SELECT idprodutos, quantidade FROM pedidos WHERE id = :id";
        $stmt_busca = $conexao->prepare($sql_busca);
        $stmt_busca->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_busca->execute();
        $itens_pedido = $stmt_busca->fetchAll(PDO::FETCH_ASSOC); //

        // Se o pedido existir no banco de dados, devolve os itens ao estoque
        if (!empty($itens_pedido)) {
            // devolve a quantidade correta ao estoque de cada produto
            $sql_devolve = "UPDATE produtos SET quant_estoque = quant_estoque + ? WHERE id = ?";
            $stmt_devolve = $conexao->prepare($sql_devolve); //

            foreach ($itens_pedido as $item) {
                $stmt_devolve->execute([$item['quantidade'], $item['idprodutos']]); //
            }
        }

        //  deleta TODOS os registros (linhas) vinculados a este ID de pedido
        $sql_delete = "DELETE FROM pedidos WHERE id = :id";
        $stmt_delete = $conexao->prepare($sql_delete); //
        $stmt_delete->bindParam(':id', $id, PDO::PARAM_INT); //
        
        if ($stmt_delete->execute()) {
            // Se tudo correu perfeitamente, confirma as alterações no banco de dados
            $conexao->commit();
            
            $_SESSION['sucesso'] = "Pedido excluído e itens devolvidos ao estoque!";
            header("Location: pedido_read.php"); //
            exit();
        } else {
            // Se a exclusão falhar por algum motivo interno do banco
            throw new Exception("Falha na execução do comando DELETE.");
        }

    } catch (Exception $e) {
        // Se der qualquer erro no processo, desfaz tudo para não corromper o estoque nem o pedido
        $conexao->rollBack(); //
        echo "Erro ao tentar cancelar o pedido e devolver itens ao estoque: " . $e->getMessage(); //
    }
} else {
    // Se nenhum ID foi enviado na URL, apenas retorna para a lista
    header("Location: pedido_read.php"); //
    exit();
}
?>