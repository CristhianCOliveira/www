<?php

require_once "config/database.php";
require_once "includes/functions.php";

//Aqui está sendo usado duas interrogações que funcionam como um if.
//Basicamente ela diz, se houver algo na string use ela, se não, use string vazia.
$titulo = trim($_POST['titulo'] ?? "");
$descricao = trim($_POST['descricao'] ?? "");

if(empty($titulo)){
    resposta(false, "Título obrigatório");
}
if(strlen($titulo) > 100){
    resposta(false, "O título está muito grande, favor inserir no máximo 100 caracteres.");
}
if(empty($descricao)){
    resposta(false, "Favor inserir descrição/Modo de preparo na receita.");
}


$ingredientesInput= trim($_POST['ingredientes'] ?? "");
$ingredientes = explode(",", $ingredientesInput);

$nomeImagem = uploadImagem($_FILES['imagem'] ?? null, __DIR__ . "/uploads");

//Inserção da receita no banco
$stmt = $conn->prepare("INSERT INTO receitas (titulo, descricao, imagem) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $titulo, $descricao, $nomeImagem);


//Inserção de ingredientes

if($stmt->execute()){
$receita_id = $conn->insert_id;
$stmt_ing = $conn->prepare("INSERT INTO ingredientes (receita_id, nome) VALUES (?,?)");

foreach ($ingredientes as $ingrediente) {

    $ingrediente = trim($ingrediente);

    //Valida o ingrediente para verificar se veio vazio e verifica também o tamanho do nome do arquivo
    if(empty($ingrediente) || strlen($ingrediente) > 100){
        continue;
    }

    if(!preg_match("/^[a-zA-ZÀ-ÿ0-9\s]+$/", $ingrediente)){
    continue;
    }

//Teoricamente só chega aqui se passou nas duas validações anteriores
$stmt_ing->bind_param("is", $receita_id, $ingrediente);

if (!$stmt_ing->execute()) {
        resposta(false, "Erro ao salvar ingrediente");
    }
    
}


header("Location: index.php");
exit;

} else {
    echo "Erro ao salvar a receita: " . $conn->error;
};

$stmt->close();

if(isset($stmt_ing)){
    $stmt_ing->close();
};
$conn->close();


?>


