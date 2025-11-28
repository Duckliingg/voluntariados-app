<?php
// views/admin/insignias/editar.php
$userType = 'admin';
include __DIR__ . '/../../layouts/header.php';
?>

<div class="page-header">
    <h2 class="section-title">Editar Insignia</h2>
    <a href="index.php" class="btn-secundario">← Volver</a>
</div>

<?php if(isset($error) && $error): ?>
    <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nombre de la Insignia *</label>
                <input type="text" name="nombre" class="form-control" required
                       placeholder="Ej: Guardián de la Salud"
                       value="<?php echo htmlspecialchars($insignia['nombre']); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Categoría *</label>
                <select name="categoria" class="form-control" required>
                    <option value="">Seleccionar categoría</option>
                    <option value="ambiental" <?php echo $insignia['categoria'] == 'ambiental' ? 'selected' : ''; ?>>🌱 Ambiental</option>
                    <option value="social" <?php echo $insignia['categoria'] == 'social' ? 'selected' : ''; ?>>🤝 Social</option>
                    <option value="educativo" <?php echo $insignia['categoria'] == 'educativo' ? 'selected' : ''; ?>>📚 Educativo</option>
                    <option value="salud" <?php echo $insignia['categoria'] == 'salud' ? 'selected' : ''; ?>>🏥 Salud</option>
                    <option value="comunitario" <?php echo $insignia['categoria'] == 'comunitario' ? 'selected' : ''; ?>>🏘️ Comunitario</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3"
                      placeholder="Describe qué representa esta insignia y cómo se obtiene"><?php echo htmlspecialchars($insignia['descripcion']); ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Icono (Emoji)</label>
                <input type="text" name="icono" class="form-control" 
                       placeholder="🏅" maxlength="4"
                       value="<?php echo htmlspecialchars($insignia['icono']); ?>">
                <small>Puedes usar emojis: 🏅 🏆 ⭐ 💪 🌟 🎖️ 👑 💎</small>
            </div>

            <div class="form-group">
                <label class="form-label">Color</label>
                <div class="color-picker-container">
                    <input type="color" name="color" id="colorPicker" 
                           value="<?php echo htmlspecialchars($insignia['color']); ?>">
                    <input type="text" id="colorValue" class="form-control" readonly
                           value="<?php echo htmlspecialchars($insignia['color']); ?>">
                </div>
                <small>Colores sugeridos: 
                    <span class="color-suggestion" data-color="#27ae60" style="background: #27ae60;" title="Verde"></span>
                    <span class="color-suggestion" data-color="#3498db" style="background: #3498db;" title="Azul"></span>
                    <span class="color-suggestion" data-color="#e74c3c" style="background: #e74c3c;" title="Rojo"></span>
                    <span class="color-suggestion" data-color="#f39c12" style="background: #f39c12;" title="Naranja"></span>
                    <span class="color-suggestion" data-color="#9b59b6" style="background: #9b59b6;" title="Morado"></span>
                </small>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Criterio de Asistencia (%)</label>
            <div class="range-container">
                <input type="range" name="criterio_asistencia" id="criterioRange" 
                       min="0" max="100" step="5"
                       value="<?php echo (int)$insignia['criterio_asistencia']; ?>">
                <span id="criterioValue"><?php echo (int)$insignia['criterio_asistencia']; ?>%</span>
            </div>
            <small>Porcentaje mínimo de asistencia requerido para obtener esta insignia</small>
        </div>

        <div class="form-group">
            <label class="form-label">
                <input type="checkbox" name="activa" value="1" <?php echo $insignia['activa'] ? 'checked' : ''; ?>>
                Insignia activa (visible para asignar a convocatorias)
            </label>
        </div>

        <!-- Vista previa -->
        <div class="form-group">
            <label class="form-label">Vista Previa</label>
            <div class="insignia-preview" id="preview">
                <div class="preview-header" id="previewHeader" style="background: <?php echo htmlspecialchars($insignia['color']); ?>;">
                    <span class="preview-icon" id="previewIcon"><?php echo htmlspecialchars($insignia['icono']); ?></span>
                </div>
                <div class="preview-body">
                    <h3 id="previewNombre"><?php echo htmlspecialchars($insignia['nombre']); ?></h3>
                    <p id="previewDescripcion"><?php echo htmlspecialchars($insignia['descripcion']); ?></p>
                    <span class="preview-criterio" id="previewCriterio">📊 <?php echo (int)$insignia['criterio_asistencia']; ?>% asistencia</span>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Guardar Cambios</button>
            <a href="index.php" class="btn-secundario">Cancelar</a>
        </div>
    </form>
