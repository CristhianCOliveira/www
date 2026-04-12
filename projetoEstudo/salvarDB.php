<?php

$conn = new mysqli("localhost", "root", "", "sistema");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$nome = $_POST["name"];
$email = $_POST["email"];

$sql = "INSERT INTO usuarios (nome, email) VALUES ('$nome', '$email')";

if ($conn->query($sql) === TRUE) {
    echo "Usuário salvo com sucesso!<br>";
    echo "<p><a href=\"index.php\">Voltar</a></p>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn -> close();

?>