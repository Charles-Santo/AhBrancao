<?php
require_once __DIR__ . '/src/Modelo/Usuario.php';

session_start();



$usuarioLogado = $_SESSION['usuario'] ?? null;



require __DIR__ . '/src/conexao-bd.php';
require __DIR__ . "/src/Modelo/Categoria.php";
require __DIR__ . "/src/Repositorio/CategoriaRepositorio.php";



$categoriaRepositorio = new CategoriaRepositorio($pdo);
$categorias = $categoriaRepositorio->buscarTodos();

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


    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/login.css">

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
                <a href="dashboard.php">
                    <img src="img/logo-AhBrancao.png" alt="logo-ah-brancao" class="logo-header">
                </a>
            </div>

            <div class="topo-direita">
                <form action="memes/form.php" method="post" style="display:inline;">
                    <button type="submit" class="botao-publicar-memes">Publicar memes</button>
                </form>
                <span>Bem-vindo, <?php echo htmlspecialchars($usuarioLogado->getNome()); ?></span>
                <a href="usuarios/editar.php?id= <?= $usuarioLogado->getId() ?>">
                    <img class="imagem-avatar-topo" src="<?= htmlspecialchars($usuarioLogado->getAvatar()) ?>"
                        alt="Imagem do Avatar">
                </a>
                <form action="logout.php" method="post" style="display:inline;">
                    <button type="submit" class="botao-sair">Logout</button>
                </form>
            </div>
        </header>
    <?php else: ?>
        <header class="container-cabecalho">


            <div class="container-admin-banner">
                <a href="/dashboard.php">
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
        <h2 class="titulo-inicio">Categorias</h2>
        <section class="container-categorias">

            <?php foreach ($categorias as $categoria): ?>
                <a href="categoria.php?id=<?= htmlspecialchars($categoria->getCodigo()) ?>" class="link-categoria">
                    <div class="cards-categorias">
                        <img class="" src="<?= htmlspecialchars($categoria->getImagem()) ?>">
                        <p class="nome-categoria"><?= htmlspecialchars($categoria->getNome()) ?></p>
                    </div>
                </a>
            <?php endforeach; ?>

        </section>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'sucesso'): ?>
            <script>
                alert("Meme Enviado com Sucesso! Esperar Aprovação");
            </script>

        <?php endif ?>

        <h2 style="margin-top: 100px;" class="titulo-inicio">Idiomas</h2>
        <section style="margin-bottom: 50px;" class="container-categorias">
            <a href="categoria.php?idioma=português" class="link-categoria">
                <div class="cards-categorias">
                    <img class="" src="img/bandeira-brasil.jfif">
                    <p class="nome-idioma">Português</p>
                </div>
            </a>
            <a href="#" class="link-categoria">
                <div class="cards-categorias">
                    <a href="categoria.php?idioma=inglês" class="link-categoria">
                        <img class="" src="img/bandeira-inglaterra.png">
                        <p class="nome-idioma">Inglês</p>
                    </a>
                </div>
            </a>
            <a href="categoria.php?idioma=espanhol" class="link-categoria">
                <div class="cards-categorias">
                    <img class="" src="img/bandeira-espanha.webp">
                    <p class="nome-idioma">Espanhol</p>
                </div>
            </a>
        </section>
    </main>
</body>

</html>