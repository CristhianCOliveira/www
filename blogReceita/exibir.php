<?php

require_once "config/database.php";

$busca = trim($_GET['busca'] ?? '');


if(!empty($busca)){

    //Aqui estamos conectando no banco e buscando as informações que contém a busca realizada. Like faz essa comparação
    $sql = ("SELECT * FROM receitas WHERE titulo LIKE ? OR descricao LIKE ? ORDER BY id DESC");

    $stmt = $conn -> prepare($sql);

    //Os % são importantes par que a pesquisa inclua aquela palavra e não se limite a encontrar somente aquela palvra.
    //Ex: "Bolo de côco" vai ser encontrado se você pesquisar por somente "bolo"
    $buscaLike = "%$busca%";

    //Amarrei as duas interrogações com a mesma variável pois ela vai buscar em descrição e em título
    $stmt->bind_param("ss", $buscaLike, $buscaLike);

    $stmt->execute();

    $result = $stmt->get_result();

} else {

$result = $conn->query("SELECT * FROM receitas ORDER BY id DESC");

}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        echo "<div class='card'>";

        if (!empty($row['imagem'])) {
        echo "<img src='/blogReceita/uploads/" . $row['imagem'] . "' style='width:300px; display:block;'>";
        }

        echo "<div class='card-content'>";

        echo "<h2>" . htmlspecialchars($row['titulo']) . "</h2>";

        echo "<div class='actions'>";
        echo "<a href='editar.php?id=" . htmlspecialchars($row['id']) . "'>Editar</a>";
        echo "<a href='delete.php?id=" . htmlspecialchars($row['id']) . "' onclick='return confirm(\"Tem certeza que deseja excluir essa receita? Essa ação não pode ser desfeita.\")'>Excluir</a>";
        echo "</div>";

        // Imprime os ingredientes na tela e protege conta SQL injection. 
        $stmt_ing = $conn->prepare("SELECT nome FROM ingredientes WHERE receita_id = ?");
        $stmt_ing->bind_param("i", $row['id']);
        $stmt_ing->execute();
        $ingredientes_result = $stmt_ing->get_result();

        if ($ingredientes_result->num_rows > 0) {
            echo "<h4>Ingredientes:</h4>";
            echo "<ul>";

            while ($ing_row = $ingredientes_result->fetch_assoc()) {
                echo "
                <li>
                    <label class='checkbox-item'>
                        <input type='checkbox'>
                        <span class='custom-checkbox'></span>
                        <span>" . htmlspecialchars($ing_row['nome']) . "</span>
                    </label>
                </li>
                ";
            }

            echo "</ul>";
        }

        echo "<p>" . htmlspecialchars($row['descricao']) . "</p>";

        echo "</div>";
        echo "</div>";
    }

    if ($result->num_rows === 0){
        echo "<p>Nenhuma receita encontrada</p>";
    }
}

?>