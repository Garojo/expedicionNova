<?php
class EntidadController {
    private $gestor;
    
    public function __construct() {
        $this->gestor = new GestorEntidades();
    }
    
    public function index() {
        // Guardar página actual en sesión para futuras redirecciones
        $paginaActual = $_GET['pagina'] ?? 1;
        $_SESSION['ultima_pagina'] = $paginaActual;
        
        // Obtener todas las entidades
        $entidades = $this->gestor->obtenerTodos();
        
        // Paginación básica
        $pagina = $paginaActual;
        $porPagina = 5;
        $total = count($entidades);
        $paginas = $total > 0 ? ceil($total / $porPagina) : 1;
        
        // Validar página
        if ($pagina < 1) $pagina = 1;
        if ($pagina > $paginas && $paginas > 0) $pagina = $paginas;
        
        // Obtener elementos para esta página
        $inicio = ($pagina - 1) * $porPagina;
        $entidadesPagina = array_slice($entidades, $inicio, $porPagina);
        
        // Cargar vista
        require_once 'views/lista.php';
    }
    
    public function crear() {
        require_once 'views/formulario.php';
    }
    
    public function guardar() {
        // Validar que se envió el formulario
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // Redirigir manteniendo la página
            $pagina = $_SESSION['ultima_pagina'] ?? 1;
            header('Location: index.php?action=crear&pagina=' . $pagina);
            exit();
        }
        
        // Obtener datos del formulario
        $tipo = $_POST['tipo'] ?? '';
        $nombre = trim($_POST['nombre'] ?? '');
        $id = (float)($_POST['id'] ?? 0);
        $planeta = trim($_POST['planeta'] ?? '');
        $estabilidad = (float)($_POST['peligrosidad'] ?? 1);
        
        // Validaciones básicas
        if (empty($nombre) || empty($planeta) || $id <= 0 || empty($tipo)) {
            echo "<script>
                alert('❌ Error: Todos los campos son obligatorios');
                history.back();
            </script>";
            exit();
        }
        
        if ($estabilidad < 1 || $estabilidad > 10) {
            echo "<script>
                alert('❌ Error: Estabilidad debe estar entre 1 y 10');
                history.back();
            </script>";
            exit();
        }
        
        // ¿Estamos editando?
        $editando = ($_POST['editando'] ?? 'false') === 'true';
        $idOriginal = $editando ? (float)($_POST['id_original'] ?? $id) : $id;
        
        // Crear la entidad según el tipo
        $entidad = null;
        
        switch($tipo) {
            case 'FormaDeVida':
                $dieta = trim($_POST['dieta'] ?? '');
                $estructuraOsea = trim($_POST['estructura_osea'] ?? '');
                
                if (empty($dieta) || empty($estructuraOsea)) {
                    echo "<script>
                        alert('❌ Error: Los campos específicos de Forma de Vida son obligatorios');
                        history.back();
                    </script>";
                    exit();
                }
                
                $entidad = new FormaDeVida($nombre, $id, $planeta, $estabilidad, $dieta, $estructuraOsea);
                break;
                
            case 'Minerales':
                $composicion = trim($_POST['composicion'] ?? '');
                $dureza = (float)($_POST['dureza'] ?? 1);
                
                if (empty($composicion) || $dureza < 1 || $dureza > 10) {
                    echo "<script>
                        alert('❌ Error: Composición obligatoria y dureza entre 1-10');
                        history.back();
                    </script>";
                    exit();
                }
                
                $entidad = new Minerales($nombre, $id, $planeta, $estabilidad, $composicion, $dureza);
                break;
                
            case 'Climatologia':
                $temperatura = (float)($_POST['temperatura'] ?? 0);
                $presion = (float)($_POST['presion'] ?? 1013);
                
                $entidad = new Climatologia($nombre, $id, $planeta, $estabilidad, $temperatura, $presion);
                break;
                
            default:
                echo "<script>
                    alert('❌ Error: Tipo de entidad no válido');
                    history.back();
                </script>";
                exit();
        }
        
        // Procesar (crear o actualizar)
        if ($editando) {
            // EDICIÓN: Verificar si el ID cambió
            if ($idOriginal != $id) {
                // Verificar que el nuevo ID no exista
                $existe = $this->gestor->obtenerPorId($id);
                if ($existe && $existe->getId() != $idOriginal) {
                    echo "<script>
                        alert('⚠️ Error: Ya existe otra entidad con ID $id');
                        history.back();
                    </script>";
                    exit();
                }
            }
            
            // Eliminar la antigua y añadir la nueva
            $this->gestor->eliminar($idOriginal);
            $this->gestor->guardar($entidad);
        } else {
            // CREACIÓN: Verificar que el ID no exista
            $existe = $this->gestor->obtenerPorId($id);
            if ($existe) {
                echo "<script>
                    alert('⚠️ Error: Ya existe una entidad con ID $id');
                    history.back();
                </script>";
                exit();
            }
            
            $this->gestor->guardar($entidad);
        }
        
        // Redirigir al listado manteniendo la página
        $pagina = $_SESSION['ultima_pagina'] ?? 1;
        header('Location: index.php?action=index&pagina=' . $pagina);
        exit();
    }
    
    public function editar($id) {
        // Guardar página actual antes de editar
        $paginaActual = $_GET['pagina'] ?? ($_SESSION['ultima_pagina'] ?? 1);
        $_SESSION['pagina_edicion'] = $paginaActual;
        
        $entidad = $this->gestor->obtenerPorId($id);
        
        if (!$entidad) {
            echo "<script>
                alert('❌ Error: Entidad no encontrada');
                window.location.href = 'index.php?action=index&pagina=' + $paginaActual;
            </script>";
            exit();
        }
        
        require_once 'views/formulario.php';
    }
    
    public function eliminar($id) {
        // Guardar página actual antes de eliminar
        $paginaActual = $_GET['pagina'] ?? ($_SESSION['ultima_pagina'] ?? 1);
        
        $this->gestor->eliminar($id);
        
        // Redirigir manteniendo la página
        header('Location: index.php?action=index&pagina=' . $paginaActual);
        exit();
    }
}
?>