<?php
session_start();

echo "<h1>🔍 DEBUG - Sesión PHP</h1>";
echo "<p>ID de sesión: " . session_id() . "</p>";
echo "<p>Cookie de sesión: " . (isset($_COOKIE['PHPSESSID']) ? $_COOKIE['PHPSESSID'] : 'NO HAY') . "</p>";

echo "<h2>📊 Contenido de \$_SESSION</h2>";

if (empty($_SESSION)) {
    echo "<p style='color: red'>⚠️ La sesión está VACÍA</p>";
} else {
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
}

// Verificar entidades específicamente
echo "<h2>👽 Entidades en sesión</h2>";

if (isset($_SESSION['entidades']) && is_array($_SESSION['entidades'])) {
    $total = count($_SESSION['entidades']);
    echo "<p>Total entidades: $total</p>";
    
    foreach ($_SESSION['entidades'] as $key => $entidad) {
        echo "<hr>";
        echo "<p><strong>Entidad #$key:</strong></p>";
        
        if (is_object($entidad)) {
            echo "<ul>";
            echo "<li>Tipo: " . get_class($entidad) . "</li>";
            echo "<li>ID: " . $entidad->getId() . "</li>";
            echo "<li>Nombre: " . $entidad->getNombre() . "</li>";
            echo "<li>Planeta: " . $entidad->getPlaneta() . "</li>";
            echo "<li>Peligrosidad: " . $entidad->getPeligrosidad() . "</li>";
            echo "</ul>";
        } else {
            echo "<p style='color: orange'>⚠️ No es un objeto válido</p>";
            echo "<pre>";
            var_dump($entidad);
            echo "</pre>";
        }
    }
} else {
    echo "<p style='color: red'>❌ No hay clave 'entidades' en la sesión</p>";
}

echo "<hr>";
echo '<a href="index.php">← Volver al sistema</a> | ';
echo '<a href="index.php?action=crear">➕ Crear entidad</a> | ';
echo '<a href="limpiar_sesion.php">🧹 Limpiar sesión</a>';
?>