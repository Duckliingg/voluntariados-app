<style>
    .asistencia-container {
        background: #ffffff;
        border-radius: 12px;
        padding: 2.5rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        margin: 2rem auto;
        max-width: 1200px;
        border: 1px solid #e2e8f0;
    }
    
    .asistencia-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 3px solid #2d5a9e;
    }
    
    .asistencia-title {
        color: #1a365d;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .asistencia-subtitle {
        color: #666666;
        margin: 0;
    }
    
    .stats-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-box {
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        padding: 25px;
        border-radius: 10px;
        text-align: center;
        border-left: 5px solid #2d5a9e;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    
    .stat-box.stat-total {
        border-left-color: #4a7bc8;
    }
    
    .stat-box.stat-presente {
        border-left-color: #28a745;
    }
    
    .stat-box.stat-ausente {
        border-left-color: #dc3545;
    }
    
    .stat-label {
        color: #666666;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 10px;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1a365d;
    }
    
    .stat-number.green {
        color: #28a745;
    }
    
    .stat-number.red {
        color: #dc3545;
    }
    
    .tabla-asistencia {
        width: 100%;
        border-collapse: collapse;
        background: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .tabla-asistencia thead {
        background: linear-gradient(135deg, #1a365d, #2d5a9e);
    }
    
    .tabla-asistencia th {
        padding: 16px;
        text-align: left;
        color: #ffffff;
        font-weight: 600;
        font-size: 1rem;
    }
    
    .tabla-asistencia th:last-child {
        text-align: center;
    }
    
    .tabla-asistencia td {
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .tabla-asistencia tbody tr:hover {
        background: #f8f9fa;
    }
    
    .tabla-asistencia tbody tr:last-child td {
        border-bottom: none;
    }
    
    .estudiante-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .avatar {
        width: 40px;
        height: 40px;
        background: #2d5a9e;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 1.2rem;
        font-weight: bold;
    }
    
    .email-text {
        color: #666666;
        font-size: 0.95rem;
    }
    
    .asistencia-control {
        text-align: center;
    }
    
    .checkbox-label {
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .checkbox-input {
        width: 20px;
        height: 20px;
        cursor: pointer;
    }
    
    .badge-status {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }
    
    .badge-ausente {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .badge-presente {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .acciones {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        justify-content: flex-end;
    }
    
    .btn {
        padding: 14px 32px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 1.1rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }
    
    .btn-secondary {
        background: #2d5a9e;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(45, 90, 158, 0.3);
    }
    
    .btn-secondary:hover {
        background: #1a365d;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(45, 90, 158, 0.4);
    }
    
    .btn-success {
        background: #28a745;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    }
    
    .btn-success:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    }
    
    .mensaje-info {
        background: #d1ecf1;
        color: #0c5460;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #bee5eb;
        border-left: 4px solid #4a7bc8;
        font-size: 1rem;
    }
    
    @media (max-width: 768px) {
        .asistencia-container {
            padding: 1.5rem;
        }
        
        .asistencia-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .stats-container {
            grid-template-columns: 1fr;
        }
        
        .tabla-asistencia {
            font-size: 0.9rem;
        }
        
        .acciones {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="asistencia-container">
    <div class="asistencia-header">
        <div>
            <h2 class="asistencia-title">Tomar Asistencia</h2>
            <p class="asistencia-subtitle">Selecciona los estudiantes que asistieron a la actividad</p>
        </div>
        <a href="index.php?action=convocatorias" class="btn btn-secondary">← Volver</a>
    </div>

    <?php if (empty($estudiantes)): ?>
        <div class="mensaje-info">
            <strong>No hay estudiantes</strong><br>
            No hay estudiantes con postulaciones aprobadas para esta convocatoria.
        </div>
    <?php else: ?>
        <form method="POST" action="" id="formAsistencia">
            
            <!-- Estadísticas -->
            <div class="stats-container">
                <div class="stat-box stat-total">
                    <div class="stat-label">Total de Estudiantes</div>
                    <div class="stat-number"><?php echo count($estudiantes); ?></div>
                </div>
                <div class="stat-box stat-presente">
                    <div class="stat-label">Presentes</div>
                    <div class="stat-number green" id="contadorPresentes">0</div>
                </div>
                <div class="stat-box stat-ausente">
                    <div class="stat-label">Ausentes</div>
                    <div class="stat-number red" id="contadorAusentes"><?php echo count($estudiantes); ?></div>
                </div>
            </div>

            <!-- Tabla -->
            <table class="tabla-asistencia">
                <thead>
                    <tr>
                        <th style="width: 40%;">Estudiante</th>
                        <th style="width: 35%;">Email</th>
                        <th style="width: 25%;">Asistencia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($estudiantes as $est): ?>
                    <tr>
                        <td>
                            <div class="estudiante-info">
                                <div class="avatar">
                                    <?php echo strtoupper(substr($est['nombre'], 0, 1)); ?>
                                </div>
                                <strong><?php echo htmlspecialchars($est['nombre']); ?></strong>
                            </div>
                        </td>
                        <td>
                            <span class="email-text">
                                <?php echo htmlspecialchars($est['email']); ?>
                            </span>
                        </td>
                        <td class="asistencia-control">
                            <label class="checkbox-label">
                                <input type="checkbox" 
                                       name="asistencia[<?php echo $est['id']; ?>]" 
                                       value="1" 
                                       class="checkbox-input asistencia-check"
                                       id="est_<?php echo $est['id']; ?>">
                                <span class="badge-status badge-ausente">Ausente</span>
                                <span class="badge-status badge-presente" style="display: none;">Presente</span>
                            </label>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Botones -->
            <div class="acciones">
                <a href="index.php?action=convocatorias" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-success">Guardar Asistencia</button>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.asistencia-check');
    const contadorPresentes = document.getElementById('contadorPresentes');
    const contadorAusentes = document.getElementById('contadorAusentes');
    const totalEstudiantes = <?php echo count($estudiantes) ?? 0; ?>;
    
    function actualizarContadores() {
        const presentes = document.querySelectorAll('.asistencia-check:checked').length;
        const ausentes = totalEstudiantes - presentes;
        
        if (contadorPresentes) contadorPresentes.textContent = presentes;
        if (contadorAusentes) contadorAusentes.textContent = ausentes;
    }
    
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const label = this.closest('.checkbox-label');
            const badgeAusente = label.querySelector('.badge-ausente');
            const badgePresente = label.querySelector('.badge-presente');
            
            if (this.checked) {
                badgeAusente.style.display = 'none';
                badgePresente.style.display = 'inline-block';
            } else {
                badgeAusente.style.display = 'inline-block';
                badgePresente.style.display = 'none';
            }
            
            actualizarContadores();
        });
    });
    
    const form = document.getElementById('formAsistencia');
    if (form) {
        form.addEventListener('submit', function(e) {
            const presentes = document.querySelectorAll('.asistencia-check:checked').length;
            
            if (presentes === 0) {
                e.preventDefault();
                alert('Debes marcar al menos un estudiante como presente.');
                return false;
            }
            
            const confirmar = confirm('¿Confirmas registrar la asistencia de ' + presentes + ' estudiante(s)?');
            if (!confirmar) {
                e.preventDefault();
                return false;
            }
        });
    }
});
</script>