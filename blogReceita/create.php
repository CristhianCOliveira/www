<?php

$conn = new mysqli("localhost", "root", "", "receitas_db");

if ($conn -> connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$titulo = $_POST['titulo'];
$ingredientes = explode(",", $_POST['ingredientes']);
$descricao = $_POST['descricao'];


$sql = "INSERT INTO receitas (titulo, descricao) values ('$titulo', '$descricao')";

if ($conn->query($sql) === TRUE) {

    $receita_id = $conn->insert_id;


    foreach ($ingredientes as $ingrediente) {

        $ingrediente = trim($ingrediente);

        if (!empty($ingrediente)) {

            $sql_ing = "INSERT INTO ingredientes (receita_id, nome) 
                        VALUES ($receita_id, '$ingrediente')";

            if (!$conn->query($sql_ing)) {
                echo "Erro ao salvar ingrediente: " . $conn->error;
            }
        }
    }

    echo "Receita cadastrada com sucesso!<br>";
    echo "<a href='index.php'>Voltar</a>";

} else {
    echo "Erro: " . $conn->error;
}


$conn->close();


?>


