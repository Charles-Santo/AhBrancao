<?php

class Memes{

    private ?int $id;
    private String $tituloMeme;
    private String $textoMeme;
    private bool $isAprovado;
    private string $imagemMeme;
    private string $idiomaMeme;
    private ?int $codigoAdminAprovador;
    private ?int $idUsuarioAutor;

    
    public function __construct(?int $id, string $tituloMeme, string $textoMeme, string $imagemMeme, string $idiomaMeme, string $avata)
    {
        $this->id = $id;
        $this->tituloMeme = $tituloMeme;
        $this->textoMeme = $textoMeme;
        $this->imagemMeme = $imagemMeme;
        $this->idiomaMeme = $idiomaMeme;
        // terminar
    }



}



?>