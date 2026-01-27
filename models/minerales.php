<?php
    class Minerales extends EntidadEstelar implements iInteractuable{
        private string $composicionQuimica;
        private float $dureza;
    
        public function __construct(string $nombre, float $id, string $planeta, float $peligrosidad, string $composicionQuimica, float $dureza)
        {
            parent::__construct($nombre, $id, $planeta, $peligrosidad);
            $this->composicionQuimica = $composicionQuimica;
            $this->dureza = $dureza;
        }
    
        public function reaccionar(): string
        {
            return "El mineral $this->nombre reacciona.";
        }
    }