<?php

require_once "config/database.php";

if (!isset($_GET['id'])) {
    die("ID não encontrado.");
}

$receita_id = intval($_GET['id']);

$sql = "SELECT * FROM receitas WHERE id = $receita_id";
$result = $conn->query($sql);

$receita = $result->fetch_assoc();

$ingredientes = [];

$sql_ing = "SELECT nome FROM ingredientes WHERE receita_id = $receita_id";
$result_ing = $conn->query($sql_ing);

while ($row_ing = $result_ing->fetch_assoc()) {
    $ingredientes[] = $row_ing['nome'];
}


$conn->close();
?>


<html>
<head>
    <title>Atualizar Receita</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="header">
</div>

<div class="main">

    <div class="content">

        <div class="form-card">

            <h2>Atualizar Receita</h2>

            <form method="POST" action="update.php" enctype="multipart/form-data">

                <input type="hidden" name="id" value="<?php echo $receita['id']; ?>">

                <label>Título</label>
                <input type="text" name="titulo" value="<?php echo $receita['titulo']; ?>">

                <label>Descrição</label>
                <textarea name="descricao"><?php echo $receita['descricao']; ?></textarea>

                <label>Ingredientes (separados por vírgula)</label>
                <input type="text" name="ingredientes" 
                value="<?php echo implode(', ', $ingredientes); ?>">

                <label>Insira uma imagem: </label>
                <input type="file" name="imagem" accept="image/*">

                <button type="submit">Atualizar</button>

            </form>

        </div>

    </div>

    <div class="sidebar">
        <h3>Publicidade</h3>
        <div class="ad">Anúncio</div>
        <div class="ad">Anúncio</div>
    </div>

</div>

</body>
</html>