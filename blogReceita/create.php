<?php

require_once "config/database.php";

//Aqui está sendo usado duas interrogações que funcionam como um if.
//Basicamente ela diz, se houver algo na string use ela, se não, use string vazia.
$titulo = trim($_POST['titulo'] ?? "");
$descricao = trim($_POST['descricao'] ?? "");

if(empty($titulo)){
    die("Título obrigatório na receita.");
}
if(strlen($titulo) > 100){
    die("O título está muito grande, favor inserir no máximo 100 caracteres.");
}
if(empty($descricao)){
    die("Favor inserir descrição/Modo de preparo na receita.");
}


$ingredientesInput= $_POST['ingredientes'] ?? "";
$ingredientes = explode(",", $ingredientesInput);
$nomeImagem = null;



if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0 ){

    //Aqui está sendo verificado os tipos de imagem que podem ser inseridos no site. Evita arquivos maliciosos.
    $tiposPermitidos = ['image/jpeg', 'image/png'];

    if (!in_array($_FILES['imagem']['type'], $tiposPermitidos)) {
        die("Formato inválido. Apenas , JPEG, JPG e PNG.");
    }
    //Aqui estou limitando o tamanho das imagens que podem inseridas. Máximo de 2 mb
    if ($_FILES['imagem']['size'] > 2 * 1024 * 1024) {
        die("Imagem muito grande. Máximo 2MB.");
    }

    //Aqui eu estou validando o tipo de extensão do arquivo para que ninguém manipule um arquivo malicioso para enganar o programa.
    $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));

    if(!in_array($extensao, ['jpg', 'jpeg', 'png'])){
        die("Extensão da imagem inválida");
    }

    //Aqui informo para onde o arquivo será enviado.
    $pasta = __DIR__ . "/uploads/";
    $nomeArquivo = uniqid() . "." . $extensao;  //uniqid gera um nome aleatório baseado no tempo em microssegundos
    $caminho = $pasta . $nomeArquivo;

    //Aqui acontece o salvamento do arquivo.
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
        echo "Erro ao salvar ingrediente: " . $conn->error;
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


