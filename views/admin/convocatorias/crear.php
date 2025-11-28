<?php
// views/admin/convocatorias/crear.php
// Variables: $error, $postData, $success, $docentes, $insignias

$userType = 'admin';
include __DIR__ . '/../../layouts/header.php';
?>

<h2 class="section-title">Nueva Convocatoria</h2>

<?php if(isset($success) && $success): ?>
    <div class="mensaje-exito"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<?php if(isset($error) && $error): ?>
    <div class="mensaje-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label class="form-label">Título *</label>
        <input type="text" name="titulo" class="form-control" required
               value="<?php echo isset($postData['titulo']) ? htmlspecialchars($postData['titulo']) : ''; ?>">
    </div>

    <div class="form-group">
        <label class="form-label">Descripción *</label>
        <textarea name="descripcion" class="form-control" rows="5" required><?php echo isset($postData['descripcion']) ? htmlspecialchars($postData['descripcion']) : ''; ?></textarea>
    </div>

    <div class="form-group">
        <label class="form-label">Lugar</label>
        <input type="text" name="lugar" class="form-control"
               value="<?php echo isset($postData['lugar']) ? htmlspecialchars($postData['lugar']) : ''; ?>">
    </div>

    <div class="form-group">
        <label class="form-label">Docente Responsable</label>
        <select name="docente_id" class="form-control">
            <option value="">-- Seleccionar Docente --</option>
            <?php if (!empty($docentes)): ?>
                <?php foreach ($docentes as $docente): ?>
                <option value="<?php echo $docente['id']; ?>"
                        <?php echo (isset($postData['docente_id']) && $postData['docente_id'] == $docente['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($docente['nombre']); ?> - <?php echo htmlspecialchars($docente['email']); ?>
                </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="">No hay docentes registrados</option>
            <?php endif; ?>
        </select>
        <small>Asigna un docente para que pueda tomar asistencia de esta convocatoria</small>
    </div>

    <div class="form-group">
        <label class="form-label">Contacto Responsable</label>
        <input type="text" name="contacto_responsable" class="form-control"
               value="<?php echo isset($postData['contacto_responsable']) ? htmlspecialchars($postData['contacto_responsable']) : ''; ?>">
    </div>

    <div class="form-group">
        <label class="form-label">Teléfono Contacto</label>
        <input type="tel" name="telefono_contacto" class="form-control"
               value="<?php echo isset($postData['telefono_contacto']) ? htmlspecialchars($postData['telefono_contacto']) : ''; ?>">
    </div>

    <div class="form-group">
        <label class="form-label">Link WhatsApp Grupo</label>
        <input type="url" name="whatsapp_grupo" class="form-control"
               value="<?php echo isset($postData['whatsapp_grupo']) ? htmlspecialchars($postData['whatsapp_grupo']) : ''; ?>">
    </div>

    <div class="form-group">
        <label class="form-label">Tipo de Voluntariado</label>
        <select name="tipo_voluntariado" class="form-control">
            <option value="social" <?php echo (isset($postData['tipo_voluntariado']) && $postData['tipo_voluntariado'] == 'social') ? 'selected' : ''; ?>>Social</option>
            <option value="ambiental" <?php echo (isset($postData['tipo_voluntariado']) && $postData['tipo_voluntariado'] == 'ambiental') ? 'selected' : ''; ?>>Ambiental</option>
            <option value="educativo" <?php echo (isset($postData['tipo_voluntariado']) && $postData['tipo_voluntariado'] == 'educativo') ? 'selected' : ''; ?>>Educativo</option>
            <option value="salud" <?php echo (isset($postData['tipo_voluntariado']) && $postData['tipo_voluntariado'] == 'salud') ? 'selected' : ''; ?>>Salud</option>
            <option value="comunitario" <?php echo (isset($postData['tipo_voluntariado']) && $postData['tipo_voluntariado'] == 'comunitario') ? 'selected' : ''; ?>>Comunitario</option>
        </select>
    </div>

    <!--Selector de Insignia -->
    <div class="form-group insignia-selector">
        <label class="form-label">
            Insignia a Otorgar 
            <span class="badge-info">Opcional</span>
        </label>
        <select name="insignia_id" class="form-control" id="insigniaSelect">
            <option value="">-- Sin insignia --</option>
            <?php if (!empty($insignias)): ?>
                <?php foreach ($insignias as $insignia): ?>
                <option value="<?php echo $insignia['_id']; ?>"
                        data-icono="<?php echo htmlspecialchars($insignia['icono']); ?>"
                        data-color="<?php echo htmlspecialchars($insignia['color']); ?>"
                        data-categoria="<?php echo htmlspecialchars($insignia['categoria']); ?>"
                        data-criterio="<?php echo $insignia['criterio_asistencia']; ?>"
                        <?php echo (isset($postData['insignia_id']) && $postData['insignia_id'] == $insignia['_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($insignia['icono']); ?> <?php echo htmlspecialchars($insignia['nombre']); ?> 
                    (<?php echo ucfirst($insignia['categoria']); ?> - <?php echo $insignia['criterio_asistencia']; ?>%)
                </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="">No hay insignias disponibles</option>
            <?php endif; ?>
        </select>
        <small>
            Los estudiantes recibirán esta insignia al completar la convocatoria con el porcentaje de asistencia requerido.
            <a href="../insignias/crear.php" target="_blank">Crear nueva insignia</a>
        </small>
        
        <!-- Vista previa de insignia -->
        <div id="insigniaPreview" class="insignia-preview-mini" style="display: none;">
            <div class="preview-icon" id="previewIcono"></div>
            <div class="preview-info">
                <span id="previewNombre"></span>
                <span id="previewCriterio" class="preview-criterio"></span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="form-label">Fecha Inicio *</label>
        <input type="date" name="fecha_inicio" class="form-control" required
               value="<?php echo isset($postData['fecha_inicio']) ? $postData['fecha_inicio'] : ''; ?>">
    </div>

    <div class="form-group">
        <label class="form-label">Fecha Fin *</label>
        <input type="date" name="fecha_fin" class="form-control" required
               value="<?php echo isset($postData['fecha_fin']) ? $postData['fecha_fin'] : ''; ?>">
    </div>

    <div class="form-group">
        <label class="form-label">Cupos Disponibles *</label>
        <input type="number" name="cupos" class="form-control" min="1" required
               value="<?php echo isset($postData['cupos']) ? $postData['cupos'] : ''; ?>">
    </div>

    <div class="form-group">
        <label class="form-label">Imagen</label>
        <input type="file" name="imagen" class="form-control" accept="image/*">
        <small>Formatos permitidos: JPG, JPEG, PNG. Máximo 5MB</small>
    </div>

    <button type="submit" class="btn">Crear Convocatoria</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<style>
.insignia-selector {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #3498db;
}

.badge-info {
    background: #3498db;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: normal;
}

.insignia-preview-mini {
    margin-top: 15px;
    padding: 15px;
    background: white;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.preview-icon {
    font-size: 2.5rem;
    padding: 15px;
    border-radius: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.preview-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.preview-info span:first-child {
    font-weight: bold;
    color: #2c3e50;
}

.preview-criterio {
    font-size: 0.85rem;
    color: #7f8c8d;
}
</style>

<script>
// Vista previa de insignia seleccionada
document.getElementById('insigniaSelect').addEventListener('change', function() {
    const preview = document.getElementById('insigniaPreview');
    const selectedOption = this.options[this.selectedIndex];
    
    if (this.value) {
        const icono = selectedOption.dataset.icono;
        const color = selectedOption.dataset.color;
        const categoria = selectedOption.dataset.categoria;
        const criterio = selectedOption.dataset.criterio;
        const nombre = selectedOption.textContent.trim();
        
        document.getElementById('previewIcono').textContent = icono;
        document.getElementById('previewIcono').style.background = color;
        document.getElementById('previewNombre').textContent = nombre;
        document.getElementById('previewCriterio').textContent = `Requiere ${criterio}% de asistencia`;
        
        preview.style.display = 'flex';
    } else {
        preview.style.display = 'none';
    }
});

// Trigger en caso de que venga seleccionado por POST
window.addEventListener('load', function() {
    document.getElementById('insigniaSelect').dispatchEvent(new Event('change'));
});
</script>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>