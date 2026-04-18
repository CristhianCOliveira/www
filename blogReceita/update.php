<?php

require_once "config/database.php";

$id = intval($_POST['id'] ?? 0);
$titulo = trim($_POST['titulo'] ?? "");
$descricao = trim($_POST['descricao'] ?? "");
$ignredientesInput = trim($_POST['ingredientes'] ?? "");
$ingredientes = explode(",", $ingredientesInput);

if($id <= 0){
    die("ID de receita não encontrado");
}

if(empty($titulo) || strlen($titulo) > 100){
    die("Titulo invalido");
}

if(empty($descricao)){
    die("Descrição obrigatória");
}

//Aqui estamos buscando a imagem que já existe no banco para podermos manipular.
$stmt_img = $conn->prepare("SELECT imagem FROM receitas WHERE id = ?");
$stmt_img->bind_param("i", $id);
$stmt_img->execute();

$result = $stmt_img->get_result();
$receita = $result -> fetch_assoc();

$imagemAtual = $receita['imagem'] ?? null;

$novaImagem = $imagemAtual;

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {

    
    $tiposPermitidos = ['image/jpeg', 'image/png'];

    if (!in_array($_FILES['imagem']['type'], $tiposPermitidos)) {
        die("Formato inválido. Apenas JPG e PNG.");
    }

    
    if ($_FILES['imagem']['size'] > 2 * 1024 * 1024) {
        die("Imagem muito grande. Máximo 2MB.");
    }


    $pasta = __DIR__ . "/uploads/";

    
    $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
    $nomeArquivo = time() . "." . $extensao;

    $caminho = $pasta . $nomeArquivo;

    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {


        if (!empty($imagemAtual)) {
            $caminhoAntigo = $pasta . $imagemAtual;

            if (file_exists($caminhoAntigo)) {
                unlink($caminhoAntigo);
            }
        }
        $novaImagem = $nomeArquivo;

    } else {
        die("Erro ao enviar nova imagem");
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
            echo "Erro ao inserir ingrediente: " . $conn->error;
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