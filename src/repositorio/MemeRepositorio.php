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

        $categorias = $this->buscarCategoriasDoMeme($d['codigo_meme']);

        return new Meme(
            isset($d['codigo_meme']) ? (int)$d['codigo_meme'] : null,
            $d['titulo_meme']        ?? '',
            $d['texto_meme']   ?? '',
            $d['imagem_meme']      ?? '',
            $d['idioma_meme']      ?? '',
            $d['categorias_id']   ?? [],
            $d['id_usuario_autor'] ?? null
        );
    }

    public function contarTotalMemeIdioma($idioma)
    {

        $sql = "SELECT COUNT(*) as total FROM tbMeme m
            WHERE idioma_meme = ?";

        $st = $this->pdo->prepare($sql);
        $st->execute([$idioma]);
        $resultado = $st->fetch(PDO::FETCH_ASSOC);
        return (int) $resultado['total'];
    }

    public function buscarPaginadoCategoria(int $codigoCategoria, int $limite, int $offset, ?string $ordem = 'data_envio', ?string $direcao = 'DESC'): array
    {

        $colunasPermitidas = ['data_envio'];


        $ordem = in_array($ordem, $colunasPermitidas) ? $ordem : 'data_envio';
        $direcao = strtoupper($direcao) === 'DESC' ? 'DESC' : 'ASC';

        $sql = "SELECT m.codigo_meme, m.titulo_meme, m.texto_meme, m.imagem_meme, m.idioma_meme, m.id_usuario_autor, m.data_envio
            FROM tbMeme m
            JOIN tbMemeCategoria mc ON m.codigo_meme = mc.codigo_meme
            WHERE mc.codigo_categoria = ? AND m.isAprovado = 1
            ORDER BY $ordem $direcao
            LIMIT ? OFFSET ?";

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $codigoCategoria, PDO::PARAM_INT);
        $statement->bindValue(2, $limite, PDO::PARAM_INT);
        $statement->bindValue(3, $offset, PDO::PARAM_INT);
        $statement->execute();

        $memes = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($r) => $this->formarObjeto($r), $memes);
    }

    public function contarTotalMemeCategoria($codigoCategoria): int
    {

        $sql = "SELECT COUNT(*) as total FROM tbMeme m
            JOIN tbMemeCategoria mc ON m.codigo_meme = mc.codigo_meme
            WHERE mc.codigo_categoria = ? AND m.isAprovado = 1";

        $st = $this->pdo->prepare($sql);
        $st->execute([$codigoCategoria]);
        $resultado = $st->fetch(PDO::FETCH_ASSOC);
        return (int) $resultado['total'];
    }


    public function buscarCategoriaMeme(int $codigoCategoria): array
    {
        $sql = "SELECT m.codigo_meme, m.titulo_meme, m.texto_meme, m.imagem_meme, m.idioma_meme, m.id_usuario_autor 
                FROM tbMeme m
                JOIN tbMemeCategoria mc ON m.codigo_meme = mc.codigo_meme
                WHERE mc.codigo_categoria = ? AND m.isAprovado = 1
                ORDER BY m.titulo_meme";

        $st = $this->pdo->prepare($sql);
        $st->execute([$codigoCategoria]);

        $rs = $st->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($r) => $this->formarObjeto($r), $rs);
    }

    public function aprovarMeme(int $codigoMeme, int $codigoAdminAprovador): bool
    {
        $sql = "UPDATE tbMeme
                SET isAprovado = 1, codigo_admin_aprovador = ?
                WHERE codigo_meme = ?";

        $st = $this->pdo->prepare($sql);
        return $st->execute([$codigoAdminAprovador, $codigoMeme]);
    }

    public function buscarMemeIdioma(string $idioma, int $limite = 5, int $offset = 0, ?string $ordem = 'data_envio', ?string $direcao = 'DESC'): array
    {
        $colunasPermitidas = ['data_envio'];
        $ordem = in_array($ordem, $colunasPermitidas) ? $ordem : 'data_envio';
        $direcao = strtoupper($direcao) === 'DESC' ? 'DESC' : 'ASC';

        $sql = "SELECT codigo_meme, titulo_meme, texto_meme, imagem_meme, idioma_meme, id_usuario_autor, data_envio
            FROM tbMeme
            WHERE idioma_meme = ? AND isAprovado = 1
            ORDER BY $ordem $direcao
            LIMIT ? OFFSET ?";

        $st = $this->pdo->prepare($sql);
        $st->bindValue(1, $idioma, PDO::PARAM_STR);
        $st->bindValue(2, $limite, PDO::PARAM_INT);
        $st->bindValue(3, $offset, PDO::PARAM_INT);
        $st->execute();

        $rs = $st->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($r) => $this->formarObjeto($r), $rs);
    }


    public function buscarMemesPendentes(): array
    {
        $sql = "SELECT codigo_meme, titulo_meme, texto_meme, imagem_meme, idioma_meme, id_usuario_autor
                FROM tbMeme 
                WHERE isAprovado = 0 
                ORDER BY titulo_meme";

        $rs = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($r) => $this->formarObjeto($r), $rs);
    }

    public function buscarTodos(): array
    {
        $sql = "SELECT codigo_meme, titulo_meme, texto_meme, imagem_meme, idioma_meme 
                FROM tbMeme ORDER BY titulo_meme";

        $rs = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($r) => $this->formarObjeto($r), $rs);
    }

    public function buscar(int $codigo): ?Meme
    {
        $st = $this->pdo->prepare("SELECT codigo_meme, titulo_meme, texto_meme, imagem_meme, idioma_meme
                                   FROM tbMeme WHERE codigo_meme=?");
        $st->execute([$codigo]);

        $d = $st->fetch(PDO::FETCH_ASSOC);

        return $d ? $this->formarObjeto($d) : null;
    }

    public function buscarCategoriasDoMeme(int $codigoMeme): array
    {
        $sql = "SELECT codigo_categoria 
                FROM tbMemeCategoria 
                WHERE codigo_meme=?";

        $st = $this->pdo->prepare($sql);
        $st->execute([$codigoMeme]);

        return array_column($st->fetchAll(PDO::FETCH_ASSOC), 'codigo_categoria');
    }

    public function salvar(Meme $meme)
    {
        $sql = "INSERT INTO tbMeme (titulo_meme, texto_meme, imagem_meme, idioma_meme, id_usuario_autor)
                VALUES (?, ?, ?, ?, ?)";

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            $meme->getTituloMeme(),
            $meme->getTextoMeme(),
            $meme->getImagemMeme(),
            $meme->getIdiomaMeme(),
            $meme->getIdUsuarioAutor()

        ]);

        $id = $this->pdo->lastInsertId();

        $this->salvarCategorias($id, $meme->getCategoriasId());
    }

    public function atualizar(Meme $meme)
    {
        $sql = "UPDATE tbMeme
                SET titulo_meme=?, texto_meme=?, imagem_meme=?, idioma_meme=?
                WHERE codigo_meme=?";

        $st = $this->pdo->prepare($sql);
        $st->execute([
            $meme->getTituloMeme(),
            $meme->getTextoMeme(),
            $meme->getImagemMeme(),
            $meme->getIdiomaMeme(),
            $meme->getId()
        ]);

        $this->atualizarCategorias($meme->getId(), $meme->getCategoriasId());
    }

    private function salvarCategorias(int $codigoMeme, array $categorias)
    {
        if (empty($categorias)) return;

        $sql = "INSERT INTO tbMemeCategoria (codigo_meme, codigo_categoria)
                VALUES (?, ?)";

        $st = $this->pdo->prepare($sql);

        foreach ($categorias as $cat) {
            $st->execute([$codigoMeme, $cat]);
        }
    }

    private function atualizarCategorias(int $codigoMeme, array $categorias)
    {

        $del = $this->pdo->prepare("DELETE FROM tbMemeCategoria WHERE codigo_meme=?");
        $del->execute([$codigoMeme]);


        $this->salvarCategorias($codigoMeme, $categorias);
    }


    public function deletar(int $codigo): bool
    {
        $this->pdo->prepare("DELETE FROM tbMemeCategoria WHERE codigo_meme=?")
            ->execute([$codigo]);

        $st = $this->pdo->prepare("DELETE FROM tbMeme WHERE codigo_meme=?");
        return $st->execute([$codigo]);
    }
}
