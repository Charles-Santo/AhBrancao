<?php

require __DIR__ . "/../src/Modelo/Usuario.php";

session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}


require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Meme.php";
require __DIR__ . "/../src/Modelo/Categoria.php";
require __DIR__ . "/../src/Repositorio/MemeRepositorio.php";
require __DIR__ . "/../src/Repositorio/UsuarioRepositorio.php";
require __DIR__ . "/../src/Repositorio/CategoriaRepositorio.php";

$usuarioLogado = $_SESSION['usuario'] ?? null;
if (!$usuarioLogado) {
    header('Location: login.php');
    exit;
}
$usuarioRepositorio = new UsuarioRepositorio($pdo);
$memeRepositorio = new MemeRepositorio($pdo);
$categoriaRepositorio = new CategoriaRepositorio($pdo);
$memesPendentes = $memeRepositorio->buscarMemesPendentes()
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Inria+Sans:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/meme.css">
    <link rel="icon" href="../img/logo-AhBrancao.png" type="image/x-icon">

    <title>Ah Brancão - Meme</title>
</head>

<body>

    <header class="container-cabecalho">
        <div class="topo-esquerda">
            <span>Admin</span>
        </div>

        <div class="container-admin-banner">
            <a href="../dashboard.php">
                <img src="../img/logo-AhBrancao.png" alt="logo-ah-brancao" class="logo-header">
            </a>
        </div>

        <div class="topo-direita">
            <form action="../memes/form.php" method="post" style="display:inline;">
                <button type="submit" class="botao-publicar-memes">Publicar memes</button>
            </form>
            <span>Bem-vindo, <?php echo htmlspecialchars($usuarioLogado->getNome()); ?></span>
            <a href="../usuarios/editar.php?id= <?= $usuarioLogado->getId() ?>">
                <img class="imagem-avatar-topo" src="../<?= htmlspecialchars($usuarioLogado->getAvatar()) ?>"
                    alt="Imagem do Avatar">
            </a>
            <form action="../logout.php" method="post" style="display:inline;">
                <button type="submit" class="botao-sair">Logout</button>
            </form>
        </div>
    </header>
    <main class="container-principal">
        <h1>Admin</h1>
        <h2>Validar Meme</h2>

        <?php if (empty($memesPendentes)): ?>
            <p>Não há memes pendentes de validação.</p>
        <?php else: ?>

            <?php foreach ($memesPendentes as $meme): ?>
                <?php

                $idUsuario = $meme->getIdUsuarioAutor();
                $usuario = $usuarioRepositorio->buscar($idUsuario);
                $categoriasId = $meme->getCategoriasId();
                ?>


                <div class="cards-memes">

                    <p class="titulo-meme"><?= htmlspecialchars($meme->getTituloMeme()) ?></p>


                    <img src="../<?= htmlspecialchars($meme->getImagemMeme()) ?>" alt="Meme">


                    <div class="footer-meme">
                        <p class="descricao-meme"><?= htmlspecialchars($meme->getTextoMeme()) ?></p>

                        <div class="listar-categorias-meme">
                            <?php foreach ($categoriasId as $categoriaId):
                                $categoria = $categoriaRepositorio->buscar($categoriaId) ?>

                                <img src="../<?= htmlspecialchars($categoria->getImagem())?>">
                                <p class="descricao-meme"> <?= htmlspecialchars($categoria->getNome()) ?></p>
                                <br>
                            <?php endforeach ?>
                        </div>
                        <div class="usuario-meme">
                            <span><?= htmlspecialchars($usuario->getNome()) ?></span>
                            <img class="imagem-avatar-meme" src="../<?= htmlspecialchars($usuario->getAvatar()) ?>" alt="Avatar do Usuário">

                        </div>
                    </div>
                </div>
                <div class="espaco-botoes">
                    <form action="validarAcao.php" method="post" style="display:inline;">
                        <input type="hidden" name="meme_id" value="<?php echo htmlspecialchars($meme->getId()); ?>">
                        <button type="submit" name="acao" value="aprovar" class="botao-aprovar">Aprovar</button>
                        <button type="submit" name="acao" value="rejeitar" class="botao-rejeitar">Rejeitar</button>
                    </form>
                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </main>
</body>

</html>