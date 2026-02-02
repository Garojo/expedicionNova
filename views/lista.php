<?php
// Verificar que tenemos datos
if (!isset($entidadesPagina)) {
    $entidadesPagina = [];
}

if (!isset($pagina)) {
    $pagina = 1;
}

if (!isset($paginas)) {
    $paginas = 1;
}

if (!isset($total)) {
    $total = 0;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Censo Galáctico</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #4CAF50; padding-bottom: 10px; }
        .btn { padding: 10px 15px; background: #4CAF50; color: white; text-decoration: none; border-radius: 4px; display: inline-block; }
        .btn:hover { background: #45a049; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #4CAF50; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        .paginador { margin-top: 20px; text-align: center; }
        .paginador a, .paginador span { padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; margin: 0 3px; display: inline-block; }
        .paginador a:hover { background: #4CAF50; color: white; border-color: #4CAF50; }
        .paginador .actual { background: #4CAF50; color: white; border-color: #4CAF50; }
        .vacio { padding: 40px; text-align: center; background: #f8f9fa; border-radius: 5px; margin: 20px 0; }
        .acciones a { margin-right: 10px; text-decoration: none; }
        .error-obj { background: #ffebee; color: #c62828; padding: 5px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Censo Galáctico</h1>
        
        <a href="index.php?action=crear" class="btn">➕ Nueva Entidad</a>
        
        <p><strong>Total:</strong> <?= $total ?> entidades | <strong>Página:</strong> <?= $pagina ?> de <?= $paginas ?></p>
        
        <?php if ($total == 0): ?>
            <div class="vacio">
                <h3>📭 No hay entidades registradas</h3>
                <p>El universo espera ser explorado. ¡Añade tu primera entidad estelar!</p>
                <a href="index.php?action=crear" class="btn">🚀 Crear Primera Entidad</a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Nombre</th>
                        <th>Planeta</th>
                        <th>Peligrosidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entidadesPagina as $entidad): ?>
                    <?php
                    // Verificar que es un objeto válido
                    if (!is_object($entidad)) {
                        continue; // Saltar si no es objeto
                    }
                    
                    // Verificar que no sea un objeto incompleto
                    if (get_class($entidad) === '__PHP_Incomplete_Class') {
                        echo '<tr class="error-obj">';
                        echo '<td colspan="6">⚠️ Objeto incompleto (clase no cargada)</td>';
                        echo '</tr>';
                        continue;
                    }
                    
                    // Verificar que tenga los métodos necesarios
                    if (!method_exists($entidad, 'getId') || 
                        !method_exists($entidad, 'getNombre') || 
                        !method_exists($entidad, 'getPlaneta') || 
                        !method_exists($entidad, 'getPeligrosidad')) {
                        echo '<tr class="error-obj">';
                        echo '<td colspan="6">⚠️ Objeto inválido (métodos faltantes)</td>';
                        echo '</tr>';
                        continue;
                    }
                    ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($entidad->getId()) ?></strong></td>
                        <td><?= get_class($entidad) ?></td>
                        <td><?= htmlspecialchars($entidad->getNombre()) ?></td>
                        <td><?= htmlspecialchars($entidad->getPlaneta()) ?></td>
                        <td>
                            <?= $entidad->getPeligrosidad() ?>/10
                            <div style="width: 100px; height: 8px; background: #ddd; display: inline-block; margin-left: 10px; border-radius: 4px;">
                                <div style="width: <?= $entidad->getPeligrosidad() * 10 ?>%; height: 100%; background: #4CAF50; border-radius: 4px;"></div>
                            </div>
                        </td>
                        <td class="acciones">
                            <a href="index.php?action=editar&id=<?= $entidad->getId() ?>" title="Editar">✏️ Editar</a>
                            <a href="index.php?action=eliminar&id=<?= $entidad->getId() ?>" 
                               onclick="return confirm('¿Eliminar <?= addslashes($entidad->getNombre()) ?>?')"
                               title="Eliminar">🗑️ Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Paginación simple -->
            <?php if ($paginas > 1): ?>
            <div class="paginador">
                <?php if ($pagina > 1): ?>
                    <a href="index.php?action=index&pagina=<?= $pagina-1 ?>">« Anterior</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $paginas; $i++): ?>
                    <?php if ($i == $pagina): ?>
                        <span class="actual"><?= $i ?></span>
                    <?php else: ?>
                        <a href="index.php?action=index&pagina=<?= $i ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($pagina < $paginas): ?>
                    <a href="index.php?action=index&pagina=<?= $pagina+1 ?>">Siguiente »</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <!-- Debug info (opcional) -->
        <?php if (isset($_GET['debug'])): ?>
        <div style="margin-top: 30px; padding: 15px; background: #e3f2fd; border-radius: 5px; font-size: 12px;">
            <strong>Debug:</strong> 
            Sesión ID: <?= session_id() ?> | 
            Clases cargadas: <?= count(get_declared_classes()) ?> | 
            Memoria: <?= round(memory_get_usage() / 1024 / 1024, 2) ?>MB
        </div>
        <?php endif; ?>
    </div>
</body>
</html>