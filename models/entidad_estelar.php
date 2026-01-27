<?php

abstract class EntidadEstelar
{
    protected string $nombre;
    protected float $id;
    protected string $planeta;
    protected float $peligrosidad;

    public function __construct(string $nombre, float $id, string $planeta, float $peligrosidad)
    {
        $this->nombre = $nombre;
        $this->id = $id;
        $this->planeta = $planeta;
        $this->peligrosidad = $peligrosidad;
    }

    abstract public function obtenerDescripcion(): string;

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getId(): float
    {
        return $this->id;
    }

    public function getPlaneta(): string
    {
        return $this->planeta;
    }

    public function getPeligrosidad(): float
    {
        return $this->peligrosidad;
    }
}