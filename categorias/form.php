<?php

require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Modelo/Categoria.php';
require_once __DIR__ . '/../src/Repositorio/CategoriaRepositorio.php';

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


$repo = new CategoriaRepositorio($pdo);


$codigo = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$modoEdicao = false;
$categoria = null;

if ($codigo) {

    if (method_exists($repo, 'buscar')) {
        $categoria = $repo->buscar($codigo);
    }

    if ($categoria) {
        $modoEdicao = true;
    } else {

        header('Location: listar.php');
        exit;
    }
}


$valorNome = $modoEdicao ? $categoria->getNome() : '';
$valorDescricao = $modoEdicao ? $categoria->getDescricao() : '';
$valorImagem = $modoEdicao ? $categoria->getImagem() : '';



$tituloPagina = $modoEdicao ? 'Editar Categoria' : 'Cadastrar Categoria';
$textoBotao = $modoEdicao ? 'Salvar Alterações' : 'Cadastrar Categoria';
$actionForm = $modoEdicao ? 'salvar.php' : 'salvar.php';
?>
<!doctype html>
<html lang="pt-br">

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
        <div class="topo-esquerda">
            <span>Admin</span>
        </div>

        <div class="container-admin-banner">
            <a href="/ABrancao/dashboard.php">
                <img src="../img/logo-AhBrancao.png" alt="logo-ah-brancao">
            </a>
        </div>

        <div class="topo-direita">
            <span>Bem-vindo, <?php echo htmlspecialchars($usuarioLogado); ?></span>
            <form action="/AhBrancao/logout.php" method="post" style="display:inline;">
                <button type="submit" class="botao-sair">Logout</button>
            </form>
        </div>



    </header>
    <main class="container-principal">
        <h1>Admin</h1>
        <h2><?= htmlspecialchars($tituloPagina) ?></h2>
        <section class="container-form">
            <div class="formulario">
                <?php if (isset($_GET['erro']) &&   $_GET['erro'] === 'campos'): ?>
                    <p class="mensagem-erro">Preencha todos os campos.</p>
                <?php endif; ?>
                <form action="<?= $actionForm ?>" method="post" enctype="multipart/form-data">
                    <?php if ($modoEdicao): ?>
                        <input type="hidden" name="codigo" value="<?= (int)$categoria->getCodigo() ?>">
                    <?php endif; ?>

                    <div>
                        <label for="nome">Nome</label>
                        <input id="nome" name="nome" type="text" placeholder="Digite o Nome" value="<?= htmlspecialchars($valorNome) ?>">
                    </div>

                    <div>
                        <label for="descricao">Descrição</label>
                        <input id="descricao" name="descricao" type="text" placeholder="Digite a Descrição" value="<?= htmlspecialchars($valorDescricao) ?>">
                    </div>

                    <div>
                        <label for="imagem">Imagem</label>
                        <input id="imagem" name="imagem" type="file" placeholder="Insira a Imagem" accept="image/*" value="<?= htmlspecialchars($valorImagem) ?>">
                        <?php if (!empty($valorImagem)): ?>
                            <div style="margin-top: 10px;">
                                <p>Imagem atual:</p>
                                <img class="imagem-categoria" src="../<?= htmlspecialchars($valorImagem) ?>"
                                    alt="Imagem da categoria">
                                   
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="grupo-botoes">
                        <button type="submit" class="botao-salvar"><?= htmlspecialchars($textoBotao) ?></button>
                        <a href="listar.php" class="botao-voltar">Voltar</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <script>
        window.addEventListener('DOMContentLoaded', () => {

            const mensagens = document.querySelectorAll('.mensagem-erro, .mensagem-ok');

            mensagens.forEach(msg => {

                setTimeout(() => {
                    msg.classList.add('oculto');
                }, 5000);

                msg.addEventListener('transitionend', () => msg.remove());
            });
        });
    </script>
</body>

</html>