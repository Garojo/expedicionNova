<?php
class PaginaHelper {
    
    /**
     * Obtiene la página actual desde GET o sesión
     */
    public static function obtenerPaginaActual(): int {
        $pagina = $_GET['pagina'] ?? ($_SESSION['ultima_pagina'] ?? 1);
        return max(1, (int)$pagina);
    }
    
    /**
     * Guarda la página actual en sesión
     */
    public static function guardarPaginaActual(int $pagina): void {
        $_SESSION['ultima_pagina'] = $pagina;
    }
    
    /**
     * Genera URL con parámetros de página
     */
    public static function generarUrl(string $accion, array $parametros = [], ?int $pagina = null): string {
        $url = "index.php?action=$accion";
        
        // Añadir parámetros
        foreach ($parametros as $key => $value) {
            $url .= "&$key=" . urlencode($value);
        }
        
        // Añadir página si se especifica
        if ($pagina !== null) {
            $url .= "&pagina=$pagina";
        } elseif (isset($_SESSION['ultima_pagina'])) {
            $url .= "&pagina=" . $_SESSION['ultima_pagina'];
        }
        
        return $url;
    }
    
    /**
     * Redirige manteniendo la página
     */
    public static function redirigir(string $accion, array $parametros = [], ?int $pagina = null): void {
        $url = self::generarUrl($accion, $parametros, $pagina);
        header("Location: $url");
        exit();
    }
}
?>