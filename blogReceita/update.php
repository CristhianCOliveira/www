<?php

$conn = new mysqli("localhost", "root", "", "receitas_db");

if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

$id = intval($_POST['id']);
$titulo = $_POST['titulo'];
$descricao = $_POST['descricao'];
$ingredientes = explode(",", $_POST['ingredientes']); // 👈 NOVO

// 1. Atualiza receita
$sql = "UPDATE receitas 
        SET titulo = '$titulo', descricao = '$descricao' 
        WHERE id = $id";

if ($conn->query($sql) === TRUE) {

    // 2. Deleta ingredientes antigos
    $conn->query("DELETE FROM ingredientes WHERE receita_id = $id");

    // 3. Insere novos ingredientes
    foreach ($ingredientes as $ingrediente) {

        $ingrediente = trim($ingrediente); // remove espaços

        if (!empty($ingrediente)) {

            $sql_ing = "INSERT INTO ingredientes (receita_id, nome)
                        VALUES ($id, '$ingrediente')";

            if (!$conn->query($sql_ing)) {
                echo "Erro ao inserir ingrediente: " . $conn->error;
            }
        }
    }

    header("Location: index.php");
    exit;

} else {
    echo "Erro: " . $conn->error;
}

$conn->close();
?>