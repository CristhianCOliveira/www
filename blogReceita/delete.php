<?php

require_once "config/database.php";

if (!isset($_GET['id'])) {
    die("ID não encontrado.");
}
$receita_id = intval($_GET['id']);

//Aqui eu deleto os ingrediente que estão relacionados com a receita
$stmt_ing = $conn->prepare("DELETE FROM ingredientes WHERE receita_id= ?");
$stmt_ing->bind_param("i", $receita_id);
if (!$stmt_ing->execute()) {
    die("Erro ao deletar ingredientes: " . $conn->error);
}

//Aqui eu deleto a própria receita
$stmt_del= $conn->prepare("DELETE FROM receitas WHERE id = ?");
$stmt_del-> bind_param("i", $receita_id);

if ($stmt_del->execute()) {
    header("Location: index.php");
    exit();
} else {
    echo "Erro: " . $conn->error;
}




$stmt_del->close();
$stmt_ing->close();
$conn->close();

?>