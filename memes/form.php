<?php

require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Modelo/Meme.php';
require_once __DIR__ . '/../src/Repositorio/MemeRepositorio.php';
require_once __DIR__ . '/../src/Repositorio/CategoriaRepositorio.php';
require_once __DIR__ . '/../src/Modelo/Categoria.php';
require __DIR__ . "/../src/Modelo/Usuario.php";

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
    exit;
}

$usuarioLogado = $_SESSION['usuario'] ?? null;
if (!$usuarioLogado) {
    header('Location: login.php');
    exit;
}

$repoMeme = new MemeRepositorio($pdo);
$repoCategoria = new CategoriaRepositorio($pdo);

$listaCategorias = $repoCategoria->buscarTodos();

$codigo = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$modoEdicao = false;
$meme = null;

if ($codigo) {
    if (method_exists($repoMeme, 'buscar')) {
        $meme = $repoMeme->buscar($codigo);
    }

    if ($meme) {
        $modoEdicao = true;
    } else {
        header('Location: listar.php');
        exit;
    }
}

function pode(string $perm): bool
{
    return in_array($perm, $_SESSION['permissoes'] ?? [], true);
}

$valorNome = $modoEdicao ? $meme->getTituloMeme() : '';
$valorDescricao = $modoEdicao ? $meme->getTextoMeme() : '';
$valorImagem = $modoEdicao ? $meme->getImagemMeme() : '';
$valorIdioma = $modoEdicao ? $meme->getIdiomaMeme() : '';
$categoriasSelecionadas = $modoEdicao ? $meme->getCategoriasId() : [];

$tituloPagina = $modoEdicao ? 'Editar Meme' : 'Cadastrar Meme';
$textoBotao = $modoEdicao ? 'Salvar Alterações' : 'Cadastrar Meme';
$actionForm = 'salvar.php';
?>
<!doctype html>
<html lang="Português">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloPagina) ?> - Ah Brancão</title>
    <link rel="icon" href="../img/logo-AhBrancao.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inria+Sans:wght@300;400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <header class="container-cabecalho">
        <?php if (pode('categorias.listar')): ?>
            <div class="topo-esquerda">
                <span>Admin</span>
            </div>
        <?php endif; ?>

        <div class="container-admin-banner">
            <a href="../dashboard.php">
                <img src="../img/logo-AhBrancao.png" alt="logo-ah-brancao" class="logo-header">
            </a>
        </div>

        <div class="topo-direita">
            <span>Bem-vindo, <?= htmlspecialchars($usuarioLogado->getNome()); ?></span>
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
        
        <h2><?= htmlspecialchars($tituloPagina) ?></h2>

        <section class="container-form">
            <div class="formulario">
                <?php if (isset($_GET['erro']) && $_GET['erro'] === 'campos'): ?>
                    <p class="mensagem-erro">Preencha todos os campos.</p>
                <?php endif; ?>

                <form action="<?= $actionForm ?>" method="post" enctype="multipart/form-data">
                    <?php if ($modoEdicao): ?>
                        <input type="hidden" name="codigo" value="<?= (int)$meme->getId() ?>">
                    <?php endif; ?>

                    <div>
                        <label for="nome">Titulo</label>
                        <input id="nome" name="nome" type="text" placeholder="Digite o Nome"
                            value="<?= htmlspecialchars($valorNome) ?>">
                    </div>

                    <div>
                        <label for="descricao">Texto</label>
                        <input id="descricao" name="descricao" type="text" placeholder="Digite a Descrição"
                            value="<?= htmlspecialchars($valorDescricao) ?>">
                    </div>

                    <div>
                        <label for="imagem">Imagem</label>
                        <input id="imagem" name="imagem" type="file" accept="image/*">
                        <?php if (!empty($valorImagem)): ?>
                            <div style="margin-top: 10px;">
                                <p>Imagem atual:</p>
                                <img class="imagem-Meme" src="../<?= htmlspecialchars($valorImagem) ?>" alt="Imagem da Meme">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div>

                        <label for="idioma">Idioma</label>
                        <select id="idioma" name="idioma">
                            <option value="Português" <?= $valorIdioma === 'Português' ? 'selected' : '' ?>>Português (Brasil)</option>
                            <option value="Inglês" <?= $valorIdioma === 'Inglês' ? 'selected' : '' ?>>Inglês (EUA)</option>
                            <option value="Espanhol" <?= $valorIdioma === 'Espanhol' ? 'selected' : '' ?>>Espanhol (Espanha)</option>
                        </select>

                    </div>
                    <div>

                        <label for="categorias">Categorias</label>
                        <select id="categorias" name="categorias[]" multiple size="5">
                            <?php foreach ($listaCategorias as $categoria): ?>
                                <option value="<?= $categoria->getCodigo() ?>"
                                    <?= in_array($categoria->getCodigo(), $categoriasSelecionadas) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($categoria->getNome()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="grupo-botoes">
                        <button type="submit" class="botao-salvar"><?= htmlspecialchars($textoBotao) ?></button>
                        <a href="../index.php" class="botao-voltar">Voltar</a>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const mensagens = document.querySelectorAll('.mensagem-erro, .mensagem-ok');
            mensagens.forEach(msg => {
                setTimeout(() => msg.classList.add('oculto'), 5000);
                msg.addEventListener('transitionend', () => msg.remove());
            });
        });
    </script>
</body>

</html>