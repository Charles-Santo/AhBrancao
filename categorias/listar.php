<?php

session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}


require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Categoria.php";
require __DIR__ . "/../src/Repositorio/CategoriaRepositorio.php";

$usuarioLogado = $_SESSION['usuario'] ?? null;
if (!$usuarioLogado) {
    header('Location: login.php');
    exit;
}

$categoriaRepositorio = new CategoriaRepositorio($pdo);
$categorias = $categoriaRepositorio->buscarTodos();
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
    <link rel="icon" href="../img/logo-AhBrancao.png" type="image/x-icon">
    
    <title>Ah Brancão - Categoria</title>
</head>

<body>

    <header class="container-cabecalho">
        <div class="topo-esquerda">
            <span>Admin</span>
        </div>

        <div class="container-admin-banner">
            <a href="../dashboard.php">
                <img src="../img/logo-AhBrancao.png" alt="logo-ah-brancao">
            </a>
        </div>

        <div class="topo-direita">
            <form action="../memes/form.php" method="post" style="display:inline;">
                <button type="submit" class="botao-publicar-memes">Publicar memes</button>
            </form>
            <span>Bem-vindo, <?php echo htmlspecialchars($usuarioLogado); ?></span>
            <form action="/AhBrancao/logout.php" method="post" style="display:inline;">
                <button type="submit" class="botao-sair">Logout</button>
            </form>
        </div>
    </header>
    <main class="container-principal">
        <h1>Admin</h1>
        <h2>Categoria</h2>
        
        <section class="container-table">

            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Descrição</th>
                        <th>Imagem</th>

                        <th colspan="2">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $categoria): ?>
                        <tr>
                            <td><?= htmlspecialchars($categoria->getCodigo()) ?></td>
                            <td><?= htmlspecialchars($categoria->getNome()) ?></td>
                            <td><?= htmlspecialchars($categoria->getDescricao()) ?></td>
                            <td><img class="imagem-categoria" src="../<?= htmlspecialchars($categoria->getImagem()) ?>"
                                    alt="Imagem da categoria"></td>
                            <td><a class="botao-editar" href="form.php?id=<?= $categoria->getCodigo() ?>">Editar</a></td>
                            <td>
                                <form action="excluir.php" method="post">
                                    <input type="hidden" name="id" value="<?= $categoria->getCodigo() ?>">
                                    <input type="submit" class="botao-excluir" value="Excluir">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a class="botao-cadastrar" href="form.php">Cadastrar Categoria</a>
        </section>
    </main>
</body>

</html>