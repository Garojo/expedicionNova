<?php
class PaginadorHelper {
    
    public static function paginar(array $datos, int $paginaActual = 1, int $elementosPorPagina = 5): array {
        $totalElementos = count($datos);
        $totalPaginas = $totalElementos > 0 ? ceil($totalElementos / $elementosPorPagina) : 1;
        
        // Validar página actual
        if ($paginaActual < 1) {
            $paginaActual = 1;
        }
        if ($paginaActual > $totalPaginas && $totalPaginas > 0) {
            $paginaActual = $totalPaginas;
        }
        
        // Calcular índices
        $inicio = ($paginaActual - 1) * $elementosPorPagina;
        $elementosPagina = array_slice($datos, $inicio, $elementosPorPagina);
        
        return [
            'elementos' => $elementosPagina,
            'pagina_actual' => $paginaActual,
            'total_paginas' => $totalPaginas,
            'total_elementos' => $totalElementos,
            'elementos_por_pagina' => $elementosPorPagina,
            'inicio' => $inicio + 1,
            'fin' => min($inicio + $elementosPorPagina, $totalElementos)
        ];
    }
    
    /**
     * Genera los enlaces de paginación HTML
     * 
     * @param int $paginaActual
     * @param int $totalPaginas
     * @param string $urlBase URL base para los enlaces (sin parámetro pagina)
     * @return string HTML de los enlaces
     */
    public static function generarEnlaces(int $paginaActual, int $totalPaginas, string $urlBase = 'index.php'): string {
        if ($totalPaginas <= 1) {
            return '';
        }
        
        $html = '<div class="paginador">';
        
        // Botón "Primera" y "Anterior"
        if ($paginaActual > 1) {
            $html .= self::crearEnlace('&laquo; Primera', $urlBase, 1);
            $html .= self::crearEnlace('&lsaquo; Anterior', $urlBase, $paginaActual - 1);
        } else {
            $html .= '<span class="disabled">&laquo; Primera</span>';
            $html .= '<span class="disabled">&lsaquo; Anterior</span>';
        }
        
        // Números de página
        $inicio = max(1, $paginaActual - 2);
        $fin = min($totalPaginas, $paginaActual + 2);
        
        for ($i = $inicio; $i <= $fin; $i++) {
            if ($i == $paginaActual) {
                $html .= '<span class="pagina-actual">' . $i . '</span>';
            } else {
                $html .= self::crearEnlace($i, $urlBase, $i);
            }
        }
        
        // Botón "Siguiente" y "Última"
        if ($paginaActual < $totalPaginas) {
            $html .= self::crearEnlace('Siguiente &rsaquo;', $urlBase, $paginaActual + 1);
            $html .= self::crearEnlace('Última &raquo;', $urlBase, $totalPaginas);
        } else {
            $html .= '<span class="disabled">Siguiente &rsaquo;</span>';
            $html .= '<span class="disabled">Última &raquo;</span>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Crea un enlace de paginación
     */
    private static function crearEnlace(string $texto, string $urlBase, int $pagina): string {
        $url = self::construirUrl($urlBase, $pagina);
        return '<a href="' . htmlspecialchars($url) . '" class="enlace-pagina">' . $texto . '</a>';
    }
    
    /**
     * Construye la URL con parámetros
     */
    private static function construirUrl(string $urlBase, int $pagina): string {
        $url = $urlBase;
        $separador = (strpos($urlBase, '?') === false) ? '?' : '&';
        
        // Mantener todos los parámetros GET excepto 'pagina'
        $parametros = $_GET;
        unset($parametros['pagina']);
        $parametros['pagina'] = $pagina;
        
        if (!empty($parametros)) {
            $url .= $separador . http_build_query($parametros);
        }
        
        return $url;
    }
    
    /**
     * Obtiene la página actual desde $_GET
     */
    public static function obtenerPaginaActual(): int {
        $pagina = $_GET['pagina'] ?? 1;
        return max(1, (int)$pagina);
    }
}
?>