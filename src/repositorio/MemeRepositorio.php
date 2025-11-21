<?php

class MemeRepositorio
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function formarObjeto(array $d): Meme
    {
        return new Meme(
            isset($d['codigo_Meme']) ? (int)$d['codigo_Meme'] : null,
            $d['nome_Meme']   ?? '',
            $d['descricao_Meme']  ?? '',
            $d['imagem_Meme'] ?? ''
        );
    }

    public function buscarTodos(): array
    {
        $sql = "SELECT codigo_Meme,nome_Meme,descricao_Meme,imagem_Meme FROM tbMeme ORDER BY nome_Meme";
        $rs = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => $this->formarObjeto($r), $rs);
    }

    public function buscar(int $codigo): ? Meme
    {
        $st = $this->pdo->prepare("SELECT codigo_Meme,nome_Meme,descricao_Meme,imagem_Meme FROM tbMeme WHERE codigo_Meme=?");
        $st->execute([$codigo]);
        $d = $st->fetch(PDO::FETCH_ASSOC);
        return $d ? $this->formarObjeto($d) : null;
    }


    public function buscarPorNome(string $nome): ?Meme
    {
        $st = $this->pdo->prepare("SELECT codigo_Meme,nome_Meme,descricao_Meme,imagem_Meme FROM tbMeme WHERE nome_Meme=? LIMIT 1");
        $st->bindValue(1, $nome);
        $st->execute([$nome]);
        $d = $st->fetch(PDO::FETCH_ASSOC);
        return $d ? $this->formarObjeto($d) : null;
    }


    public function salvar(Meme $Meme)
    {
        $sql = "INSERT INTO tbMeme (nome_Meme, descricao_Meme, imagem_Meme) VALUES (?, ?, ?)";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $Meme->getNome());
        $statement->bindValue(2, $Meme->getdescricao());
        $statement->bindValue(3, $Meme->getImagem());
        $statement->execute();
    }



    public function atualizar(Meme $Meme)
    {

        $sql = "UPDATE tbMeme SET nome_Meme = ?, descricao_Meme = ?, imagem_Meme = ? WHERE codigo_Meme = ?";
        $st = $this->pdo->prepare($sql);
        $st->execute([
            $Meme->getNome(),
            $Meme->getDescricao(),
            $Meme->getImagem(),
            $Meme->getCodigo()
        ]);
    }

    public function deletar(int $codigo): bool
    {
        $st = $this->pdo->prepare("DELETE FROM tbMeme WHERE codigo_Meme=?");
        return $st->execute([$codigo]);
    }
}
