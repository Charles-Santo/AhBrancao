<?php

require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Meme.php";
require __DIR__ . "/../src/repositorio/MemeRepositorio.php";

$memeRepositorio = new MemeRepositorio($pdo);
$memeRepositorio->deletar($_POST['id']);

header("Location: ../index.php");