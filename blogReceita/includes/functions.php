<?php

//Adiciona uma tratativa de erro para caso aconteça algum
function resposta($sucesso, $mensagem, $dados = null ){
    if(!headers_sent()){
        header('Content-Type: application/json');
    }

    echo json_encode([
        "success" => $sucesso,
        "messagem" => $mensagem,
        "data" => $dados
    ]);

    
    exit;
}

//Upload de imagens
function uploadImagem($file, $pastaDestino){

    if(!isset($file) || $file['error'] !== 0){
        return null;
    }

    $tiposPermitidos = ['image/jpeg', 'image/png'];

    if (!in_array($file['type'], $tiposPermitidos)){
        resposta(false, "Formato inválido. Permitido apenas JPG e PNG.");
    }

    if ($file['size'] > 2 * 1024 * 1024){
        resposta(false, "Imagem muito grande. Máximo de 2MB.");
    }

    $extensao = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($extensao, ['jpg', 'jpeg', 'png'])){
        resposta(false, "Extensão inválida");
    }

    if (!is_dir($pastaDestino)){
        mkdir($pastaDestino, 0777, true);
    }

    $nomeArquivo = uniqid() . "." . $extensao;
    $caminho = $pastaDestino . "/" . $nomeArquivo;

    if (!move_uploaded_file($file['tmp_name'], $caminho)){
        resposta(false, "Erro ao salvar a imagem");
    }

    return $nomeArquivo;
}





?>