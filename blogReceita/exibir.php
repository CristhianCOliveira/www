<?php

$conn = new mysqli("localhost", "root", "", "receitas_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM receitas");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        echo "<div class='card'>";

        // Imagem aleatória que vou trocar assim que possível
        echo "<img src='https://picsum.photos/400/200?random=" . $row['id'] . "'>";

        echo "<div class='card-content'>";

        echo "<h2>" . $row['titulo'] . "</h2>";

        echo "<div class='actions'>";
        echo "<a href='editar.php?id=" . $row['id'] . "'>Editar</a>";
        echo "<a href='delete.php?id=" . $row['id'] . "' onclick='return confirm(\"Tem certeza?\")'>Excluir</a>";
        echo "</div>";

        // Imprime os ingredientes na tela
        $ingredientes_result = $conn->query("SELECT nome FROM ingredientes WHERE receita_id = " . $row['id']);

        if ($ingredientes_result->num_rows > 0) {
            echo "<h4>Ingredientes:</h4>";
            echo "<ul>";

            while ($ing_row = $ingredientes_result->fetch_assoc()) {
                echo "
                <li>
                    <label class='checkbox-item'>
                        <input type='checkbox'>
                        <span class='custom-checkbox'></span>
                        <span>" . $ing_row['nome'] . "</span>
                    </label>
                </li>
                ";
            }

            echo "</ul>";
        }

        echo "<p>" . $row['descricao'] . "</p>";

        echo "</div>"; // card-content
        echo "</div>"; // card
    }
}
?>