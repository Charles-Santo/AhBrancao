<?php
require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Categoria.php";
require __DIR__ . "/../src/Repositorio/CategoriaRepositorio.php";

$categoriaRepositorio = new CategoriaRepositorio($pdo);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $categoria = new Categoria(
        $_POST['codigo'] ?: null,
        $_POST['nome'],
        $_POST['descricao'],
        $_POST['imagem'] ?? ''
    );
}

if ($categoria->getCodigo()) {

    $categoriaRepositorio->atualizar($categoria);
} else {

    $categoriaRepositorio->salvar($categoria);
}

header("Location: listar.php");
exit();

$repo = new CategoriaRepositorio($pdo);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: listar.php');
    exit;
}

$codigo = isset($_POST['codigo']) && $_POST['codigo'] !== '' ? (int)$_POST['codigo'] : null;
$nome = trim($_POST['nome']   ?? '');
$descricao = trim($_POST['descricao'] ?? '');
$imagem = trim($_POST['imagem'] ?? '');

if ($nome === '' || $descricao === '' || (!$codigo === '')) {
    header('Location: form.php' . ($codigo ? '?codigo=' . $codigo . '&erro=campos' : '?erro=campos'));
    exit;
}

if ($codigo) {

    $existente = $repo->buscar($codigo);
    if (!$existente) {
        header('Location: listar.php?erro=inexistente');
        exit;
    }


    $categoria = new Categoria($codigo, $nome, $descricao, $imagem ?? '');
    $repo->atualizar($categoria);
    header('Location: listar.php?ok=1');
    exit;
} else {
    
    $categoria = new Categoria(null, $nome, $descricao, $imagem ?? '');
    $repo->salvar($categoria);
    header('Location: listar.php?novo=1');
    exit;
}
