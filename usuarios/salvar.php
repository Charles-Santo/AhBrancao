<?php
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


if ($nome === '' || $email === '' || (!$id && $senha === '')) {
    header('Location: form.php' . ($id ? '?id=' . $id . '&erro=campos' : '?erro=campos'));
    exit;
}

if (!in_array($funcao, ['User', 'Admin'], true)) {
    $funcao = 'User';
}


$avatar = $_FILES['avatar'] ?? null;
$caminhoAvatar = trim($_POST['avatar'] ?? ''); 

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

    
    if ($avatar && $avatar['error'] === UPLOAD_ERR_OK) {
        $pastaUploads = __DIR__ . '/../uploads/';

        if (!is_dir($pastaUploads)) {
            mkdir($pastaUploads, 0755, true);
        }

        $nomeArquivo = uniqid() . '-' . basename($avatar['name']);
        $destino = $pastaUploads . $nomeArquivo;

        if (move_uploaded_file($avatar['tmp_name'], $destino)) {
            $caminhoAvatar = 'uploads/' . $nomeArquivo;
        }
    }

    $usuario = new Usuario($id, $nome, $funcao, $email, $senhaParaObjeto, $caminhoAvatar);
    $repo->atualizar($usuario);
    header('Location: listar.php?ok=1');
    exit;

} else {
    
    if ($avatar && $avatar['error'] === UPLOAD_ERR_OK) {
        $pastaUploads = __DIR__ . '/../uploads/';

        if (!is_dir($pastaUploads)) {
            mkdir($pastaUploads, 0755, true);
        }

        $nomeArquivo = uniqid() . '-' . basename($avatar['name']);
        $destino = $pastaUploads . $nomeArquivo;

        if (move_uploaded_file($avatar['tmp_name'], $destino)) {
            $caminhoAvatar = 'uploads/' . $nomeArquivo;
        }
    }

    $usuario = new Usuario(null, $nome, $funcao, $email, $senha, $caminhoAvatar);
    $repo->salvar($usuario);
    header('Location: listar.php?novo=1');
    exit;
}
