<?php
require_once __DIR__ . '/src/Modelo/Usuario.php';

session_start();

require __DIR__ . '/src/conexao-bd.php';
require __DIR__ . "/src/Modelo/Categoria.php";
require __DIR__ . "/src/Repositorio/CategoriaRepositorio.php";
require __DIR__ . "/src/Repositorio/MemeRepositorio.php";
require __DIR__ . "/src/Modelo/Meme.php";
require __DIR__ . "/src/Repositorio/UsuarioRepositorio.php";

$usuarioRepositorio = new UsuarioRepositorio($pdo);
$categoriaRepositorio = new CategoriaRepositorio($pdo);
$memeRepositorio = new MemeRepositorio($pdo);

$usuarioLogado = $_SESSION['usuario'] ?? null;
$idCategoria = $_GET['id'] ?? null;
$idiomaCategoria = $_GET['idioma'] ?? null;




$paginaAtual = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT) ?: 1;
$itensPorPagina = filter_input(INPUT_GET, 'itens', FILTER_VALIDATE_INT) ?: 5;
$ordem = $_GET['ordem'] ?? 'data_envio';
$direcao = $_GET['direcao'] ?? 'DESC';


$paginaAtual = max(1, $paginaAtual);


$offset = ($paginaAtual - 1) * $itensPorPagina;


$totalMemes = 0;
$memes = [];
$categoria = null;

if ($idCategoria === null) {

    $totalMemes = $memeRepositorio->contarTotalMemeIdioma($idiomaCategoria);

    $memes = $memeRepositorio->buscarMemeIdioma($idiomaCategoria, $itensPorPagina, $offset, $ordem, $direcao);
} else {

    $categoria = $categoriaRepositorio->buscar($idCategoria);

    $totalMemes = $memeRepositorio->contarTotalMemeCategoria($idCategoria);

    $memes = $memeRepositorio->buscarPaginadoCategoria($idCategoria, $itensPorPagina, $offset, $ordem, $direcao);
}


$totalPaginas = ceil($totalMemes / $itensPorPagina);



function urlPaginacao(array $novosParams)
{
    $params = $_GET;
    $params = array_merge($params, $novosParams);
    return '?' . http_build_query($params);
}

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
    <link rel="stylesheet" href="css/meme.css">

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
                <a href="index.php">
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
        <?php if ($idCategoria === null): ?>
            <h2 class="">Memes em <?= htmlspecialchars($idiomaCategoria) ?> </h2>
        <?php else: ?>
            <h2 class="">Memes da Categoria <?= htmlspecialchars($categoria->getNome()) ?> </h2>
        <?php endif; ?>


        <section class="controles-lista">
            <form method="GET" action="">

                <?php if ($idCategoria): ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($idCategoria) ?>">
                <?php endif; ?>
                <?php if ($idiomaCategoria): ?>
                    <input type="hidden" name="idioma" value="<?= htmlspecialchars($idiomaCategoria) ?>">
                <?php endif; ?>

                <div>
                    <label for="itens">Exibir:</label>
                    <select name="itens" id="itens" onchange="this.form.submit()">
                        <option value="5" <?= $itensPorPagina == 5 ? 'selected' : '' ?>>5</option>
                        <option value="10" <?= $itensPorPagina == 10 ? 'selected' : '' ?>>10</option>
                        <option value="20" <?= $itensPorPagina == 20 ? 'selected' : '' ?>>20</option>
                    </select>
                </div>

                <div>
                    <label for="direcao">Ordem:</label>
                    <select name="direcao" id="direcao" onchange="this.form.submit()">
                        <option value="DESC" <?= $direcao === 'DESC' ? 'selected' : '' ?>>Mais recentes</option>
                        <option value="ASC" <?= $direcao === 'ASC' ? 'selected' : '' ?>>Mais antigos</option>
                    </select>
                </div>
            </form>

            <span>Total: <?= $totalMemes ?> memes encontrados.</span>

        </section>

        <section class="container-memes">
            <?php if (empty($memes)): ?>
                <p>Nenhum meme encontrado nesta página.</p>
            <?php endif; ?>

            <?php foreach ($memes as $meme): ?>
                <?php
                $idUsuario = $meme->getIdUsuarioAutor();
                $usuario = $usuarioRepositorio->buscar($idUsuario);
                ?>
                <div class="cards-memes">
                    <p class="titulo-meme"><?= htmlspecialchars($meme->getTituloMeme()) ?></p>
                    <img src="<?= htmlspecialchars($meme->getImagemMeme()) ?>" alt="Meme">
                    <div class="footer-meme">
                        <p class="descricao-meme"><?= htmlspecialchars($meme->getTextoMeme()) ?></p>
                        <?php
                        if (pode('categorias.listar')):         
                        ?>

                        <div class="opcoes-meme-excluir">

                            <form action="memes/excluir.php" method="post">

                                <input type="hidden" name="id" value="<?= $meme->getId() ?>">
                                <input type="submit" value="apagar">

                            </form>


                        </div>

                        <?php endif; ?>
                        <div class="usuario-meme">
                                <span><?= htmlspecialchars($usuario->getNome()) ?></span>
                                <img class="imagem-avatar-meme" src="<?= htmlspecialchars($usuario->getAvatar()) ?>" alt="Avatar do Usuário">                            
                        </div>
                        
                    </div>
                </div>
            <?php endforeach; ?>
        </section>


        <?php if ($totalPaginas > 1): ?>
            <div class="paginacao">

                <?php if ($paginaAtual > 1): ?>
                    <a href="<?= urlPaginacao(['pagina' => $paginaAtual - 1]) ?>">Anterior</a>
                <?php endif; ?>


                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <?php if ($i == $paginaAtual): ?>
                        <span class="ativo"><?= $i ?></span>
                    <?php else: ?>
                        <a href="<?= urlPaginacao(['pagina' => $i]) ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>7


                <?php if ($paginaAtual < $totalPaginas): ?>
                    <a href="<?= urlPaginacao(['pagina' => $paginaAtual + 1]) ?>">Próximo</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </main>
</body>

</html>