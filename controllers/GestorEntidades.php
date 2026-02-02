<?php
class GestorEntidades{

    public function __construct()
    {
        if (!isset($_SESSION['entidades'])) {
            $_SESSION['entidades'] = [];
        }
        
    }

    public function obtenerTodos(): array
    {
        return $_SESSION['entidades'];
    }

    public function guardar(EntidadEstelar $entidad): void
    {
        $_SESSION['entidades'][] = $entidad;
    }

    public function eliminar(float $id): void
    {
        foreach ($_SESSION['entidades'] as $key => $entidad) {
            if ($entidad->getId() === $id) {
                unset($_SESSION['entidades'][$key]);
                break;
            }
        }
    }

    public function editar( string $nombre, string $planeta, float $peligrosidad, float $id): void
    {
        foreach ($_SESSION['entidades'] as $entidad) {
            if ($entidad->getId() === $id) {
                $entidad->nombre = $nombre;
                $entidad->planeta = $planeta;
                $entidad->peligrosidad = $peligrosidad;
                break;
            }
        }
    }
}