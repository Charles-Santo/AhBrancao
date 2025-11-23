<?php
require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Meme.php";    
require __DIR__ . "/../src/Repositorio/MemeRepositorio.php";
require __DIR__ . "/../src/Repositorio/CategoriaRepositorio.php";
require_once __DIR__ . '/../src/Modelo/Usuario.php';

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

$repo = new MemeRepositorio($pdo);
$repoCategoria = new CategoriaRepositorio($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: listar.php');
    exit;
}

$codigo = !empty($_POST['codigo']) ? (int)$_POST['codigo'] : null;

$titulo = trim($_POST['nome'] ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$idioma = trim($_POST['idioma'] ?? '');
$categorias = $_POST['categorias'] ?? [];   
$idUsuarioAutor = $_SESSION['usuario']->getId();
if ($titulo === '' || $descricao === '') {
    header('Location: form.php' . ($codigo ? '?id=' . $codigo . '&erro=campos' : '?erro=campos'));
    exit;
}


$imagem = $_FILES['imagem'] ?? null;
$caminhoImagem = null;

if ($codigo) {
    $memeExistente = $repo->buscar($codigo);
    $caminhoImagem = $memeExistente ? $memeExistente->getImagemMeme() : null;
}

if ($imagem && $imagem['error'] === UPLOAD_ERR_OK) {

    $pastaUploads = __DIR__ . '/../uploads/';
    if (!is_dir($pastaUploads)) {
        mkdir($pastaUploads, 0777, true);
    }

    $tituloArquivo = uniqid() . '-' . basename($imagem['name']);
    $destino = $pastaUploads . $tituloArquivo;

    if (move_uploaded_file($imagem['tmp_name'], $destino)) {
        $caminhoImagem = 'uploads/' . $tituloArquivo;
    }
}

$meme = new Meme(
    $codigo,
    $titulo,
    $descricao,
    $caminhoImagem,
    $idioma,
    $categorias,
    $idUsuarioAutor
);


if ($codigo) {
    $repo->atualizar($meme);
} else {
    $repo->salvar($meme);
}


header('Location: ../index.php?msg=sucesso');


exit;
