<?php
// Solo mostrar, no iniciar sesión
echo "<h1>🔍 DEBUG - Estado de Sesión</h1>";

if (session_status() === PHP_SESSION_ACTIVE) {
    echo "<p style='color: green'>✅ Sesión ACTIVA</p>";
    echo "<p>ID de sesión: " . session_id() . "</p>";
    
    echo "<h2>📊 Contenido de \$_SESSION</h2>";
    
    if (empty($_SESSION)) {
        echo "<p style='color: orange'>⚠️ \$_SESSION está vacío</p>";
    } else {
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
    }
    
    echo "<h2>👽 Entidades registradas</h2>";
    
    if (isset($_SESSION['entidades']) && is_array($_SESSION['entidades'])) {
        $total = count($_SESSION['entidades']);
        echo "<p>Total: $total entidades</p>";
        
        if ($total > 0) {
            echo "<table border='1' cellpadding='10'>";
            echo "<tr><th>#</th><th>Tipo</th><th>ID</th><th>Nombre</th><th>Planeta</th><th>Peligrosidad</th></tr>";
            
            foreach ($_SESSION['entidades'] as $key => $entidad) {
                if (is_object($entidad)) {
                    echo "<tr>";
                    echo "<td>$key</td>";
                    echo "<td>" . get_class($entidad) . "</td>";
                    echo "<td>" . $entidad->getId() . "</td>";
                    echo "<td>" . htmlspecialchars($entidad->getNombre()) . "</td>";
                    echo "<td>" . htmlspecialchars($entidad->getPlaneta()) . "</td>";
                    echo "<td>" . $entidad->getPeligrosidad() . "/10</td>";
                    echo "</tr>";
                } else {
                    echo "<tr style='background: #ffcccc'>";
                    echo "<td colspan='6'>Índice $key: No es objeto (" . gettype($entidad) . ")</td>";
                    echo "</tr>";
                }
            }
            echo "</table>";
        }
    } else {
        echo "<p style='color: red'>❌ No hay clave 'entidades' en la sesión</p>";
    }
} else {
    echo "<p style='color: red'>❌ Sesión NO ACTIVA (status: " . session_status() . ")</p>";
    echo "<p>PHP_SESSION_NONE = 1, PHP_SESSION_ACTIVE = 2, PHP_SESSION_DISABLED = 0</p>";
}

echo "<hr>";
echo '<a href="index.php">← Volver al sistema</a> | ';
echo '<a href="index.php?action=crear">➕ Crear entidad</a> | ';
echo '<a href="#" onclick="location.reload();">🔄 Actualizar</a>';
?>