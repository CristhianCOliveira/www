<?php

$conn = new mysqli("localhost", "root", "", "receitas_db");

if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

$id = intval($_POST['id']);
$titulo = $_POST['titulo'];
$descricao = $_POST['descricao'];
$ingredientes = explode(",", $_POST['ingredientes']);

// 1. Atualiza receita
$stmt = $conn->prepare("UPDATE receitas SET titulo = ?, descricao = ? WHERE id = ?");
$stmt->bind_param("ssi", $titulo, $descricao, $id);

if ($stmt->execute()) {

    // 2. Deleta ingredientes antigos
    $stmt_del = $conn->prepare("DELETE FROM ingredientes WHERE receita_id = ?");
    $stmt_del->bind_param("i", $id);

    // 3. Insere novos ingredientes

    if($stmt_del->execute()){

    $stmt_ing = $conn->prepare("INSERT INTO ingredientes (receita_id, nome) VALUES (?, ?)");


    foreach ($ingredientes as $ingrediente) {

        $ingrediente = trim($ingrediente); // remove espaços

        if (!empty($ingrediente)) {

           
            $stmt_ing->bind_param("is", $id, $ingrediente);

            if (!$stmt_ing->execute()) {
                echo "Erro ao inserir ingrediente: " . $conn->error;
            }
        }
    }
    }

    header("Location: index.php");
    exit;

} else {
    echo "Erro: " . $conn->error;
}

$stmt->close();

if (isset($stmt_del)) {
    $stmt_del->close();
}

if (isset($stmt_ing)) {
    $stmt_ing->close();
}

$conn->close();
?>