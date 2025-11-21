<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
require __DIR__ . "/../src/conexao-bd.php";
require __DIR__ . "/../src/Modelo/Usuario.php";
require __DIR__ . "/../src/Repositorio/UsuarioRepositorio.php";

$usuarioLogado = $_SESSION['usuario'] ?? null;
if (!$usuarioLogado) {
    header('Location: login.php');
    exit;
}

$usuarioRepositorio = new UsuarioRepositorio($pdo);
$usuarios = $usuarioRepositorio->buscarTodos();
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="../img/logo-AhBrancao.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inria+Sans:wght@300;400;700&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="../css/reset.css">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/form.css">
    <link rel="stylesheet" href="../css/login.css">
    
    <title>Ah Brancão - Usuários</title>
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
            <span>Bem-vindo, <?php echo htmlspecialchars($usuarioLogado); ?></span>
            <form action="/ABrancao/logout.php" method="post" style="display:inline;">
                <button type="submit" class="botao-sair">Logout</button>
            </form>
        </div>
    </header>
    <main class="container-principal">
        <h1>Admin</h1>
        <h2>Usuários</h2>
        
        <section class="container-table">

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Função</th>
                        <th>Email</th>
                        <th>Avatar</th>

                        <th colspan="2">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario->getId()) ?></td>
                            <td><?= htmlspecialchars($usuario->getNome()) ?></td>
                            <td><?= htmlspecialchars($usuario->getfuncao()) ?></td>
                            <td><?= htmlspecialchars($usuario->getEmail()) ?></td>
                            <td><?= htmlspecialchars($usuario->getAvatar()) ?></td>
                            <td><a class="botao-editar" href="form.php?id=<?= $usuario->getId() ?>">Editar</a></td>
                            <td>
                                <form action="excluir.php" method="post">
                                    <input type="hidden" name="id" value="<?= $usuario->getId() ?>">
                                    <input type="submit" class="botao-excluir" value="Excluir">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a class="botao-cadastrar" href="form.php">Cadastrar usuário</a>
        </section>
    </main>
</body>

</html>