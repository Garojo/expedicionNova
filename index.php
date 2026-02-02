<?php
// Activar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ======== 1. CARGAR AUTOLOADER PRIMERO ========
require_once __DIR__ . '/autoloader.php';

// ======== 2. CARGAR MANUALMENTE LAS CLASES CRÍTICAS ========
// Esto asegura que estén disponibles para la deserialización
$clasesCriticas = [
    'EntidadEstelar',
    'FormaDeVida',
    'Minerales',
    'Climatologia',
    'IInteractuable',
    'IGestor',
    'GestorEntidades',
    'EntidadController'
];

foreach ($clasesCriticas as $clase) {
    // Forzar la carga de cada clase
    if (!class_exists($clase) && !interface_exists($clase)) {
        // El autoloader se encargará
        class_exists($clase);
    }
}

// ======== 3. AHORA SÍ INICIAR SESIÓN ========
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ======== 4. INICIALIZAR SESIÓN SI NO EXISTE ========
if (!isset($_SESSION['entidades'])) {
    $_SESSION['entidades'] = [];
}

// ======== 5. CREAR CONTROLADOR ========
try {
    $controller = new EntidadController();
} catch (Error $e) {
    die("❌ Error creando controlador: " . $e->getMessage());
}

// ======== 6. MANEJAR ACCIONES ========
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'index':
        $controller->index();
        break;
    case 'crear':
        $controller->crear();
        break;
    case 'guardar':
        $controller->guardar();
        break;
    case 'editar':
        if ($id) {
            $controller->editar($id);
        } else {
            header('Location: index.php');
        }
        break;
    case 'eliminar':
        if ($id) {
            $controller->eliminar($id);
        } else {
            header('Location: index.php');
        }
        break;
    default:
        $controller->index();
}
?>