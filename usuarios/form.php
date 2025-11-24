<?php
require_once __DIR__ . '/../src/Modelo/Usuario.php';
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

require_once __DIR__ . '/../src/conexao-bd.php';
require_once __DIR__ . '/../src/Repositorio/UsuarioRepositorio.php';

$repo = new UsuarioRepositorio($pdo);


$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$modoEdicao = false;
$usuario = null;

if ($id) {
    if (method_exists($repo, 'buscar')) {
        $usuario = $repo->buscar($id);
    }

    if ($usuario) {
        $modoEdicao = true;
    } else {
        header('Location: listar.php');
        exit;
    }
}


$valorNome   = $modoEdicao ? $usuario->getNome() : '';
$valorFuncao = $modoEdicao ? $usuario->getFuncao() : '';
$valorEmail  = $modoEdicao ? $usuario->getEmail() : '';
$valorSenha  = $modoEdicao ? $usuario->getSenha() : '';
$valorAvatar = $modoEdicao ? $usuario->getAvatar() : '';

$tituloPagina = $modoEdicao ? 'Editar Usuário' : 'Cadastrar Usuário';
$textoBotao   = $modoEdicao ? 'Salvar Alterações' : 'Cadastrar Usuário';
$actionForm   = 'salvar.php';
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
            <a href="../dashboard.php">
                <img src="../img/logo-AhBrancao.png" alt="logo-ah-brancao" class="logo-header">
            </a>
        </div>

        <div class="topo-direita">
            <span>Bem-vindo, <?= htmlspecialchars($usuarioLogado->getNome()) ?></span>
            <a href="editar.php?id= <?= $usuarioLogado->getId() ?>">
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
        <h2><?= htmlspecialchars($tituloPagina) ?></h2>
        <section class="container-form">
            <div class="formulario">
                <?php if (isset($_GET['erro']) && $_GET['erro'] === 'campos'): ?>
                    <p class="mensagem-erro">Preencha todos os campos.</p>
                <?php endif; ?>
                <form action="<?= $actionForm ?>" method="post" class="form-produto" enctype="multipart/form-data">
                    <?php if ($modoEdicao): ?>
                        <input type="hidden" name="id" value="<?= (int)$usuario->getId() ?>">
                    <?php endif; ?>

                    <div>
                        <label for="nome">Nome</label>
                        <input id="nome" name="nome" type="text" value="<?= htmlspecialchars($valorNome) ?>">
                    </div>

                    <div>
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="<?= htmlspecialchars($valorEmail) ?>">
                    </div>

                    <div>
                        <label for="senha">Senha</label>
                        <input id="senha" name="senha" type="password" value="<?= htmlspecialchars($valorSenha) ?>">
                    </div>

                    <div>
                        <label for="avatar">Avatar</label>
                        <input id="avatar" name="avatar" type="file" placeholder="Insira a Imagem" accept="image/*" value="<?= htmlspecialchars($valorAvatar) ?>">
                        <?php if (!empty($valorAvatar)): ?>
                            <div style="margin-top: 10px;">
                                <p>Avatar atual:</p>
                                <img class="imagem-lista" src="../<?= htmlspecialchars($valorAvatar) ?>"
                                    alt="Imagem do avatar">

                            </div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label>Função</label>
                        <label>
                            <input type="radio" name="funcao" value="User" <?= $valorFuncao === 'User' ? 'checked' : '' ?>> Usuário Comum
                        </label>
                        <br>
                        <label>
                            <input type="radio" name="funcao" value="Admin" <?= $valorFuncao === 'Admin' ? 'checked' : '' ?>> Usuário Administrador
                        </label>
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