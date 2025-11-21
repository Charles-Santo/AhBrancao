<?php

class Meme
{
    private ?int $id;
    private string $tituloMeme;
    private string $textoMeme;
    private bool $isAprovado;
    private string $imagemMeme;
    private string $idiomaMeme;
    private ?int $codigoAdminAprovador;
    private ?int $idUsuarioAutor;

    public function __construct(?int $id, string $tituloMeme, string $textoMeme, string $imagemMeme, string $idiomaMeme)
    {
        $this->id = $id;
        $this->tituloMeme = $tituloMeme;
        $this->textoMeme = $textoMeme;
        $this->imagemMeme = $imagemMeme;
        $this->idiomaMeme = $idiomaMeme;
        $this->isAprovado = false;
        $this->codigoAdminAprovador = null;
        $this->idUsuarioAutor = null;
    }

    // GETTERS
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTituloMeme(): string
    {
        return $this->tituloMeme;
    }

    public function getTextoMeme(): string
    {
        return $this->textoMeme;
    }

    public function isAprovado(): bool
    {
        return $this->isAprovado;
    }

    public function getImagemMeme(): string
    {
        return $this->imagemMeme;
    }

    public function getIdiomaMeme(): string
    {
        return $this->idiomaMeme;
    }

    public function getCodigoAdminAprovador(): ?int
    {
        return $this->codigoAdminAprovador;
    }

    public function getIdUsuarioAutor(): ?int
    {
        return $this->idUsuarioAutor;
    }

    // SETTERS
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setTituloMeme(string $tituloMeme): void
    {
        $this->tituloMeme = $tituloMeme;
    }

    public function setTextoMeme(string $textoMeme): void
    {
        $this->textoMeme = $textoMeme;
    }

    public function setAprovado(bool $isAprovado): void
    {
        $this->isAprovado = $isAprovado;
    }

    public function setImagemMeme(string $imagemMeme): void
    {
        $this->imagemMeme = $imagemMeme;
    }

    public function setIdiomaMeme(string $idiomaMeme): void
    {
        $this->idiomaMeme = $idiomaMeme;
    }

    public function setCodigoAdminAprovador(?int $codigoAdminAprovador): void
    {
        $this->codigoAdminAprovador = $codigoAdminAprovador;
    }

    public function setIdUsuarioAutor(?int $idUsuarioAutor): void
    {
        $this->idUsuarioAutor = $idUsuarioAutor;
    }
}
