<?php

require_once "config/database.php";

$titulo = $_POST['titulo'];
$ingredientes = explode(",", $_POST['ingredientes']);
$descricao = $_POST['descricao'];
$nomeImagem = null;



if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0 ){

    //Aqui está sendo verificado os tipos de imagem que podem ser inseridos no site. Evita arquivos maliciosos.
    $tiposPermitidos = ['image/jpeg', 'image/png'];

    if (!in_array($_FILES['imagem']['type'], $tiposPermitidos)) {
        die("Formato inválido. Apenas JPG e PNG.");
    }
    //Aqui estou limitando o tamanho das imagens que podem inseridas. Máximo de 2 mb
    if ($_FILES['imagem']['size'] > 2 * 1024 * 1024) {
        die("Imagem muito grande. Máximo 2MB.");
    }

    $pasta = __DIR__ . "/uploads/";
    $nomeArquivo = time() . "_" . $_FILES['imagem']['name'];
    $caminho = $pasta . $nomeArquivo;

    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)){
        $nomeImagem = $nomeArquivo;
    } else {
        die("Erro ao salvar imagem");
    };
}

//Inserção da receita no banco
$stmt = $conn->prepare("INSERT INTO receitas (titulo, descricao, imagem) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $titulo, $descricao, $nomeImagem);


//Inserção de ingredientes

if($stmt->execute()){
$receita_id = $conn->insert_id;
$stmt_ing = $conn->prepare("INSERT INTO ingredientes (receita_id, nome) VALUES (?,?)");

foreach ($ingredientes as $ingrediente) {

    $ingrediente = trim($ingrediente);

    if (!empty($ingrediente)) {

        $stmt_ing->bind_param("is", $receita_id, $ingrediente);
      
        if (!$stmt_ing->execute()) {
            echo "Erro ao salvar ingrediente: " . $conn->error;
        }
    }
}


echo "Receita cadastrada com sucesso!<br>";
echo "<a href='index.php'>Voltar</a>";

} else {
    echo "Erro ao salvar a receita: " . $conn->error;
};

$stmt->close();

if(isset($stmt_ing)){
    $stmt_ing->close();
};
$conn->close();


?>


