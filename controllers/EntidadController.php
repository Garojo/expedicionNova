<?php
class EntidadController {
    private $gestor;
    
    public function __construct() {
        $this->gestor = new GestorEntidades();
    }
    
    public function index() {
        // Obtener todas las entidades
        $entidades = $this->gestor->obtenerTodos();
        
        // Configurar paginación
        $pagina = $_GET['pagina'] ?? 1;
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
        // Verificar método POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=crear');
            exit();
        }
        
        // Obtener datos
        $tipo = $_POST['tipo'] ?? '';
        $nombre = trim($_POST['nombre'] ?? '');
        $id = (float)($_POST['id'] ?? 0);
        $planeta = trim($_POST['planeta'] ?? '');
        $peligrosidad = (float)($_POST['peligrosidad'] ?? 1);
        
        // Validaciones
        if (empty($nombre) || empty($planeta) || $id <= 0 || empty($tipo)) {
            $this->mostrarError('Todos los campos son obligatorios');
            return;
        }
        
        if ($peligrosidad < 1 || $peligrosidad > 10) {
            $this->mostrarError('Peligrosidad debe estar entre 1 y 10');
            return;
        }
        
        // ¿Estamos editando?
        $editando = ($_POST['editando'] ?? 'false') === 'true';
        $idOriginal = $editando ? (float)($_POST['id_original'] ?? $id) : $id;
        
        // Crear entidad
        try {
            $entidad = $this->crearEntidad($tipo, $nombre, $id, $planeta, $peligrosidad, $_POST);
        } catch (Exception $e) {
            $this->mostrarError($e->getMessage());
            return;
        }
        
        // Guardar o actualizar
        if ($editando) {
            $this->actualizarEntidad($idOriginal, $entidad);
        } else {
            $this->crearNuevaEntidad($entidad);
        }
        
        // Redirigir
        header('Location: index.php?action=index');
        exit();
    }
    
    private function crearEntidad($tipo, $nombre, $id, $planeta, $peligrosidad, $datos) {
        switch($tipo) {
            case 'FormaDeVida':
                $dieta = trim($datos['dieta'] ?? '');
                $estructura = trim($datos['estructura_osea'] ?? '');
                
                if (empty($dieta) || empty($estructura)) {
                    throw new Exception('Los campos de Forma de Vida son obligatorios');
                }
                
                return new FormaDeVida($nombre, $id, $planeta, $peligrosidad, $dieta, $estructura);
                
            case 'Minerales':
                $composicion = trim($datos['composicion'] ?? '');
                $dureza = (float)($datos['dureza'] ?? 1);
                
                if (empty($composicion) || $dureza < 1 || $dureza > 10) {
                    throw new Exception('Composición obligatoria y dureza entre 1-10');
                }
                
                return new Minerales($nombre, $id, $planeta, $peligrosidad, $composicion, $dureza);
                
            case 'Climatologia':
                $temperatura = (float)($datos['temperatura'] ?? 0);
                $presion = (float)($datos['presion'] ?? 1013);
                
                return new Climatologia($nombre, $id, $planeta, $peligrosidad, $temperatura, $presion);
                
            default:
                throw new Exception('Tipo de entidad no válido');
        }
    }
    
    private function crearNuevaEntidad($entidad) {
        // Verificar ID único
        $existe = $this->gestor->obtenerPorId($entidad->getId());
        if ($existe) {
            $this->mostrarError('Ya existe una entidad con ID ' . $entidad->getId());
            return;
        }
        
        $this->gestor->guardar($entidad);
    }
    
    private function actualizarEntidad($idOriginal, $nuevaEntidad) {
        // Si el ID cambió, verificar que no exista
        if ($idOriginal != $nuevaEntidad->getId()) {
            $existe = $this->gestor->obtenerPorId($nuevaEntidad->getId());
            if ($existe && $existe->getId() != $idOriginal) {
                $this->mostrarError('Ya existe otra entidad con ID ' . $nuevaEntidad->getId());
                return;
            }
        }
        
        // Eliminar antigua y añadir nueva
        $this->gestor->eliminar($idOriginal);
        $this->gestor->guardar($nuevaEntidad);
    }
    
    public function editar($id) {
        $entidad = $this->gestor->obtenerPorId($id);
        
        if (!$entidad) {
            $this->mostrarError('Entidad no encontrada');
            return;
        }
        
        require_once 'views/formulario.php';
    }
    
    public function eliminar($id) {
        $this->gestor->eliminar($id);
        header('Location: index.php?action=index');
        exit();
    }
    
    private function mostrarError($mensaje) {
        echo '<div style="padding: 20px; background: #fff3cd; border: 2px solid #ffc107; border-radius: 5px; margin: 20px;">';
        echo '<h3 style="color: #856404;">⚠️ Error</h3>';
        echo '<p>' . htmlspecialchars($mensaje) . '</p>';
        echo '<a href="javascript:history.back()" style="display: inline-block; padding: 10px; background: #6c757d; color: white; text-decoration: none; border-radius: 3px;">← Volver</a>';
        echo '</div>';
    }
}
?>