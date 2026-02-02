<?php
session_start();
session_destroy();

echo "<h1>✅ Sesión limpiada</h1>";
echo "<p>Todos los datos han sido eliminados. Se creará una nueva sesión.</p>";
echo '<a href="index.php">← Volver al sistema</a>';