<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Receita</title>
    <link rel="stylesheet" href="assets/style.css">
    
</head>
<body>

<div class="header">
    🍳 Cadastrar nova receita
</div>

<div class="main">

    <div class="content">

        <div class="form-card">

            <h2>Nova Receita</h2>

            <form method="POST" action="create.php" enctype="multipart/form-data">

                <label>Título</label>
                <input type="text" name="titulo" required>

                <label>Descrição</label>
                <textarea name="descricao" required></textarea>

                <label>Ingredientes (separados por vírgula)</label>
                <input type="text" name="ingredientes" required>

                <input type="file" name="imagem" accept="image/*">

                <button type="submit">Cadastrar</button>

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