<?php

class CategoriaRepositorio
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    private function formarObjeto(array $d): Categoria
    {
        return new Categoria(
            isset($d['codigo_categoria']) ? (int)$d['codigo_categoria'] : null,
            $d['nome_categoria']   ?? '',
            $d['descricao_categoria']  ?? '',
            $d['imagem_categoria'] ?? ''
        );
    }

    public function buscarTodos(): array
    {
        $sql = "SELECT codigo_categoria,nome_categoria,descricao_categoria,imagem_categoria FROM tbCategoria ORDER BY nome_categoria";
        $rs = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => $this->formarObjeto($r), $rs);
    }

    public function buscar(int $codigo): ?Categoria
    {
        $st = $this->pdo->prepare("SELECT codigo_categoria,nome_categoria,descricao_categoria,imagem_categoria FROM tbCategoria WHERE codigo_categoria=?");
        $st->execute([$codigo]);
        $d = $st->fetch(PDO::FETCH_ASSOC);
        return $d ? $this->formarObjeto($d) : null;
    }


    public function buscarPorNome(string $nome): ?Categoria
    {
        $st = $this->pdo->prepare("SELECT codigo_categoria,nome_categoria,descricao_categoria,imagem_categoria FROM tbCategoria WHERE nome_categoria=? LIMIT 1");
        $st->bindValue(1, $nome);
        $st->execute([$nome]);
        $d = $st->fetch(PDO::FETCH_ASSOC);
        return $d ? $this->formarObjeto($d) : null;
    }


    public function salvar(Categoria $categoria)
    {
        $sql = "INSERT INTO tbCategoria (nome_categoria, descricao_categoria, imagem_categoria) VALUES (?, ?, ?)";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $categoria->getNome());
        $statement->bindValue(2, $categoria->getdescricao());
        $statement->bindValue(3, $categoria->getImagem());
        $statement->execute();
    }



    public function atualizar(Categoria $categoria)
    {

        $sql = "UPDATE tbCategoria SET nome_categoria = ?, descricao_categoria = ?, imagem_categoria = ? WHERE codigo_categoria = ?";
        $st = $this->pdo->prepare($sql);
        $st->execute([
            $categoria->getNome(),
            $categoria->getDescricao(),
            $categoria->getImagem(),
            $categoria->getCodigo()
        ]);
    }

    public function deletar(int $codigo): bool
    {
        $st = $this->pdo->prepare("DELETE FROM tbCategoria WHERE codigo_categoria=?");
        return $st->execute([$codigo]);
    }
}
