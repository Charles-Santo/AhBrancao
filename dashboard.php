<?php

session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}
$usuarioLogado = $_SESSION['usuario'] ?? null;
if (!$usuarioLogado) {
    header('Location: login.php');
    exit;
}


function pode(string $perm): bool
{
    return in_array($perm, $_SESSION['permissoes'] ?? [], true);
}
?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Ah Brancão</title>
    <link rel="icon" href="img/logo-AhBrancao.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inria+Sans:wght@300;400;700&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body class="pagina-dashboard">
    <header class="container-cabecalho">
        <div class="topo-esquerda">
            <span>Admin</span>
        </div>

        <div class="container-admin-banner">
            <a href="dashboard.php">
                <img src="img\logo-AhBrancao.png" alt="logo-ah-brancao">
            </a>
        </div>

        <div class="topo-direita">
            <span>Bem-vindo, <?php echo htmlspecialchars($usuarioLogado); ?></span>
            <form action="logout.php" method="post" style="display:inline;">
                <button type="submit" class="botao-sair">Logout</button>
            </form>
        </div>
    </header>
    <main class="dashboard-container">
        <h1 class="titulo-dashboard">Dashboard</h1>
        <div class="container-opcoes">
            <section class="cards-container">

                <?php if (pode('usuarios.listar')): ?>
                    <div class="container-card">
                        <a class="card-opcao" href="usuarios/listar.php">

                            <img src="img/icone-usuarios.png" alt="Ícone de Usuários">
                        </a>
                        <h2>Gerenciar Usuarios</h2>
                    </div>
                <?php endif; ?>

                <?php if (pode('memes.listar')): ?>
                    <div class="container-card">
                        <a class="card-opcao" href="memes/listar.php">

                            <Img src="img/icone-avaliar.png" alt="icone de Avaliar Meme"></Img>
                        </a>
                        <h2>Avaliar Memes Enviados</h2>
                    </div>
                <?php endif; ?>


                <?php if (pode('categorias.listar')): ?>
                    <div class="container-card">
                        <a class="card-opcao" href="categorias/listar.php">

                            <img src="img/icone-categoria.png" alt="icone de Categorias">
                        </a>
                        <h2>Gerenciar Categorias</h2>
                    </div>
                <?php endif; ?>


                <?php if (pode('acessoNormal.listar')): ?>
                    <div class="container-card">
                        <a class="card-opcao" href="memesVer/listar.php">

                            <img src="img/icone-acesso.png" alt="icone de Acesso">
                        </a>
                        <h2>Ver Memes</h2>
                    </div>
                <?php endif; ?>
        </div>
        </section>
        </div>
    </main>
</body>

</html>