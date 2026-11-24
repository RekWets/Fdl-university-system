<?php include($_SERVER['DOCUMENT_ROOT']."/fdl/app/views/partials/header.php"); ?>

<?php 
if (!empty($data)) {
    $key = $data['registro'];
    $form = $data['formacion_academica'] ?? [];
    $docs = $data['documentos'] ?? [];
    $hijos = $data['hijos'] ?? [];
    
    // Definimos la ruta base de los documentos para este usuario
    $folderPath = "/fdl/documentos/" . htmlspecialchars($key['id']) . "/";
?>

<section class="container d-flex justify-content-between align-items-center my-3">
    <h2 class="mb-0">Previsualizar Registro: <?= htmlspecialchars($key['nombres'] . " " . $key['apellidos']) ?></h2>
    <div>
        <form method="POST" action="?route=registro&method=ficha" target="_blank" style="display:inline;">
            <input type="hidden" name="clave" value="<?= htmlspecialchars($key['cedula']) ?>">
            <button class="btn btn-sm btn-success"><i class="fa fa-file-pdf"></i> Ficha PDF</button>
        </form>
        <a href="?route=registro&method=index" class="btn btn-sm btn-secondary">
            <i class="fa fa-arrow-left"></i> Regresar
        </a>
    </div>
</section>
<hr>

<section class="container p-4 bg-white shadow-sm rounded" style="max-width: 800px;">
    <div class="row g-2">
        <div class="col-6">
            <strong>Cédula:</strong>
            <p class="mb-1 border-bottom"><?= htmlspecialchars($key['cedula']) ?></p>
        </div>
        <div class="col-6">
            <strong>Nombres y Apellidos:</strong>
            <p class="mb-1 border-bottom"><?= htmlspecialchars($key['nombres'] . " " . $key['apellidos']) ?></p>
        </div>
        
        <div class="col-12 mt-3">
            <h5 class="text-primary border-bottom">Documentos Consignados</h5>
            <div class="row">
                <?php 
                // Mapeo de campos de la base de datos a etiquetas legibles
                $listaDocs = [
                    'sintesis' => 'Síntesis Curricular',
                    'cedula_doc' => 'Copia de Cédula',
                    'rif' => 'RIF Actualizado',
                    'partida' => 'Partida de Nacimiento',
                    'titulo_negro' => 'Título Académico',
                    'permisos' => 'Permisos Laborales',
                    'salud' => 'Certificado de Salud'
                ];

                foreach ($listaDocs as $campo => $label): 
                    $archivo = $docs[$campo] ?? '';
                    $rutaArchivo = $folderPath . $archivo;
                ?>
                    <div class="col-md-6 mb-2">
                        <strong><?= $label ?>:</strong><br>
                        <?php if (!empty($archivo) && $archivo !== 'Ninguno'): ?>
                            <a href="<?= $rutaArchivo ?>" target="_blank" class="btn btn-link btn-sm p-0 text-decoration-none">
                                <i class="fa fa-external-link-alt"></i> Ver <?= htmlspecialchars($archivo) ?>
                            </a>
                        <?php else: ?>
                            <span class="text-danger small"><i class="fa fa-times-circle"></i> No consignado</span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php } else { ?>
    <div class="container mt-5">
        <div class="alert alert-warning">No se encontraron datos para mostrar.</div>
    </div>
<?php } ?>

<?php include($_SERVER['DOCUMENT_ROOT']."/fdl/app/views/partials/footer.php"); ?>