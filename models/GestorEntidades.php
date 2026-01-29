<?php
class GestorEntidades implements iGestor {
    
    public function __construct() {
        if (!isset($_SESSION['entidades'])) {
            $_SESSION['entidades'] = [];
        }
    }
    
    public function obtenerTodos(): array {
        return $_SESSION['entidades'] ?? [];
    }
    
    public function guardar(EntidadEstelar $entidad): void {
        $_SESSION['entidades'][] = $entidad;
    }
    
    public function eliminar(float $id): void {
        $_SESSION['entidades'] = array_filter(
            $_SESSION['entidades'] ?? [],
            function($entidad) use ($id) {
                return $entidad->getId() != $id;
            }
        );
    }
    
    public function editar(float $id, EntidadEstelar $entidad): void {
        foreach ($_SESSION['entidades'] as $key => $e) {
            if ($e->getId() == $id) {
                $_SESSION['entidades'][$key] = $entidad;
                break;
            }
        }
    }
    
    public function obtenerPorId(float $id): ?EntidadEstelar {
        foreach ($_SESSION['entidades'] ?? [] as $entidad) {
            if ($entidad->getId() == $id) {
                return $entidad;
            }
        }
        return null;
    }
}
?>