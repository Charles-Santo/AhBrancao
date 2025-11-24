<?php

$tituloPagina = 'Registrar Usuário';
$textoBotao   = 'Registrar Usuário';

?>
<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($tituloPagina) ?> - Ah Brancão</title>
    <link rel="icon" href="img/logo-AhBrancao.png" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Inria+Sans:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="css/form.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
    <main class="config-fundo-login">

        <section class="card-login">
            <div class="conteudo-login">
                <h1><?= htmlspecialchars($tituloPagina) ?></h1>
                <?php if (isset($_GET['erro']) &&   $_GET['erro'] === 'jausado'): ?>
                    <p class="mensagem-erro">O e-mail ou nome de usuario digitado já estão sendo usados.</p>
                <?php endif; ?>
                <?php if (isset($_GET['erro']) &&   $_GET['erro'] === 'campos'): ?>
                    <p class="mensagem-erro">Preencha e-mail e senha.</p>
                <?php endif; ?>
                <?php if (isset($_GET['erro']) &&   $_GET['erro'] === 'senhasDiferentes'): ?>
                    <p class="mensagem-erro">Digite Senhas Iguais.</p>
                <?php endif; ?>
                <?php if (isset($_GET['novo']) && $_GET['novo'] == 1): ?>
                    <p class="mensagem-ok">Usuário registrado. Faça login.</p>
                <?php endif; ?>
                <form action="salvarRegistro.php" method="post">
                    <input type="hidden" name="redirect" value="login">
                    <input type="hidden" name="funcao" value="User">

                    <div>
                        <label for="nome">Nome</label>
                        <input id="nome" name="nome" type="text" placeholder="Digite o seu nome">
                    </div>

                    <div>
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Digite o seu e-mail">
                    </div>

                    <div>
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" placeholder="Digite a sua senha">
                    </div>

                    <div>
                        <label for="senha">Confirmar Sua Senha</label>
                        <input type="password" id="senha" name="senhaNovamente" placeholder="Digite a sua senha novamente">
                    </div>

                    <div class="acoes-login">
                        <button type="submit" class="botao-primario"><?= htmlspecialchars($textoBotao) ?></button>
                        <a href="login.php" class="botao-secundario">Voltar</a>
                    </div>

            </div>

            </form>
        </section>

    </main>
    <img src="img/logo-AhBrancao.png" class="logo-rodape" alt="logo Ah Brancão">
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