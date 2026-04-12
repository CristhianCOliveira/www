<?php

$conn = new mysqli("localhost", "root", "", "sistema");

$result = $conn->query("SELECT * FROM usuarios");

echo "<h2>Lista de Usuários</h2>";

while ($user = $result->fetch_assoc()) {
    echo "<p>" . $user['nome'] . " - " . $user['email'] . "</p>";
}

echo "<p><a href=\"index.php\">Voltar</a></p>";
$conn->close();

?>