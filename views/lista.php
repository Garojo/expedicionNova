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

// Función para obtener clase CSS según estabilidad (CORREGIDA: < 3 = ROJO)
function obtenerClaseEstabilidad($estabilidad) {
    if ($estabilidad < 3) {
        return 'alerta-alta';    // Rojo - BAJA estabilidad (ALERTA)
    } elseif ($estabilidad < 6) {
        return 'alerta-media';   // Naranja - Estabilidad media
    } else {
        return 'alerta-baja';    // Verde - ALTA estabilidad
    }
}

// Función para obtener color de la barra (CORREGIDA)
function obtenerColorBarra($estabilidad) {
    if ($estabilidad < 3) {
        return '#dc3545'; // Rojo - Poco estable/Peligroso
    } elseif ($estabilidad < 6) {
        return '#ffc107'; // Naranja - Estabilidad media
    } else {
        return '#28a745'; // Verde - Muy estable/Seguro
    }
}

// Función para obtener texto descriptivo (CORREGIDA)
function obtenerDescripcionEstabilidad($estabilidad) {
    if ($estabilidad < 3) {
        return [
            'texto' => 'BAJA ESTABILIDAD',
            'desc' => 'Nivel crítico - Requiere atención inmediata',
            'icono' => '🔥',
            'clase' => 'estabilidad-baja'
        ];
    } elseif ($estabilidad < 6) {
        return [
            'texto' => 'ESTABILIDAD MEDIA',
            'desc' => 'Requiere monitoreo constante',
            'icono' => '⚠️',
            'clase' => 'estabilidad-media'
        ];
    } else {
        return [
            'texto' => 'ALTA ESTABILIDAD',
            'desc' => 'Entidad segura y estable',
            'icono' => '✅',
            'clase' => 'estabilidad-alta'
        ];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Censo Galáctico - Expedición Nova</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #0c2461 0%, #1e3799 100%);
            color: #333;
            min-height: 100vh;
        }
        .container { 
            max-width: 1400px; 
            margin: 0 auto; 
            background: rgba(255, 255, 255, 0.95); 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }
        h1 { 
            color: #1a237e; 
            border-bottom: 3px solid #00bcd4; 
            padding-bottom: 15px;
            margin-bottom: 25px;
            font-size: 2.5em;
            text-align: center;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        .btn-nuevo { 
            padding: 12px 25px; 
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%); 
            color: white; 
            text-decoration: none; 
            border-radius: 8px; 
            display: inline-block;
            font-weight: bold;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 188, 212, 0.3);
        }
        .btn-nuevo:hover { 
            background: linear-gradient(135deg, #0097a7 0%, #00838f 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 188, 212, 0.4);
        }
        .info-panel {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            padding: 15px 20px;
            border-radius: 10px;
            border-left: 5px solid #2196f3;
            margin-bottom: 25px;
        }
        .stats {
            display: flex;
            gap: 20px;
            font-size: 14px;
        }
        .stat {
            padding: 8px 15px;
            background: white;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        th { 
            background: linear-gradient(135deg, #1a237e 0%, #283593 100%); 
            color: white; 
            padding: 18px 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
        }
        td { 
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            vertical-align: middle;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:nth-child(even) { 
            background-color: #f8f9fa; 
        }
        tr:hover { 
            background-color: #e3f2fd !important; 
            transform: scale(1.002);
            transition: transform 0.1s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        /* CLASES DE ALERTA SEGÚN ESTABILIDAD (CORREGIDAS: < 3 = ROJO) */
        .alerta-alta {
            background-color: #ffebee !important;
            border-left: 5px solid #f44336 !important;
            animation: pulse 2s infinite;
        }
        .alerta-alta td:first-child::before {
            content: '🔥 ';
            color: #f44336;
            font-weight: bold;
        }
        
        .alerta-media {
            background-color: #fff3cd !important;
            border-left: 5px solid #ffc107 !important;
        }
        .alerta-media td:first-child::before {
            content: '⚠️ ';
            color: #ffc107;
        }
        
        .alerta-baja {
            background-color: #d4edda !important;
            border-left: 5px solid #28a745 !important;
        }
        .alerta-baja td:first-child::before {
            content: '✅ ';
            color: #28a745;
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(244, 67, 54, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(244, 67, 54, 0); }
            100% { box-shadow: 0 0 0 0 rgba(244, 67, 54, 0); }
        }
        
        /* Indicador de estabilidad */
        .indicador-estabilidad {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
            margin-left: 10px;
            vertical-align: middle;
        }
        .estabilidad-baja {
            background: #f44336;
            color: white;
            box-shadow: 0 2px 4px rgba(244, 67, 54, 0.3);
        }
        .estabilidad-media {
            background: #ffc107;
            color: #000;
            box-shadow: 0 2px 4px rgba(255, 193, 7, 0.3);
        }
        .estabilidad-alta {
            background: #28a745;
            color: white;
            box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
        }
        
        /* BARRA DE ESTABILIDAD */
        .barra-estabilidad {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .barra-container {
            flex-grow: 1;
            height: 10px;
            background: #e0e0e0;
            border-radius: 5px;
            overflow: hidden;
        }
        .barra-progreso {
            height: 100%;
            border-radius: 5px;
            transition: width 0.5s ease;
        }
        .valor-estabilidad {
            font-weight: bold;
            min-width: 40px;
            text-align: center;
            font-size: 14px;
        }
        
        /* ICONOS SEGÚN TIPO */
        .tipo-icono {
            font-size: 20px;
            margin-right: 8px;
            vertical-align: middle;
        }
        
        /* ACCIONES */
        .acciones {
            display: flex;
            gap: 8px;
        }
        .btn-accion {
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-editar {
            background: #4CAF50;
            color: white;
        }
        .btn-editar:hover {
            background: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 3px 6px rgba(76, 175, 80, 0.3);
        }
        .btn-eliminar {
            background: #f44336;
            color: white;
        }
        .btn-eliminar:hover {
            background: #d32f2f;
            transform: translateY(-2px);
            box-shadow: 0 3px 6px rgba(244, 67, 54, 0.3);
        }
        
        /* PAGINADOR */
        .paginador { 
            margin-top: 30px; 
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .paginador a, .paginador span { 
            padding: 10px 15px; 
            border: 1px solid #ddd; 
            text-decoration: none; 
            margin: 0 2px; 
            display: inline-block;
            border-radius: 6px;
            transition: all 0.2s ease;
            min-width: 40px;
            text-align: center;
        }
        .paginador a:hover { 
            background: linear-gradient(135deg, #00bcd4 0%, #0097a7 100%); 
            color: white; 
            border-color: #00bcd4;
            transform: translateY(-2px);
        }
        .paginador .actual { 
            background: linear-gradient(135deg, #1a237e 0%, #283593 100%); 
            color: white; 
            border-color: #1a237e;
            font-weight: bold;
        }
        
        /* ESTADO VACÍO */
        .vacio { 
            padding: 60px 40px; 
            text-align: center; 
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); 
            border-radius: 10px; 
            margin: 30px 0;
            border: 2px dashed #adb5bd;
        }
        .vacio h3 { 
            color: #6c757d; 
            margin-bottom: 15px;
        }
        
        /* LEYENDA */
        .leyenda {
            display: flex;
            gap: 20px;
            margin: 20px 0 30px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            flex-wrap: wrap;
        }
        .leyenda-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }
        .leyenda-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border-left: 5px solid;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .container { padding: 15px; }
            .header { flex-direction: column; align-items: stretch; }
            .stats { flex-direction: column; }
            table { font-size: 14px; }
            th, td { padding: 10px; }
            .acciones { flex-direction: column; }
            .barra-estabilidad { flex-direction: column; align-items: flex-start; }
            .barra-container { width: 100%; }
            .leyenda { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Censo Galáctico - Expedición Nova</h1>
        
        <div class="header">
            <a href="index.php?action=crear" class="btn-nuevo">
                <span style="font-size: 18px;">🛸</span> Nueva Entidad Estelar
            </a>
            
            <div class="info-panel">
                <div class="stats">
                    <div class="stat">📊 <strong>Total:</strong> <?= $total ?> entidades</div>
                    <div class="stat">📄 <strong>Página:</strong> <?= $pagina ?> de <?= $paginas ?></div>
                    <div class="stat">👁️ <strong>Mostrando:</strong> <?= count($entidadesPagina) ?> entidades</div>
                </div>
            </div>
        </div>
        
        <!-- Leyenda de colores CORREGIDA -->
        <div class="leyenda">
            <div class="leyenda-item">
                <div class="leyenda-color" style="background: #d4edda; border-left-color: #28a745;"></div>
                <span><strong>Alta Estabilidad</strong> (6-10) - Seguro</span>
            </div>
            <div class="leyenda-item">
                <div class="leyenda-color" style="background: #fff3cd; border-left-color: #ffc107;"></div>
                <span><strong>Estabilidad Media</strong> (3-5.9) - Monitorear</span>
            </div>
            <div class="leyenda-item">
                <div class="leyenda-color" style="background: #ffebee; border-left-color: #f44336;"></div>
                <span><strong>🔥 ALERTA: Baja Estabilidad</strong> (1-2.9)</span>
            </div>
        </div>
        
        <?php if ($total == 0): ?>
            <div class="vacio">
                <h3>🌌 El universo está vacío... por ahora</h3>
                <p style="font-size: 16px; margin-bottom: 25px;">No se han registrado entidades estelares en esta expedición.</p>
                <a href="index.php?action=crear" class="btn-nuevo" style="font-size: 18px;">
                    🚀 Iniciar primera exploración
                </a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Nombre</th>
                        <th>Planeta</th>
                        <th>Nivel de Estabilidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entidadesPagina as $entidad): ?>
                    <?php
                    // Verificar que es un objeto válido
                    if (!is_object($entidad) || !method_exists($entidad, 'getPeligrosidad')) {
                        continue;
                    }
                    
                    // Obtener nivel de estabilidad (1-10, donde 10 es muy estable)
                    $estabilidad = $entidad->getPeligrosidad(); // Este es el nivel de estabilidad
                    $claseAlerta = obtenerClaseEstabilidad($estabilidad);
                    $colorBarra = obtenerColorBarra($estabilidad);
                    $infoEstabilidad = obtenerDescripcionEstabilidad($estabilidad);
                    
                    // Icono según tipo
                    $iconos = [
                        'FormaDeVida' => '👽',
                        'Minerales' => '💎',
                        'Climatologia' => '🌪️'
                    ];
                    $tipo = get_class($entidad);
                    $icono = $iconos[$tipo] ?? '❓';
                    ?>
                    <tr class="<?= $claseAlerta ?>">
                        <td><strong>#<?= htmlspecialchars($entidad->getId()) ?></strong></td>
                        <td>
                            <span class="tipo-icono"><?= $icono ?></span>
                            <?= $tipo ?>
                        </td>
                        <td><?= htmlspecialchars($entidad->getNombre()) ?></td>
                        <td>🪐 <?= htmlspecialchars($entidad->getPlaneta()) ?></td>
                        <td>
                            <div style="margin-bottom: 5px;">
                                <span class="barra-estabilidad">
                                    <span class="valor-estabilidad"><?= number_format($estabilidad, 1) ?>/10</span>
                                    <div class="barra-container">
                                        <div class="barra-progreso" 
                                             style="width: <?= $estabilidad * 10 ?>%; 
                                                    background: <?= $colorBarra ?>;"></div>
                                    </div>
                                </span>
                                <span class="indicador-estabilidad <?= $infoEstabilidad['clase'] ?>">
                                    <?= $infoEstabilidad['icono'] ?> <?= $infoEstabilidad['texto'] ?>
                                </span>
                            </div>
                            <div style="font-size: 12px; color: #666; font-style: italic;">
                                <?= $infoEstabilidad['desc'] ?>
                            </div>
                        </td>
<td class="acciones">
    <a href="index.php?action=editar&id=<?= $entidad->getId() ?>&pagina=<?= $pagina ?>" 
       class="btn-accion btn-editar" title="Editar entidad">
        ✏️ Editar
    </a>
    <a href="index.php?action=eliminar&id=<?= $entidad->getId() ?>&pagina=<?= $pagina ?>" 
       class="btn-accion btn-eliminar" 
       onclick="return confirm('¿Expulsar \'<?= addslashes($entidad->getNombre()) ?>\' al espacio exterior?')"
       title="Eliminar entidad">
        🗑️ Eliminar
    </a>
</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Paginación -->
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
        
        <!-- Info del sistema -->
        <div style="margin-top: 40px; padding: 15px; background: #f8f9fa; border-radius: 8px; font-size: 14px; text-align: center; border-top: 3px solid #00bcd4;">
            <strong>🌐 Sistema de Gestión Galáctica</strong> |
            <a href="debug.php" style="color: #1a237e; text-decoration: none;">🔧 Modo debug</a>
        </div>
    </div>
</body>
</html>