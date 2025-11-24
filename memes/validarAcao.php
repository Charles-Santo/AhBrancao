<?php

require __DIR__ . "/../src/Modelo/Usuario.php";
require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/MemeRepositorio.php';
require_once __DIR__ . '/../src/Modelo/Meme.php';

session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$repo = new MemeRepositorio($pdo);

$idMeme = $_POST['meme_id'] ?? [];   
$AcaoTomada = $_POST['acao'] ?? [];   
$idUsuarioAprovador = $_SESSION['usuario']->getId();

if($AcaoTomada === 'aprovar'){
    
        
            $repo->aprovarMeme($idMeme, $idUsuarioAprovador);
        
            header('Location: validacao.php?ok=1');
    
} elseif($AcaoTomada === 'rejeitar'){
    
        
            $repo->deletar($idMeme);    

            header('Location: validacao.php?ok=1');
    }


?>