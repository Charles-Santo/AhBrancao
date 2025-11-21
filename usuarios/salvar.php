<?php
        require __DIR__ . "/../src/conexao-bd.php";
        require __DIR__ . "/../src/Modelo/Usuario.php";
        require __DIR__ . "/../src/Repositorio/UsuarioRepositorio.php";

        $usuarioRepositorio = new UsuarioRepositorio($pdo);

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            
            $usuario = new Usuario(
                $_POST['id'] ?: null, 
                $_POST['nome'],
                $_POST['funcao'],
                $_POST['email'],
                $_POST['senha'],
                $_POST['avatar'] ?? '' 
            );
        }

        if ($usuario->getId()) {
            
            $usuarioRepositorio->atualizar($usuario);
        } else {
            
            $usuarioRepositorio->salvar($usuario);
        }

        header("Location: listar.php");
        exit();


        require __DIR__ . "/../src/conexao-bd.php";
        require __DIR__ . "/../src/Modelo/Usuario.php";
        require __DIR__ . "/../src/Repositorio/UsuarioRepositorio.php";

        $repo = new UsuarioRepositorio($pdo);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: listar.php');
            exit;
        }

        $id     = isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null;
        $nome   = trim($_POST['nome']   ?? '');
        $funcao = trim($_POST['funcao'] ?? 'User');
        $email  = trim($_POST['email']  ?? '');
        $senha  = $_POST['senha'] ?? '';
        $avatar = trim($_POST['avatar'] ?? '');

        if ($nome === '' || $email === '' || (!$id && $senha === '')) {
            header('Location: form.php' . ($id ? '?id=' . $id . '&erro=campos' : '?erro=campos'));
            exit;
        }

        if (!in_array($funcao, ['User', 'Admin'], true)) {
            $funcao = 'User';
        }

        if ($id) {

            $existente = $repo->buscar($id);
            if (!$existente) {
                header('Location: listar.php?erro=inexistente');
                exit;
            }


            if ($senha === '') {
                $senhaParaObjeto = $existente->getSenha(); 
            } else {
                $senhaParaObjeto = $senha; 
            }

            $usuario = new Usuario($id, $nome, $funcao, $email, $senhaParaObjeto, $avatar ?? '');
            $repo->atualizar($usuario);
            header('Location: listar.php?ok=1');
            exit;
        } else {
            
            $usuario = new Usuario(null, $nome, $funcao, $email, $senha, $avatar ?? ''); 
            $repo->salvar($usuario);
            header('Location: listar.php?novo=1');
            exit;
        }
