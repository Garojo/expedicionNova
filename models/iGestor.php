<?php

interface iGestor
{
    public function obtenerTodos(): array;
    public function guardar(EntidadEstelar $entidad): void;
    public function eliminar(float $id): void;
    public function editar(float $id, EntidadEstelar $entidad): void;
}