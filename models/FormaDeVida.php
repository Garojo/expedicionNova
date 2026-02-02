<?php
class FormaDeVida extends EntidadEstelar implements iInteractuable {
    private string $estructuraOsea;
    private string $dieta;
    
    public function __construct(string $nombre, float $id, string $planeta, float $peligrosidad, string $dieta, string $estructuraOsea)
    {
        parent::__construct($nombre, $id, $planeta, $peligrosidad);
        $this->estructuraOsea = $estructuraOsea;
        $this->dieta = $dieta;
    }

    public function obtenerDescripcion(): string {
        return "Forma de vida {$this->getNombre()} con dieta {$this->dieta}";
    }
    
    public function reaccionar(): string {
        return "La forma de vida {$this->getNombre()} reacciona.";
    }
    
    public function getEstructuraOsea(): string { 
        return $this->estructuraOsea; 
    }
    
    public function getDieta(): string { 
        return $this->dieta; 
    }
}
?>