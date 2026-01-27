<?php

    class FormaDeVida extends EntidadEstelar implements iInteractuable{
     private string $dieta;

     public function __construct(string $nombre, float $id, string $planeta, float $peligrosidad, string $dieta)
     {
         parent::__construct($nombre, $id, $planeta, $peligrosidad);
         $this->dieta = $dieta;
     }

     public function reaccionar():