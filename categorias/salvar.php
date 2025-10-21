<?php
require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Categoria.php";
require __DIR__ . "/../src/Repositorio/CategoriaRepositorio.php";

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

$repo = new CategoriaRepositorio($pdo);


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: listar.php');
    exit;
}


$codigo = isset($_POST['codigo']) && $_POST['codigo'] !== '' ? (int)$_POST['codigo'] : null;
$nome = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');

if ($nome === '' || $descricao === '') {
    header('Location: form.php' . ($codigo ? '?id=' . $codigo . '&erro=campos' : '?erro=campos'));
    exit;
}


$imagem = $_FILES['imagem'] ?? null;
$caminhoImagem = null;


if ($codigo) {
    $categoriaExistente = $repo->buscar($codigo);
    $caminhoImagem = $categoriaExistente ? $categoriaExistente->getImagem() : null;
}


if ($imagem && $imagem['error'] === UPLOAD_ERR_OK) {
    $pastaUploads = __DIR__ . '/../uploads/';


    $nomeArquivo = uniqid() . '-' . basename($imagem['name']);
    $destino = $pastaUploads . $nomeArquivo;

    if (move_uploaded_file($imagem['tmp_name'], $destino)) {
        $caminhoImagem = 'uploads/' . $nomeArquivo; 
    }
}


$categoria = new Categoria($codigo, $nome, $descricao, $caminhoImagem);


if ($codigo) {
    $repo->atualizar($categoria);
    header('Location: listar.php?ok=1');
} else {
    $repo->salvar($categoria);
    header('Location: listar.php?novo=1');
}

exit;
