<?php
// ======== CARGAR AUTOLOADER PRIMERO ========
require_once __DIR__ . '/autoloader.php';

// ======== CARGAR MANUALMENTE LAS CLASES CRÍTICAS ========
// Esto asegura que estén disponibles para la deserialización
$clasesCriticas = [
    'FormaDeVida',
    'Minerales', 
    'Climatologia',
    'EntidadEstelar',
    'iInteractuable',
    'IGestor',
    'GestorEntidades'
];

foreach ($clasesCriticas as $clase) {
    if (!class_exists($clase) && !interface_exists($clase)) {
        // Forzar la carga
        class_exists($clase);
    }
}

// ======== AHORA SÍ INICIAR SESIÓN ========
session_start();

// ======== INICIALIZAR SESIÓN SI NO EXISTE ========
if (!isset($_SESSION['entidades'])) {
    $_SESSION['entidades'] = [];
}

// ======== CREAR CONTROLADOR ========
$controller = new EntidadController();

// ======== MANEJAR ACCIONES ========
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
        $controller->editar($id);
        break;
    case 'eliminar':
        $controller->eliminar($id);
        break;
    default:
        $controller->index();
        break;
}