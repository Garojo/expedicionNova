<?php
class EntidadController {
    private $gestor;
    
    public function __construct() {
        $this->gestor = new GestorEntidades();
    }
    
    public function index() {

        $entidades = $this->gestor->obtenerTodos();
        

        $pagina = $_GET['pagina'] ?? 1;
        $porPagina = 5;
        $total = count($entidades);
        $paginas = ceil($total / $porPagina);
        

        if ($pagina < 1) $pagina = 1;
        if ($pagina > $paginas && $paginas > 0) $pagina = $paginas;
        
        $inicio = ($pagina - 1) * $porPagina;
        $entidadesPagina = array_slice($entidades, $inicio, $porPagina);
        

        require_once 'views/lista.php';
    }
    
    public function crear() {
        require_once 'views/formulario.php';
    }
    
    public function guardar() {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=crear');
            exit();
        }

        $tipo = $_POST['tipo'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $id = (float)($_POST['id'] ?? 0);
        $planeta = $_POST['planeta'] ?? '';
        $peligrosidad = (float)($_POST['peligrosidad'] ?? 1);
        

        $entidad = null;
        
        switch($tipo) {
            case 'FormaDeVida':
                $entidad = new FormaDeVida(
                    $nombre, $id, $planeta, $peligrosidad,
                    $_POST['dieta'] ?? '',
                    $_POST['estructura_osea'] ?? ''
                );
                break;
                
            case 'Minerales':
                $entidad = new Minerales(
                    $nombre, $id, $planeta, $peligrosidad,
                    $_POST['composicion'] ?? '',
                    (float)($_POST['dureza'] ?? 1)
                );
                break;
                
            case 'Climatologia':
                $entidad = new Climatologia(
                    $nombre, $id, $planeta, $peligrosidad,
                    (float)($_POST['temperatura'] ?? 0),
                    (float)($_POST['presion'] ?? 1013)
                );
                break;
                
            default:
                die("Error: Tipo de entidad no válido");
        }
        

        $this->gestor->guardar($entidad);
        

        header('Location: index.php?action=index');
        exit();
    }
    
    public function editar($id) {
        $entidad = $this->gestor->obtenerPorId($id);
        
        if (!$entidad) {
            die("Error: Entidad no encontrada");
        }
        
        require_once 'views/formulario.php';
    }
    
    public function actualizar($id) {
 
        header('Location: index.php?action=index');
        exit();
    }
    
    public function eliminar($id) {
        $this->gestor->eliminar($id);
        header('Location: index.php?action=index');
        exit();
    }
}
?>