<?php

class UsuarioRepositorio
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function formarObjeto(array $d): Usuario
    {
        return new Usuario(
            isset($d['id_usuario']) ? (int)$d['id_usuario'] : null,
            $d['nome_usuario']   ?? '',
            $d['funcao_usuario'] ?? 'User',
            $d['email_usuario']  ?? '',
            $d['senha_usuario']  ?? '',
            $d['avatar_usuario'] ?? ''
        );
    }

    public function buscarTodos(): array
    {
        $sql = "SELECT id_usuario,nome_usuario,funcao_usuario,email_usuario,senha_usuario,avatar_usuario FROM tbUsuario ORDER BY email_usuario";
        $rs = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => $this->formarObjeto($r), $rs);
    }

    public function buscar(int $id): ?Usuario
    {
        $st = $this->pdo->prepare("SELECT id_usuario,nome_usuario,funcao_usuario,email_usuario,senha_usuario,avatar_usuario FROM tbUsuario WHERE id_usuario=?");
        $st->execute([$id]);
        $d = $st->fetch(PDO::FETCH_ASSOC);
        return $d ? $this->formarObjeto($d) : null;
    }


    public function buscarPorEmail(string $email): ?Usuario
    {
        $st = $this->pdo->prepare("SELECT id_usuario,nome_usuario,funcao_usuario,email_usuario,senha_usuario,avatar_usuario FROM tbUsuario WHERE email_usuario=? LIMIT 1");
        $st->bindValue(1, $email);
        $st->execute([$email]);
        $d = $st->fetch(PDO::FETCH_ASSOC);
        return $d ? $this->formarObjeto($d) : null;
    }

    public function buscarPorNome(string $nome): ?Usuario
    {
        $st = $this->pdo->prepare("SELECT id_usuario,nome_usuario,funcao_usuario,email_usuario,senha_usuario,avatar_usuario FROM tbUsuario WHERE nome_usuario=? LIMIT 1");
        $st->bindValue(1, $nome);
        $st->execute([$nome]);
        $d = $st->fetch(PDO::FETCH_ASSOC);
        return $d ? $this->formarObjeto($d) : null;
    }

    public function salvar(Usuario $usuario)
    {
        $sql = "INSERT INTO tbUsuario (nome_usuario, funcao_usuario, email_usuario, senha_usuario, avatar_usuario) VALUES (?, ?, ?, ?, ?)";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $usuario->getNome());
        $statement->bindValue(2, $usuario->getfuncao());
        $statement->bindValue(3, $usuario->getEmail());
        $statement->bindValue(4, password_hash($usuario->getSenha(), PASSWORD_DEFAULT));
        $statement->bindValue(5, $usuario->getAvatar());
        $statement->execute();
    }

    public function autenticar(string $email, string $senha): bool
    {
        $u = $this->buscarPorEmail($email);
        return $u ? password_verify($senha, $u->getSenha()) : false;
    }

    public function atualizar(Usuario $usuario)
    {
        $senha = $usuario->getSenha();

        if (!preg_match('/^\$2y\$/', $senha)) {
            $senha = password_hash($senha, PASSWORD_DEFAULT);
        }

        $sql = "UPDATE tbUsuario SET nome_usuario = ?, funcao_usuario = ?, email_usuario = ?, avatar_usuario = ?, senha_usuario = ? WHERE id_usuario = ?";
        $st = $this->pdo->prepare($sql);
        $st->execute([
            $usuario->getNome(),
            $usuario->getfuncao(),
            $usuario->getEmail(),
            $usuario->getAvatar(),
            $senha,
            $usuario->getId()
        ]);
    }

    public function deletar(int $id): bool
    {
        $st = $this->pdo->prepare("DELETE FROM tbUsuario WHERE id_usuario=?");
        return $st->execute([$id]);
    }
}
