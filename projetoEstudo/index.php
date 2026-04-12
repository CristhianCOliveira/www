<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Laragon</title>

    <link href="https://fonts.googleapis.com/css?family=Karla:400" rel="stylesheet">

</head>
<body>

<div class="container">
    <h1>Formulário de Idade</h1>
<form method="post" action="salvarDB.php" class="treino">

    <p>Nome:<input type="text" name="name" placeholder="Digite seu nome"></p>
    <p>E-mail:<input type="email" name="email" placeholder="Digite seu e-mail"></p>

    <button type="submit">Salvar no Banco de Dados</button>
    
</form>


<a href="tratamentoDeDados.php"><button>Listar Usuários</button></a>

</div>
</body>
</html>