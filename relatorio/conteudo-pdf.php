<?php
require "../src/conexao-bd.php";
require "../src/Modelo/Meme.php";
require "../src/Modelo/Usuario.php";
require "../src/Modelo/Categoria.php";
require "../src/Repositorio/MemeRepositorio.php";
require "../src/Repositorio/UsuarioRepositorio.php";
require "../src/Repositorio/CategoriaRepositorio.php";

date_default_timezone_set('America/Sao_Paulo');
$rodapeDataHora = date('d/m/Y H:i');

$memeRepositorio = new MemeRepositorio($pdo);

$categoriaRepositorio = new CategoriaRepositorio($pdo);
$usuarioRepositorio = new UsuarioRepositorio($pdo);


$codigoCategoria = $_POST['codigo'] ?? null;
$idiomaCategoria = $_POST['idioma'] ?? null;

if ($codigoCategoria !== null) {
    $categoria = $categoriaRepositorio->buscar($codigoCategoria);
    $memes = $memeRepositorio->buscarCategoriaMeme($codigoCategoria);
}else{
    if($idiomaCategoria !== null){
    $memes = $memeRepositorio->buscarSemPaginaMemeIdioma($idiomaCategoria);
    }
    else{
        $memes =$memeRepositorio->buscarTodos();
    }
}


$logoPath = '../img/logo-AhBrancao.png';
$logoData = base64_encode(file_get_contents($logoPath));
$logoBase64 = "data:image/jpeg;base64," . $logoData;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">

    <style>
        body,
        table,
        th,
        td,
        h3 {
            font-family: Arial, Helvetica, sans-serif;
        }

        .logo {
            width: 120px;
            display: block;
            margin: 10px auto;
        }

        h3 {
            text-align: center;
            margin: 12px 0 20px 0;
        }

        table {
            width: 95%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        table th,
        table td {
            border: 1px solid #F9B012;
            padding: 8px;
            font-size: 12px;
        }

        table th {
            background: #f2f2f2;
            font-size: 13px;
        }

        .meme-img {
            width: 120px;

        }

        .pdf-footer {
            position: fixed;
            bottom: 0;
            text-align: center;
            width: 100%;
            padding: 5px;
            font-size: 12px;
            border-top: 1px solid #444;
            background: #fafafa;
        }
    </style>

</head>

<body>

    <img src="<?= $logoBase64 ?>" class="logo">

    <h3>Relatório de Memes</h3>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Texto</th>
                <th>Idioma</th>
                <th>Imagem</th>
                <th>Autor (Nome)</th>
                <th>Categoria (Nome)</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($memes as $meme): ?>

                <?php
                
                $imgPath = "../" . $meme->getImagemMeme();

                if (file_exists($imgPath)) {
                    $imgBase64 = "data:image/jpeg;base64," . base64_encode(file_get_contents($imgPath));
                } else {
                    $imgBase64 = "";
                }
                ?>

                <tr>
                    <td><?= htmlspecialchars($meme->getId()) ?></td>
                    <td><?= htmlspecialchars($meme->getTituloMeme()) ?></td>
                    <td><?= htmlspecialchars($meme->getTextoMeme()) ?></td>
                    <td><?= htmlspecialchars($meme->getIdiomaMeme()) ?></td>

                    <td>
                        <?php if ($imgBase64): ?>
                            <img src="<?= $imgBase64 ?>" class="meme-img">
                        <?php else: ?>
                            (sem imagem)
                        <?php endif; ?>
                    </td>
                    <?php $usuario = $usuarioRepositorio->buscar($meme->getIdUsuarioAutor()) ?>
                    <td><?= htmlspecialchars($usuario->getNome() ?? "―") ?></td>

                    <td>
                        <?php if ($codigoCategoria !== null): ?>
                            <?= htmlspecialchars($categoria->getNome())?>
                        <?php else: ?>
                            <?= htmlspecialchars($meme->getIdiomaMeme() ) ?>
                             Meme Idioma
                        <?php endif ?>
                    </td>
                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="pdf-footer">
        Gerado em <?= $rodapeDataHora ?>
    </div>

</body>

</html>