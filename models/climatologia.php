<?php
class Climatologia extends EntidadEstelar implements iInteractuable {
    private float $temperaturaMedia;
    private float $presionAtmosferica;

    public function __construct(string $nombre, float $id, string $planeta, float $peligrosidad, float $temperaturaMedia, float $presionAtmosferica)
    {
        parent::__construct($nombre, $id, $planeta, $peligrosidad);
        $this->temperaturaMedia = $temperaturaMedia;
        $this->presionAtmosferica = $presionAtmosferica;
    }

    public function reaccionar(): string {
        return "La climatología de {$this->getNombre()} cambia drásticamente.";
    }

    public function obtenerDescripcion(): string {
        return "Climatología: {$this->getNombre()} - Temp: {$this->temperaturaMedia}°C";
    }
    
    public function getTemperaturaMedia(): float {
        return $this->temperaturaMedia;
    }
    
    public function getPresionAtmosferica(): float {
        return $this->presionAtmosferica;
    }
}
?>