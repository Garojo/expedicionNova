<!DOCTYPE html>
<html>
<head>
    <!-- En el formulario, añadir campo oculto para la página -->
<form method="POST" action="index.php?action=guardar">
    
    <!-- Campos ocultos para control -->
    <input type="hidden" name="editando" value="<?= isset($entidad) ? 'true' : 'false' ?>">
    
    <?php if (isset($entidad)): ?>
        <input type="hidden" name="id_original" value="<?= $entidad->getId() ?>">
        <?php 
            // Obtener tipo actual para el select
            $tipoActual = get_class($entidad);
            $tipoSeleccionado = $tipoActual; // Ya están en el mismo formato
        ?>
    <?php endif; ?>
    
    <!-- Campo oculto para guardar la página actual -->
    <input type="hidden" name="pagina_actual" 
           value="<?= $_SESSION['pagina_edicion'] ?? ($_GET['pagina'] ?? 1) ?>">
    
    <!-- Resto del formulario... -->
    <title><?= isset($entidad) ? 'Editar' : 'Crear' ?> Entidad Estelar</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 10px;
        }
        .campo { 
            margin-bottom: 20px; 
        }
        label { 
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }
        input, select { 
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 16px;
        }
        .botones {
            margin-top: 30px;
            display: flex;
            gap: 10px;
        }
        button { 
            padding: 12px 24px; 
            background: #4CAF50; 
            color: white; 
            border: none; 
            border-radius: 4px;
            cursor: pointer; 
            font-size: 16px;
            font-weight: bold;
            flex: 1;
        }
        .btn-cancelar {
            background: #f44336;
            text-decoration: none;
            color: white;
            padding: 12px 24px;
            border-radius: 4px;
            text-align: center;
            flex: 1;
        }
        .campo-especifico {
            background: #f0f8ff;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #2196F3;
        }
        .aviso {
            background: #fff3cd;
            color: #856404;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            border: 1px solid #ffeaa7;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= isset($entidad) ? '✏️ Editar' : '🚀 Crear' ?> Entidad Estelar</h1>
        
        <?php if (!isset($entidad)): ?>
            <div class="aviso">
                <strong>📝 Primero selecciona un tipo de entidad:</strong> 
                Elige el tipo en el menú desplegable para ver los campos específicos.
            </div>
        <?php endif; ?>
        
        <form method="POST" action="index.php?action=guardar">
            <!-- Campos ocultos para control -->
            <input type="hidden" name="editando" value="<?= isset($entidad) ? 'true' : 'false' ?>">
            
            <?php if (isset($entidad)): ?>
                <input type="hidden" name="id_original" value="<?= $entidad->getId() ?>">
                <?php $tipoActual = get_class($entidad); ?>
            <?php endif; ?>
            
            <!-- Tipo de Entidad -->
            <div class="campo">
                <label for="tipo">📋 Tipo de Entidad:</label>
                <select name="tipo" id="tipoSelect" required 
                        onchange="mostrarCamposEspecificos(this.value)">
                    <option value="">-- Selecciona un tipo --</option>
                    <option value="FormaDeVida" <?= (isset($entidad) && $tipoActual === 'FormaDeVida') ? 'selected' : '' ?>>
                        👽 Forma de Vida
                    </option>
                    <option value="Minerales" <?= (isset($entidad) && $tipoActual === 'Minerales') ? 'selected' : '' ?>>
                        💎 Mineral
                    </option>
                    <option value="Climatologia" <?= (isset($entidad) && $tipoActual === 'Climatologia') ? 'selected' : '' ?>>
                        🌪️ Climatología
                    </option>
                </select>
                
                <?php if (isset($entidad)): ?>
                    <input type="hidden" name="tipo" value="<?= $tipoActual ?>">
                <?php endif; ?>
            </div>
            
            <!-- Campos COMUNES -->
            <div class="campo">
                <label for="nombre">🔤 Nombre:</label>
                <input type="text" name="nombre" id="nombre" 
                       value="<?= isset($entidad) ? htmlspecialchars($entidad->getNombre()) : '' ?>" 
                       required>
            </div>
            
            <div class="campo">
                <label for="id">🆔 ID (único):</label>
                <input type="number" step="0.1" name="id" id="id" 
                       value="<?= isset($entidad) ? $entidad->getId() : '' ?>" 
                       <?= isset($entidad) ? 'readonly' : 'required' ?>>
            </div>
            
            <div class="campo">
                <label for="planeta">🪐 Planeta de Origen:</label>
                <input type="text" name="planeta" id="planeta" 
                       value="<?= isset($entidad) ? htmlspecialchars($entidad->getPlaneta()) : '' ?>" 
                       required>
            </div>
            
<div class="campo">
    <label for="peligrosidad">⚠️ Nivel de Peligrosidad:</label>
    <div style="display: flex; align-items: center; gap: 15px;">
        <input type="range" min="1" max="10" step="0.1" name="peligrosidad" id="peligrosidad" 
               value="<?= isset($entidad) ? $entidad->getPeligrosidad() : '5' ?>" 
               oninput="actualizarPeligrosidad(this.value)"
               required
               style="flex-grow: 1;">
        
        <div id="indicador-peligrosidad" 
             style="width: 120px; padding: 8px 15px; border-radius: 20px; text-align: center; font-weight: bold; color: white;"
             data-value="<?= isset($entidad) ? $entidad->getPeligrosidad() : '5' ?>">
            <span id="valor-peligrosidad"><?= isset($entidad) ? $entidad->getPeligrosidad() : '5' ?></span>/10
        </div>
    </div>
    
    <div style="display: flex; justify-content: space-between; margin-top: 5px; font-size: 12px;">
        <span>1 = Inofensivo</span>
        <span>10 = Extremadamente peligroso</span>
    </div>
    
    <div id="descripcion-peligrosidad" style="margin-top: 8px; font-size: 13px; font-style: italic;">
        <!-- Se llena con JavaScript -->
    </div>
</div>

<script>
// Actualizar indicador de peligrosidad en tiempo real
function actualizarPeligrosidad(valor) {
    // Actualizar valor numérico
    document.getElementById('valor-peligrosidad').textContent = valor;
    
    // Actualizar color del indicador
    var indicador = document.getElementById('indicador-peligrosidad');
    if (valor < 3) {
        indicador.style.background = 'linear-gradient(135deg, #f44336 0%, #d32f2f 100%)';
        indicador.style.boxShadow = '0 2px 8px rgba(244, 67, 54, 0.3)';
        document.getElementById('descripcion-peligrosidad').innerHTML = 
            '<span style="color: #f44336">⚠️ ALTA PELIGROSIDAD</span> - Aparecerá en rojo en el listado';
    } else if (valor < 6) {
        indicador.style.background = 'linear-gradient(135deg, #ffc107 0%, #ffa000 100%)';
        indicador.style.boxShadow = '0 2px 8px rgba(255, 193, 7, 0.3)';
        document.getElementById('descripcion-peligrosidad').innerHTML = 
            '<span style="color: #ffa000">⚠️ Peligrosidad moderada</span> - Aparecerá en naranja';
    } else {
        indicador.style.background = 'linear-gradient(135deg, #4CAF50 0%, #388e3c 100%)';
        indicador.style.boxShadow = '0 2px 8px rgba(76, 175, 80, 0.3)';
        document.getElementById('descripcion-peligrosidad').innerHTML = 
            '<span style="color: #388e3c">✓ Baja peligrosidad</span> - Aparecerá en verde';
    }
}

// Inicializar al cargar la página
window.onload = function() {
    var valorInicial = document.getElementById('peligrosidad').value;
    actualizarPeligrosidad(valorInicial);
};
</script>
            
            <!-- Campos ESPECÍFICOS (se muestran dinámicamente) -->
            <div id="camposEspecificos">
                <?php if (isset($entidad)): ?>
                    <?php if ($tipoActual === 'FormaDeVida'): ?>
                        <div class="campo-especifico">
                            <h3>👽 Datos de Forma de Vida</h3>
                            <div class="campo">
                                <label for="dieta">🍽️ Dieta:</label>
                                <input type="text" name="dieta" id="dieta" 
                                       value="<?= htmlspecialchars($entidad->getDieta()) ?>" required>
                            </div>
                            <div class="campo">
                                <label for="estructura_osea">🦴 Estructura Ósea:</label>
                                <input type="text" name="estructura_osea" id="estructura_osea" 
                                       value="<?= htmlspecialchars($entidad->getEstructuraOsea()) ?>" required>
                            </div>
                        </div>
                    <?php elseif ($tipoActual === 'Minerales'): ?>
                        <div class="campo-especifico">
                            <h3>💎 Datos de Mineral</h3>
                            <div class="campo">
                                <label for="composicion">🧪 Composición:</label>
                                <input type="text" name="composicion" id="composicion" 
                                       value="<?= htmlspecialchars($entidad->getComposicionQuimica()) ?>" required>
                            </div>
                            <div class="campo">
                                <label for="dureza">💪 Dureza (1-10):</label>
                                <input type="number" min="1" max="10" step="0.1" name="dureza" id="dureza" 
                                       value="<?= $entidad->getDureza() ?>" required>
                            </div>
                        </div>
                    <?php elseif ($tipoActual === 'Climatologia'): ?>
                        <div class="campo-especifico">
                            <h3>🌪️ Datos de Climatología</h3>
                            <div class="campo">
                                <label for="temperatura">🌡️ Temperatura (°C):</label>
                                <input type="number" step="0.1" name="temperatura" id="temperatura" 
                                       value="<?= $entidad->getTemperaturaMedia() ?>" required>
                            </div>
                            <div class="campo">
                                <label for="presion">🌬️ Presión (hPa):</label>
                                <input type="number" step="0.1" name="presion" id="presion" 
                                       value="<?= $entidad->getPresionAtmosferica() ?>" required>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <!-- Botones -->
            <div class="botones">
                <button type="submit" id="btnGuardar">
                    <?= isset($entidad) ? '💾 Guardar Cambios' : '🚀 Crear Entidad' ?>
                </button>
<?php

$pagina_actual = $_GET['pagina'] ?? 
                 ($_POST['pagina_actual'] ?? 
                 ($_SESSION['pagina_edicion'] ?? 
                 ($_SESSION['ultima_pagina'] ?? 1)));
?>

<a href="index.php?action=index&pagina=<?= $pagina_actual ?>" 
   class="btn-cancelar">❌ Cancelar</a>
            </div>
        </form>
    </div>

    <script>
        function mostrarCamposEspecificos(tipo) {
            var divCampos = document.getElementById('camposEspecificos');
            var html = '';
            
            if (tipo === 'FormaDeVida') {
                html = `
                    <div class="campo-especifico">
                        <h3>👽 Datos de Forma de Vida</h3>
                        <div class="campo">
                            <label for="dieta">🍽️ Dieta:</label>
                            <input type="text" name="dieta" id="dieta" required>
                        </div>
                        <div class="campo">
                            <label for="estructura_osea">🦴 Estructura Ósea:</label>
                            <input type="text" name="estructura_osea" id="estructura_osea" required>
                        </div>
                    </div>
                `;
            } else if (tipo === 'Minerales') {
                html = `
                    <div class="campo-especifico">
                        <h3>💎 Datos de Mineral</h3>
                        <div class="campo">
                            <label for="composicion">🧪 Composición:</label>
                            <input type="text" name="composicion" id="composicion" required>
                        </div>
                        <div class="campo">
                            <label for="dureza">💪 Dureza (1-10):</label>
                            <input type="number" min="1" max="10" step="0.1" name="dureza" id="dureza" required>
                        </div>
                    </div>
                `;
            } else if (tipo === 'Climatologia') {
                html = `
                    <div class="campo-especifico">
                        <h3>🌪️ Datos de Climatología</h3>
                        <div class="campo">
                            <label for="temperatura">🌡️ Temperatura (°C):</label>
                            <input type="number" step="0.1" name="temperatura" id="temperatura" required>
                        </div>
                        <div class="campo">
                            <label for="presion">🌬️ Presión (hPa):</label>
                            <input type="number" step="0.1" name="presion" id="presion" required>
                        </div>
                    </div>
                `;
            }
            
            divCampos.innerHTML = html;
        }
        
        // Si hay un tipo seleccionado al cargar (edición), mostrar sus campos
        window.onload = function() {
            var selectTipo = document.getElementById('tipoSelect');
            if (selectTipo.value) {
                mostrarCamposEspecificos(selectTipo.value);
            }
        };
    </script>

    
</body>
</html>