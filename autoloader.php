<?php
spl_autoload_register(function ($className) {
    // Mapa de clases → archivos (para manejar mayúsculas/minúsculas)
    $mapaClases = [
        'EntidadEstelar'    => 'models/EntidadEstelar.php',
        'FormaDeVida'       => 'models/FormaDeVida.php',
        'Minerales'         => 'models/Minerales.php',
        'Climatologia'      => 'models/Climatologia.php',
        'IInteractuable'    => 'models/IInteractuable.php',
        'IGestor'           => 'models/IGestor.php',
        'GestorEntidades'   => 'models/GestorEntidades.php',
        'EntidadController' => 'controllers/EntidadController.php',
    ];
    
    // 1. Buscar en el mapa primero
    if (isset($mapaClases[$className])) {
        $ruta = __DIR__ . '/' . $mapaClases[$className];
        if (file_exists($ruta)) {
            require_once $ruta;
            return true;
        }
    }
    
    // 2. Buscar en carpetas estándar
    $carpetas = ['models/', 'controllers/', 'helpers/', ''];
    
    foreach ($carpetas as $carpeta) {
        $ruta = __DIR__ . '/' . $carpeta . $className . '.php';
        if (file_exists($ruta)) {
            require_once $ruta;
            return true;
        }
    }
    
    // 3. Si no encuentra, intentar convertir a minúsculas
    foreach ($carpetas as $carpeta) {
        $ruta = __DIR__ . '/' . $carpeta . strtolower($className) . '.php';
        if (file_exists($ruta)) {
            require_once $ruta;
            return true;
        }
    }
    
    // Solo log error, no morir (para permitir deserialización)
    error_log("Autoloader: Clase no encontrada - $className");
    return false;
});
?>