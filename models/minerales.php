<?php
class Minerales extends EntidadEstelar implements iInteractuable {
    private string $composicionQuimica;
    private float $dureza;
    
    public function __construct(string $nombre, float $id, string $planeta, float $peligrosidad, string $composicionQuimica, float $dureza)
    {
        parent::__construct($nombre, $id, $planeta, $peligrosidad);
        $this->composicionQuimica = $composicionQuimica;
        $this->dureza = $dureza;
    }
    
    public function reaccionar(): string {
        return "El mineral {$this->getNombre()} reacciona.";
    }

    public function obtenerDescripcion(): string {
        return "Mineral: {$this->getNombre()} - Dureza: {$this->dureza}";
    }
    
    public function getComposicionQuimica(): string {
        return $this->composicionQuimica;
    }
    
    public function getDureza(): float {
        return $this->dureza;
    }
}
?>