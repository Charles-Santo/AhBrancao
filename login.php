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
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/login.css">
    <link rel="icon" href="img/" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;900&display=swap"
        rel="stylesheet">
    <title>AhBrancao - Login</title>
</head>

<body>
    <div class="bola bola1"></div>
    <div class="bola bola2"></div>
    <div class="bola bola3"></div>
    <div class="bola bola4"></div>
    <div class="bola bola5"></div>
    <div class="bola bola6"></div>
    <div class="bola bola7"></div>

    <main>
        <?php if ($usuarioLogado): ?>
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
        <?php else: ?>

            <section class="container-form">
                <div class="form-wrapper">
                    <h1>Fazer Login</h1>

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
                            <button type="submit" class="botao-primario">Entrar</button>
                            <a href="registrar.php" class="botao-secundario">Cadastrar</a>
                        </div>
                    </form>

                    <div class="footer-login">
                        <img src="img/AhBrancao.png" alt="Ah Brancão">
                    </div>
                </div>
            </section>
        <?php endif; ?>
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
