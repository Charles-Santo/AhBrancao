<?php
require_once __DIR__ . '/../src/Modelo/Usuario.php';

session_start();

require __DIR__ . '/../src/conexao-bd.php';

require __DIR__ . "/../src/Repositorio/MemeRepositorio.php";
require __DIR__ . "/../src/Modelo/Meme.php";
require __DIR__ . "/../src/Repositorio/UsuarioRepositorio.php";

$usuarioRepositorio = new UsuarioRepositorio($pdo);

$memeRepositorio = new MemeRepositorio($pdo);


$usuarioLogado = $_SESSION['usuario'];


$memes = $memeRepositorio->buscarMemesUsuario($usuarioLogado->getId());



function pode(string $perm): bool
{
    return in_array($perm, $_SESSION['permissoes'] ?? [], true);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="img/logo-AhBrancao.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inria+Sans:wght@300;400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="../css/meme.css">

    <title>Ah Brancão - Inicio</title>



</head>

<body>

    <?php if ($usuarioLogado): ?>
        <header class="container-cabecalho">
            <?php if (pode('categorias.listar')): ?>
                <div class="topo-esquerda">
                    <span>Admin</span>
                </div>
            <?php endif; ?>
            <div class="container-admin-banner">
                <a href="../index.php">
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
    <?php else: ?>
        <header class="container-cabecalho">
            <div class="container-admin-banner">
                <a href="index.php">
                    <img src="img/logo-AhBrancao.png" alt="logo-ah-brancao" class="logo-header">
                </a>
            </div>
            <div class="topo-direita">
                <form action="login.php" method="post" style="display:inline;">
                    <button type="submit" class="botao-publicar-memes">Publicar memes</button>
                </form>
                <form action="login.php" method="post" style="display:inline;">
                    <button type="submit" class="botao-sair">Login</button>
                </form>
            </div>
        </header>
    <?php endif; ?>

    <main class="container-principal">



        <section class="container-memes" style="margin-top: 50px;">
            <?php if (empty($memes)): ?>
                <h2>Nenhum meme encontrado nesta página.</h2>
            <?php else:; ?>
                <h2>Seus Memes</h2>
            <?php endif ?>

            <?php foreach ($memes as $meme): ?>
                <?php
                $idUsuario = $meme->getIdUsuarioAutor();
                $usuario = $usuarioRepositorio->buscar($idUsuario);
                ?>
                <div class="cards-memes">
                    <p class="titulo-meme"><?= htmlspecialchars($meme->getTituloMeme()) ?></p>
                    <img src="../<?= htmlspecialchars($meme->getImagemMeme()) ?>" alt="Meme">
                    <div class="footer-meme">
                        <p class="descricao-meme"><?= htmlspecialchars($meme->getTextoMeme()) ?></p>


                        <div class="opcoes-meme-excluir">

                            <form action="excluir.php" method="post">

                                <input type="hidden" name="id" value="<?= $meme->getId() ?>">
                                <input type="submit" value="apagar">

                            </form>

                        </div>

                        <?php if ($meme->getCodigoAdminAprovador() === null): ?>

                            <p class="descricao-meme" >Seu Meme Ainda Não Foi Aprovado</p>

                        <?php else: ?>

                            <p class="descricao-meme" >Seu Meme Já Foi Aprovado</p>

                        <?php endif; ?>


                        <div class="usuario-meme">
                            <span><?= htmlspecialchars($usuario->getNome()) ?></span>
                            <img class="imagem-avatar-meme" src="../<?= htmlspecialchars($usuario->getAvatar()) ?>" alt="Avatar do Usuário">
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </section>



    </main>
</body>

</html>