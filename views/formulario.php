<!DOCTYPE html>
<html>
<head>
    <title><?= isset($entidad) ? 'Editar' : 'Crear' ?> Entidad Estelar</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .campo { margin-bottom: 15px; }
        label { display: inline-block; width: 200px; }
        input, select { width: 300px; padding: 5px; }
        .especifico { background-color: #f0f0f0; padding: 15px; margin: 15px 0; border-radius: 5px; }
        button { padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer; font-size: 16px; }
        button:hover { background: #45a049; }
        .error { color: red; }
        .tipo-seccion { margin-bottom: 20px; }
    </style>
    <script>
        function mostrarCamposEspecificos() {
            var tipo = document.getElementById('tipo').value;
            
            // Ocultar todos los campos específicos primero
            document.getElementById('camposFormaVida').style.display = 'none';
            document.getElementById('camposMineral').style.display = 'none';
            document.getElementById('camposClima').style.display = 'none';
            
            // Mostrar solo los campos correspondientes
            if (tipo === 'FormaDeVida') {
                document.getElementById('camposFormaVida').style.display = 'block';
            } else if (tipo === 'Minerales') {
                document.getElementById('camposMineral').style.display = 'block';
            } else if (tipo === 'Climatologia') {
                document.getElementById('camposClima').style.display = 'block';
            }
            
            // Mostrar el botón de guardar solo si hay tipo seleccionado
            var btnGuardar = document.getElementById('btnGuardar');
            btnGuardar.style.display = tipo ? 'inline-block' : 'none';
        }
        
        // Ejecutar al cargar la página
        window.onload = mostrarCamposEspecificos;
    </script>
</head>
<body>
    <h1><?= isset($entidad) ? 'Editar' : 'Crear' ?> Entidad Estelar</h1>
    
    <a href="index.php?action=index">← Volver al listado</a>
    <hr>
    
    <form method="POST" action="index.php?action=guardar">
        <!-- Si estamos editando, necesitamos el ID -->
        <?php if (isset($entidad)): ?>
            <input type="hidden" name="id" value="<?= $entidad->getId() ?>">
        <?php endif; ?>
        
        <!-- Tipo de Entidad -->
        <div class="campo">
            <label for="tipo"><strong>Tipo de Entidad:</strong></label>
            <select name="tipo" id="tipo" required onchange="mostrarCamposEspecificos()">
                <option value="">-- Selecciona un tipo --</option>
                <option value="FormaDeVida" 
                    <?= (isset($entidad) && get_class($entidad) === 'FormaDeVida') ? 'selected' : '' ?>>
                    Forma de Vida
                </option>
                <option value="Minerales"
                    <?= (isset($entidad) && get_class($entidad) === 'Minerales') ? 'selected' : '' ?>>
                    Mineral
                </option>
                <option value="Climatologia"
                    <?= (isset($entidad) && get_class($entidad) === 'Climatologia') ? 'selected' : '' ?>>
                    Climatología
                </option>
            </select>
            <small>Selecciona primero el tipo para ver los campos específicos</small>
        </div>
        
        <h3>Datos Generales (obligatorios para todos)</h3>
        
        <div class="campo">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" 
                   value="<?= isset($entidad) ? htmlspecialchars($entidad->getNombre()) : '' ?>" 
                   required placeholder="Ej: Xenomorfo, Cristal de Kyber, Tormenta de Metano">
        </div>
        
        <div class="campo">
            <label for="id">ID (único):</label>
            <input type="number" step="0.1" name="id" id="id" 
                   value="<?= isset($entidad) ? $entidad->getId() : '' ?>" 
                   <?= isset($entidad) ? 'readonly' : 'required' ?>
                   placeholder="Ej: 1.0, 2.5">
        </div>
        
        <div class="campo">
            <label for="planeta">Planeta de Origen:</label>
            <input type="text" name="planeta" id="planeta" 
                   value="<?= isset($entidad) ? htmlspecialchars($entidad->getPlaneta()) : '' ?>" 
                   required placeholder="Ej: Marte, Kepler-438b, Tatooine">
        </div>
        
        <div class="campo">
            <label for="peligrosidad">Nivel de Peligrosidad (1-10):</label>
            <input type="range" min="1" max="10" step="0.1" name="peligrosidad" id="peligrosidad" 
                   value="<?= isset($entidad) ? $entidad->getPeligrosidad() : '5' ?>" 
                   oninput="document.getElementById('valorPeligrosidad').innerHTML = this.value" required>
            <span id="valorPeligrosidad"><?= isset($entidad) ? $entidad->getPeligrosidad() : '5' ?></span>
            <small>1 = Inofensivo, 10 = Extremadamente peligroso</small>
        </div>
        
        <!-- Campos ESPECÍFICOS (se muestran según tipo seleccionado) -->
        
        <!-- Forma de Vida -->
        <div id="camposFormaVida" class="especifico" style="display: none;">
            <h3>📋 Datos específicos de Forma de Vida</h3>
            <div class="campo">
                <label for="dieta">Tipo de Dieta:</label>
                <input type="text" name="dieta" id="dieta" 
                       value="<?= isset($entidad) && get_class($entidad) === 'FormaDeVida' ? htmlspecialchars($entidad->getDieta()) : '' ?>"
                       placeholder="Ej: Carnívoro, Herbívoro, Energético">
            </div>
            <div class="campo">
                <label for="estructura_osea">Estructura Ósea:</label>
                <input type="text" name="estructura_osea" id="estructura_osea" 
                       value="<?= isset($entidad) && get_class($entidad) === 'FormaDeVida' ? htmlspecialchars($entidad->getEstructuraOsea()) : '' ?>"
                       placeholder="Ej: Endoesqueleto, Exoesqueleto, Flexible">
            </div>
        </div>
        
        <!-- Mineral -->
        <div id="camposMineral" class="especifico" style="display: none;">
            <h3>💎 Datos específicos de Mineral</h3>
            <div class="campo">
                <label for="composicion">Composición Química:</label>
                <input type="text" name="composicion" id="composicion" 
                       value="<?= isset($entidad) && get_class($entidad) === 'Minerales' ? htmlspecialchars($entidad->getComposicionQuimica()) : '' ?>"
                       placeholder="Ej: SiO₂, Fe₃O₄, C (diamante)">
            </div>
            <div class="campo">
                <label for="dureza">Dureza (Escala de Mohs):</label>
                <input type="number" step="0.1" min="1" max="10" name="dureza" id="dureza" 
                       value="<?= isset($entidad) && get_class($entidad) === 'Minerales' ? $entidad->getDureza() : '1' ?>"
                       placeholder="1 (talco) a 10 (diamante)">
            </div>
        </div>
        
        <!-- Climatología -->
        <div id="camposClima" class="especifico" style="display: none;">
            <h3>🌪️ Datos específicos de Climatología</h3>
            <div class="campo">
                <label for="temperatura">Temperatura Media (°C):</label>
                <input type="number" step="0.1" name="temperatura" id="temperatura" 
                       value="<?= isset($entidad) && get_class($entidad) === 'Climatologia' ? $entidad->getTemperaturaMedia() : '0' ?>"
                       placeholder="Ej: -89.2 (Antártida), 58 (Valle de la Muerte)">
            </div>
            <div class="campo">
                <label for="presion">Presión Atmosférica (hPa):</label>
                <input type="number" step="0.1" name="presion" id="presion" 
                       value="<?= isset($entidad) && get_class($entidad) === 'Climatologia' ? $entidad->getPresionAtmosferica() : '1013' ?>"
                       placeholder="1013 hPa = presión al nivel del mar en Tierra">
            </div>
        </div>
        
        <!-- Botón de Guardar -->
        <div class="campo">
            <button type="submit" id="btnGuardar" style="display: none;">
                <?= isset($entidad) ? '💾 Actualizar Entidad' : '🚀 Crear Entidad' ?>
            </button>
            <a href="index.php?action=index" style="margin-left: 20px;">❌ Cancelar</a>
        </div>
        
        <div class="campo">
            <small><strong>Nota:</strong> Todos los campos son obligatorios. Primero selecciona el tipo de entidad.</small>
        </div>
    </form>
</body>
</html>