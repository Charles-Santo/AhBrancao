<?php

class Usuario
{
    private ?int $id;
    private string $nome;
    private string $funcao;
    private string $email;
    private string $senha;
    private string $avatar;

    public function __construct(?int $id, string $nome, string $funcao, string $email, string $senha, string $avatar)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->funcao = $funcao;
        $this->email = $email;
        $this->senha = $senha;
        $this->avatar = $avatar;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    public function getfuncao(): string
    {
        return $this->funcao;
    }

    public function setfuncao(string $funcao): void
    {
        $this->funcao = $funcao;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getSenha(): string
    {
        return $this->senha;
    }

    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }

    public function getAvatar(): string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }
}
