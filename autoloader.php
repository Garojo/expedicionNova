<?php
spl_autoload_register(function ($className) {

    $carpetas = [
        'models/',
        'controllers/',
        'helpers/',

    ];
    

    foreach ($carpetas as $carpeta) {
        $file = __DIR__ . '/' . $carpeta . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    

    throw new Exception("Clase '$className' no encontrada. Asegúrate de que el archivo existe.");
});