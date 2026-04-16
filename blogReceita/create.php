<?php

$conn = new mysqli("localhost", "root", "", "receitas_db");

if ($conn -> connect_error) {
    die("Erro: " . $conn->connect_error);
}

$titulo = $_POST['titulo'];
$ingredientes = explode(",", $_POST['ingredientes']);
$descricao = $_POST['descricao'];

//Inserção da receita no banco
$stmt = $conn->prepare("INSERT INTO receitas (titulo, descricao) values (?, ?)");
$stmt-> bind_param("ss", $titulo, $descricao);


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


