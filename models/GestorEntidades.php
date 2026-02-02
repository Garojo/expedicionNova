<?php
class GestorEntidades implements IGestor {
    
    public function __construct() {
        // NO iniciar sesión aquí
        // Solo asegurar que el array existe
        if (!isset($_SESSION['entidades'])) {
            $_SESSION['entidades'] = [];
        }
        
        // Reparar objetos incompletos en la sesión
        $this->repararObjetosSesion();
    }
    
    /**
     * Repara objetos serializados que pueden estar incompletos
     */
    private function repararObjetosSesion() {
        if (!isset($_SESSION['entidades']) || !is_array($_SESSION['entidades'])) {
            return;
        }
        
        $reparados = [];
        foreach ($_SESSION['entidades'] as $entidad) {
            // Si es un objeto "incompleto" (__PHP_Incomplete_Class)
            if (is_object($entidad) && get_class($entidad) === '__PHP_Incomplete_Class') {
                // Intentar reparar serializando y deserializando con clases cargadas
                $serializado = serialize($entidad);
                $entidadReparada = unserialize($serializado);
                
                if (is_object($entidadReparada) && !($entidadReparada instanceof __PHP_Incomplete_Class)) {
                    $reparados[] = $entidadReparada;
                } else {
                    // Si no se puede reparar, omitir
                    continue;
                }
            } else {
                $reparados[] = $entidad;
            }
        }
        
        $_SESSION['entidades'] = $reparados;
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
                return is_object($entidad) && $entidad->getId() != $id;
            }
        );
        
        // Reindexar
        $_SESSION['entidades'] = array_values($_SESSION['entidades']);
    }
    
    public function editar(float $id, EntidadEstelar $entidad): void {
        foreach ($_SESSION['entidades'] as $key => $e) {
            if (is_object($e) && $e->getId() == $id) {
                $_SESSION['entidades'][$key] = $entidad;
                break;
            }
        }
    }
    
    public function obtenerPorId(float $id): ?EntidadEstelar {
        foreach ($_SESSION['entidades'] ?? [] as $entidad) {
            if (is_object($entidad) && $entidad->getId() == $id) {
                return $entidad;
            }
        }
        return null;
    }
}
?>