<?php
require __DIR__ . "/src/conexao-bd.php";
require __DIR__ . "/src/Modelo/Usuario.php";
require __DIR__ . "/src/Repositorio/UsuarioRepositorio.php";

$usuarioRepositorio = new UsuarioRepositorio($pdo);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit;
}

$nome    = trim($_POST['nome'] ?? '');
$funcao  = 'User';
$email   = trim($_POST['email'] ?? '');
$senha   = $_POST['senha'] ?? '';
$senhaNovamente   = $_POST['senhaNovamente'] ?? '';

if ($nome === '' || $email === '' || $senha === '') {
    header("Location: registrar.php?erro=campos");
    exit;
}

if ($senha !== $senhaNovamente) {
    header("Location: registrar.php?erro=senhasDiferentes");
    exit;
}



$senhaHash = password_hash($senha, PASSWORD_DEFAULT);
$stmt = $pdo->prepare('INSERT INTO tbUsuario (nome_usuario, funcao_usuario, email_usuario, senha_usuario) VALUES (?, ?, ?, ?)');
$stmt->execute([$nome, $funcao, $email, password_hash($senha, PASSWORD_DEFAULT)]);

header("Location: login.php?novo=1");