</div>

<style>
.form-container {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.color-picker-container {
    display: flex;
    gap: 10px;
    align-items: center;
}

#colorPicker {
    width: 60px;
    height: 40px;
    border: 2px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
}

#colorValue {
    flex: 1;
}

.color-suggestion {
    display: inline-block;
    width: 30px;
    height: 30px;
    border-radius: 4px;
    cursor: pointer;
    margin: 0 5px;
    border: 2px solid #ddd;
    transition: transform 0.2s;
}

.color-suggestion:hover {
    transform: scale(1.1);
    border-color: #333;
}

.range-container {
    display: flex;
    align-items: center;
    gap: 15px;
}

#criterioRange {
    flex: 1;
}

#criterioValue {
    font-weight: bold;
    color: #2c3e50;
    min-width: 50px;
}

.insignia-preview {
    max-width: 300px;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.preview-header {
    padding: 30px 20px;
    text-align: center;
    transition: background 0.3s;
}

.preview-icon {
    font-size: 3rem;
}

.preview-body {
    padding: 20px;
}

.preview-body h3 {
    margin: 0 0 10px 0;
    color: #2c3e50;
}

.preview-body p {
    color: #7f8c8d;
    font-size: 0.9rem;
    margin-bottom: 15px;
    min-height: 40px;
}

.preview-criterio {
    display: inline-block;
    padding: 5px 10px;
    background: #f8f9fa;
    border-radius: 4px;
    font-size: 0.85rem;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 30px;
}

.btn-secundario {
    background: #95a5a6;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 4px;
    transition: background 0.2s;
}

.btn-secundario:hover {
    background: #7f8c8d;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Vista previa en tiempo real
const nombreInput = document.querySelector('input[name="nombre"]');
const descripcionInput = document.querySelector('textarea[name="descripcion"]');
const iconoInput = document.querySelector('input[name="icono"]');
const colorPicker = document.getElementById('colorPicker');
const colorValue = document.getElementById('colorValue');
const criterioRange = document.getElementById('criterioRange');
const criterioValue = document.getElementById('criterioValue');

// Actualizar vista previa
nombreInput.addEventListener('input', (e) => {
    document.getElementById('previewNombre').textContent = e.target.value || 'Nombre de la insignia';
});

descripcionInput.addEventListener('input', (e) => {
    document.getElementById('previewDescripcion').textContent = e.target.value || 'Descripción de la insignia';
});

iconoInput.addEventListener('input', (e) => {
    document.getElementById('previewIcon').textContent = e.target.value || '🏅';
});

colorPicker.addEventListener('input', (e) => {
    colorValue.value = e.target.value;
    document.getElementById('previewHeader').style.background = e.target.value;
});

criterioRange.addEventListener('input', (e) => {
    criterioValue.textContent = e.target.value + '%';
    document.getElementById('previewCriterio').textContent = '📊 ' + e.target.value + '% asistencia';
});

// Selector de colores sugeridos
document.querySelectorAll('.color-suggestion').forEach(span => {
    span.addEventListener('click', () => {
        const color = span.dataset.color;
        colorPicker.value = color;
        colorValue.value = color;
        document.getElementById('previewHeader').style.background = color;
    });
});
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>