<?php
session_start();
$usuarioLogado = $_SESSION['usuario'] ?? null;
$erro = $_GET['erro'] ?? '';
?>

<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    
    <link href="https://fonts.googleapis.com/css2?family=Inria+Sans:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/login.css">
    <title> Login - Ah Brancão</title>
    <link rel="icon" href="img/logo-AhBrancao.png" type="image/x-icon">
</head>

<body class="config-fundo-login">
    <?php if ($usuarioLogado): ?>
        <main class="card-login">
            <section class="container-topo">
                <div class="topo-direita">
                    <p>Você já está logado como <strong><?php echo htmlspecialchars($usuarioLogado); ?></strong></p>
                    <form action="logout.php" method="post">
                        <button type="submit" class="botao-sair">Sair</button>
                    </form>
                </div>
                <div class="conteudo">
                    <a href="dashboard.php" class="link-adm">Ir para o Dashboard</a>
                </div>
            </section>
        </main>
    <?php else: ?>
        <main class="card-login">
            <h1>Login</h1>
            <div class='conteudo-login'>
                <?php if ($erro === 'credenciais'): ?>
                    <p class="mensagem-erro">Usuário ou senha incorretos.</p>
                <?php elseif ($erro === 'campos'): ?>
                    <p class="mensagem-erro">Preencha e-mail e senha.</p>
                <?php endif; ?>
                <?php if (isset($_GET['novo']) && $_GET['novo'] == 1): ?>
                    <p class="mensagem-ok">Usuário registrado. Faça login.</p>
                <?php endif; ?>

                <form action="autenticar.php" method="post">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="Digite o seu e-mail">

                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Digite a sua senha">

                    <div class="acoes-login">
                        <button type="submit" class="botao-primario">Fazer Login</button>
                        <a href="registrar.php" class="botao-secundario">Realizar Registro</a>
                    </div>
            </div>
            </form>
        </main>

        <a href="index.php">
            <img src="img/logo-AhBrancao.png" class="logo-rodape" alt="logo Ah Brancão">
        </a>
    <?php endif; ?>

    

</body>

</html>