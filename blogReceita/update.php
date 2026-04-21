<?php

require_once "config/database.php";
require_once "includes/functions.php";

$id = intval($_POST['id'] ?? 0);
$titulo = trim($_POST['titulo'] ?? "");
$descricao = trim($_POST['descricao'] ?? "");
$ingredientesInput = trim($_POST['ingredientes'] ?? "");
$ingredientes = explode(",", $ingredientesInput);

if($id <= 0){
    resposta(false, "ID de receita não encontrado");
}

if(empty($titulo) || strlen($titulo) > 100){
    resposta(false, "Titulo invalido");
}

if(empty($descricao)){
    resposta(false, "Descrição obrigatória");
}

//Aqui estamos buscando a imagem que já existe no banco para podermos manipular.
$stmt_img = $conn->prepare("SELECT imagem FROM receitas WHERE id = ?");
$stmt_img->bind_param("i", $id);
$stmt_img->execute();

$result = $stmt_img->get_result();
$receita = $result -> fetch_assoc();
$stmt_img->close();

$imagemAtual = $receita['imagem'] ?? null;

$novaImagem = uploadImagem($_FILES['imagem'] ?? null, __DIR__ . "/uploads") ?? $imagemAtual;

if ($novaImagem !== $imagemAtual && !empty($imagemAtual)) {
    $caminhoAntigo = __DIR__ . "/uploads/" . $imagemAtual;

    if (file_exists($caminhoAntigo)) {
        unlink($caminhoAntigo);
    }
}


// 1. Atualiza receita
$stmt = $conn->prepare("UPDATE receitas SET titulo = ?, descricao = ?, imagem = ? WHERE id = ?");
$stmt->bind_param("sssi", $titulo, $descricao, $novaImagem, $id);

if ($stmt->execute()) {

    // 2. Deleta ingredientes antigos
    $stmt_del = $conn->prepare("DELETE FROM ingredientes WHERE receita_id = ?");
    $stmt_del->bind_param("i", $id);

    // 3. Insere novos ingredientes

    if($stmt_del->execute()){

    $stmt_ing = $conn->prepare("INSERT INTO ingredientes (receita_id, nome) VALUES (?, ?)");


    foreach ($ingredientes as $ingrediente) {

        $ingrediente = trim($ingrediente); // remove espaços


        if (empty($ingrediente) || strlen($ingrediente) > 100) {
            continue;
        }

        if (!preg_match("/^[a-zA-ZÀ-ÿ0-9\s]+$/", $ingrediente)) {
            continue;
        }
        
        $stmt_ing->bind_param("is", $id, $ingrediente);

        if (!$stmt_ing->execute()) {
            resposta(false, "Erro ao inserir ingrediente");
        }
        }
    }
    }

    header("Location: index.php");
    exit;


$stmt->close();

if (isset($stmt_del)) {
    $stmt_del->close();
}

if (isset($stmt_ing)) {
    $stmt_ing->close();
}


$conn->close();
?>