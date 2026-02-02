<?php
// ======== 1. CARGAR CLASES PRIMERO (IMPORTANTE) ========
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar autoloader primero
require_once __DIR__ . '/autoloader.php';

// Forzar carga de todas las clases necesarias
$clases = [
    'EntidadEstelar',
    'FormaDeVida',
    'Minerales',
    'Climatologia',
    'IInteractuable',
    'IGestor',
    'GestorEntidades'
];

foreach ($clases as $clase) {
    // Forzar la carga de cada clase
    if (!class_exists($clase) && !interface_exists($clase)) {
        class_exists($clase);
    }
}

// ======== 2. AHORA SÍ INICIAR SESIÓN ========
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo "<h1>🔍 DEBUG - Expedición Nova</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Sesión ID: " . session_id() . "</p>";

echo "<h2>👽 Entidades en sesión</h2>";

if (isset($_SESSION['entidades']) && is_array($_SESSION['entidades'])) {
    $total = count($_SESSION['entidades']);
    echo "<p>Total entidades: $total</p>";
    
    // Función para verificar y reparar objetos incompletos
    function verificarYRepararEntidad($entidad, $indice) {
        echo "<hr>";
        echo "<p><strong>Entidad #$indice:</strong></p>";
        
        // Verificar si es un objeto incompleto
        if (!is_object($entidad)) {
            echo "<p style='color: red'>❌ No es un objeto (es " . gettype($entidad) . ")</p>";
            return;
        }
        
        $clase = get_class($entidad);
        
        if ($clase === '__PHP_Incomplete_Class') {
            echo "<p style='color: orange'>⚠️ Objeto incompleto (__PHP_Incomplete_Class)</p>";
            
            // Intentar reparar
            echo "<p>Intentando reparar... ";
            $serializado = serialize($entidad);
            $reparado = @unserialize($serializado);
            
            if (is_object($reparado) && get_class($reparado) !== '__PHP_Incomplete_Class') {
                echo "<span style='color: green'>✅ REPARADO -> " . get_class($reparado) . "</span></p>";
                
                // Actualizar en sesión
                $_SESSION['entidades'][$indice] = $reparado;
                
                // Mostrar info de la entidad reparada
                mostrarInfoEntidad($reparado);
            } else {
                echo "<span style='color: red'>❌ No se pudo reparar</span></p>";
                echo "<pre>";
                var_dump($entidad);
                echo "</pre>";
            }
        } else {
            echo "<p style='color: green'>✅ Clase válida: $clase</p>";
            mostrarInfoEntidad($entidad);
        }
    }
    
    // Función para mostrar info de una entidad válida
    function mostrarInfoEntidad($entidad) {
        echo "<ul>";
        
        try {
            echo "<li>ID: " . $entidad->getId() . "</li>";
        } catch (Error $e) {
            echo "<li>ID: <span style='color: red'>Error - " . $e->getMessage() . "</span></li>";
        }
        
        try {
            echo "<li>Nombre: " . htmlspecialchars($entidad->getNombre()) . "</li>";
        } catch (Error $e) {
            echo "<li>Nombre: <span style='color: red'>Error - " . $e->getMessage() . "</span></li>";
        }
        
        try {
            echo "<li>Planeta: " . htmlspecialchars($entidad->getPlaneta()) . "</li>";
        } catch (Error $e) {
            echo "<li>Planeta: <span style='color: red'>Error - " . $e->getMessage() . "</span></li>";
        }
        
        try {
            echo "<li>Estabilidad: " . $entidad->getPeligrosidad() . "/10</li>";
        } catch (Error $e) {
            echo "<li>Estabilidad: <span style='color: red'>Error - " . $e->getMessage() . "</span></li>";
        }
        
        echo "</ul>";
    }
    
    // Procesar cada entidad
    foreach ($_SESSION['entidades'] as $key => $entidad) {
        verificarYRepararEntidad($entidad, $key);
    }
    
    // Botón para reparar todas
    echo "<hr>";
    echo '<form method="POST" action="">';
    echo '<button type="submit" name="reparar_todas" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">';
    echo '🛠️ Reparar todas las entidades automáticamente';
    echo '</button>';
    echo '</form>';
    
    // Si se presiona el botón de reparar
    if (isset($_POST['reparar_todas'])) {
        echo "<h3>🛠️ Proceso de reparación:</h3>";
        
        $reparadas = 0;
        $errores = 0;
        
        foreach ($_SESSION['entidades'] as $key => $entidad) {
            if (is_object($entidad) && get_class($entidad) === '__PHP_Incomplete_Class') {
                $serializado = serialize($entidad);
                $reparado = @unserialize($serializado);
                
                if (is_object($reparado) && get_class($reparado) !== '__PHP_Incomplete_Class') {
                    $_SESSION['entidades'][$key] = $reparado;
                    $reparadas++;
                    echo "<p>✅ Entidad #$key reparada (" . get_class($reparado) . ")</p>";
                } else {
                    $errores++;
                    echo "<p style='color: red'>❌ Error reparando entidad #$key</p>";
                }
            } else {
                // Ya está bien
                $reparadas++;
            }
        }
        
        echo "<h4>Resultado: $reparadas reparadas, $errores errores</h4>";
        
        // Recargar la página para ver cambios
        echo '<meta http-equiv="refresh" content="2">';
    }
    
} else {
    echo "<p style='color: orange'>⚠️ No hay entidades en la sesión o no existe la clave 'entidades'</p>";
    echo "<p>Contenido de \$_SESSION:</p>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
}

echo "<hr>";
echo '<div style="margin-top: 20px;">';
echo '<a href="index.php" style="padding: 10px 20px; background: #2196F3; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px;">← Volver al sistema</a>';
echo '<a href="limpiar_sesion.php" style="padding: 10px 20px; background: #f44336; color: white; text-decoration: none; border-radius: 4px;">🧹 Limpiar sesión</a>';
echo '</div>';

// Función para limpiar objetos incompletos
function limpiarObjetosIncompletos() {
    if (!isset($_SESSION['entidades']) || !is_array($_SESSION['entidades'])) {
        return;
    }
    
    $limpias = [];
    foreach ($_SESSION['entidades'] as $entidad) {
        if (is_object($entidad) && get_class($entidad) !== '__PHP_Incomplete_Class') {
            $limpias[] = $entidad;
        }
    }
    
    $_SESSION['entidades'] = $limpias;
    return count($limpias);
}

// Limpiar automáticamente si hay objetos incompletos
$total_antes = isset($_SESSION['entidades']) ? count($_SESSION['entidades']) : 0;
$total_despues = limpiarObjetosIncompletos();

if ($total_antes != $total_despues) {
    echo "<p style='color: orange'>⚠️ Se eliminaron " . ($total_antes - $total_despues) . " objetos incompletos automáticamente</p>";
}
?>