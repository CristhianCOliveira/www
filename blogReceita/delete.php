<?php

require_once "config/database.php";
require_once "includes/functions.php";

$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    resposta(false, "ID inválido");
}

// 1. Buscar imagem antes de deletar
$stmt = $conn->prepare("SELECT imagem FROM receitas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$receita = $result->fetch_assoc();
$stmt->close();

if (!$receita) {
    resposta(false, "Receita não encontrada");
}

$imagem = $receita['imagem'] ?? null;

// 2. Inicia transação
$conn->begin_transaction();

try {

    // 3. Deleta ingredientes
    $stmt_ing = $conn->prepare("DELETE FROM ingredientes WHERE receita_id = ?");
    $stmt_ing->bind_param("i", $id);

    if (!$stmt_ing->execute()) {
        throw new Exception("Erro ao deletar ingredientes");
    }

    // 4. Deleta receita
    $stmt_rec = $conn->prepare("DELETE FROM receitas WHERE id = ?");
    $stmt_rec->bind_param("i", $id);

    if (!$stmt_rec->execute()) {
        throw new Exception("Erro ao deletar receita");
    }

    // 5. Confirma no banco
    $conn->commit();

} catch (Exception $e) {

    // Se algo falhar, desfaz tudo
    $conn->rollback();

    resposta(false, $e->getMessage());
}

// 6. Deleta imagem (fora da transação)
if (!empty($imagem)) {
    $caminho = __DIR__ . "/uploads/" . $imagem;

    if (file_exists($caminho)) {
        unlink($caminho);
    }
}

// 7. Redireciona
header("Location: index.php?sucesso=1");
exit;