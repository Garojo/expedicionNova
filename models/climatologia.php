<?php

class Climatologia extends EntidadEstelar implements iInteractuable{
    private string $tipoClima;
    private float $temperatura;

    public function __construct(string $nombre, float $id, string $planeta, float $peligrosidad, string $tipoClima, float $temperatura)
    {
        parent::__construct($nombre, $id, $planeta, $peligrosidad);
        $this->tipoClima = $tipoClima;
        $this->temperatura = $temperatura;
    }

    public function reaccionar(): string
    {
        return "La climatología de $this->nombre reacciona.";
    }
}
