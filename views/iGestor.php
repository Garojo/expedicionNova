<?php

public interface iGestor
{
    public function obtenerTodos(): array;
    public function guardar(EntidadEstelar $entidad): void;
    public function eliminar(float $id): void;
}