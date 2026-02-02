<?php
// Verificar que $entidades está definida
if (!isset($entidades)) {
    $entidades = [];
    echo "<p style='color: orange'>⚠️ No hay entidades para mostrar</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Censo Galáctico</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .peligro-alto { background-color: #ffcccc !important; }
        .btn { padding: 5px 10px; text-decoration: none; border-radius: 3px; }
        .btn-editar { background: #4CAF50; color: white; }
        .btn-eliminar { background: #f44336; color: white; }
        .btn-nuevo { background: #2196F3; color: white; padding: 10px 15px; }
    </style>
</head>
<body>
    <h1>🚀 Censo Galáctico</h1>
    
    <a href="index.php?action=crear" class="btn btn-nuevo">➕ Nueva Entidad</a>
    
    <p>Total de entidades: <?= count($entidades) ?></p>
    
    <?php if (empty($entidades)): ?>
        <p>No hay entidades registradas. <a href="index.php?action=crear">Crea la primera</a></p>
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
                <?php foreach ($entidades as $entidad): ?>
                <?php 
                // Verificar que la entidad es válida
                if (!is_object($entidad)) continue;
                
                $clasePeligro = $entidad->getPeligrosidad() > 7 ? 'peligro-alto' : '';
                ?>
                <tr class="<?= $clasePeligro ?>">
                    <td><?= htmlspecialchars($entidad->getId()) ?></td>
                    <td><?= get_class($entidad) ?></td>
                    <td><?= htmlspecialchars($entidad->getNombre()) ?></td>
                    <td><?= htmlspecialchars($entidad->getPlaneta()) ?></td>
                    <td><?= $entidad->getPeligrosidad() ?>/10</td>
                    <td>
                        <a href="index.php?action=editar&id=<?= $entidad->getId() ?>" 
                           class="btn btn-editar">✏️ Editar</a>
                        <a href="index.php?action=eliminar&id=<?= $entidad->getId() ?>" 
                           class="btn btn-eliminar"
                           onclick="return confirm('¿Expulsar esta entidad al espacio?')">🗑️ Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>