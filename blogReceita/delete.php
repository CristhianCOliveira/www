<?php

$conn = new mysqli("localhost", "root", "", "receitas_db");

if ($conn -> connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    die("ID não encontrado.");
}
$receita_id = intval($_GET['id']);

$sql = " DELETE FROM receitas WHERE id = $receita_id";

if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
    exit();
} else {
    echo "Erro: " . $conn->error;
}

$conn->close();

?>