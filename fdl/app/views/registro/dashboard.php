<?php include($_SERVER['DOCUMENT_ROOT']."/fdl/app/views/partials/header.php");?>

<?php
// Lógica para filtros (se mantiene igual)
$cargosUnicos = [];
$ubicacionesUnicas = [];
if (!empty($rows)) {
    foreach ($rows as $row) {
        if (!empty($row['cargo']) && !in_array($row['cargo'], $cargosUnicos)) $cargosUnicos[] = $row['cargo'];
        if (!empty($row['direccion']) && !in_array($row['direccion'], $ubicacionesUnicas)) $ubicacionesUnicas[] = $row['direccion'];
    }
}
?>

<style>
    /* Estilos para que la tabla sea legible */
    .table-responsive-custom {
        overflow-x: auto; /* Permite scroll horizontal si la tabla es muy ancha */
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .table thead th {
        background-color: #d17700;
        color: white;
        white-space: nowrap; /* Evita que los títulos se corten en dos líneas */
        font-size: 0.85rem;
        text-transform: uppercase;
    }

    .table tbody td {
        font-size: 0.85rem;
        vertical-align: middle;
        white-space: nowrap; /* Evita que el texto largo deforme la fila */
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis; /* Agrega "..." si el texto es muy largo */
    }

    /* Estilo para las fotos */
    .img-table {
        border-radius: 5px;
        object-fit: cover;
        border: 1px solid #ddd;
    }

    .btn-action {
        padding: 2px 5px;
        background: none;
        border: none;
        transition: transform 0.2s;
    }

    .btn-action:hover {
        transform: scale(1.2);
    }
    /* Ajuste de anchos para ahorrar espacio */
.table td, .table th {
    padding: 0.5rem !important; /* Reduce el espacio interno */
    font-size: 0.8rem !important;
}

/* Forzar que la columna de opciones no se rompa */
.col-opciones {
    width: 100px !important;
    min-width: 100px !important;
    text-align: center;
}

/* Opcional: Hacer que la columna de Opciones sea fija a la derecha */
@media (min-width: 992px) {
    .table-responsive-custom {
        position: relative;
    }
    /* Esto fija la última columna si quieres que siempre esté a la vista */
    /*
    .table th:last-child, .table td:last-child {
        position: sticky;
        right: 0;
        background: #f8f9fc;
        z-index: 1;
        box-shadow: -2px 0 5px rgba(0,0,0,0.1);
    }
    */
}

/* Limitar ancho de celdas con mucho texto (como Dirección) */
.text-truncate-custom {
    max-width: 150px !important;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: inline-block;
}
</style>



    <div class="card mb-4">
        <div class="card-body bg-light">
            <div class="row align-items-center">
                <div class="col-md-auto mb-2">
                    <label class="font-weight-bold" for="filter-cargo">Cargo:</label>
                    <select id="filter-cargo" class="form-control d-inline-block" style="width: 200px;">
                        <option value="">Todos</option>
                        <?php foreach ($cargosUnicos as $cargo): ?>
                            <option value="<?= htmlspecialchars($cargo) ?>"><?= htmlspecialchars($cargo) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-auto mb-2">
                    <label class="font-weight-bold" for="filter-direccion">Dirección:</label>
                    <select id="filter-direccion" class="form-control d-inline-block" style="width: 200px;">
                        <option value="">Todas</option>
                        <?php foreach ($ubicacionesUnicas as $ubicacion): ?>
                            <option value="<?= htmlspecialchars($ubicacion) ?>"><?= htmlspecialchars($ubicacion) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive-custom">
        <table class="table table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cédula</th>
                    <th>Nombres</th>
                    <th>Apellidos</th>
                    <th>Sexo</th>
                    <th>Nacimiento</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Ingreso</th>
                    <th>Ubicación Adm.</th>
                    <th>Cargo</th>
                    <th>Egreso</th>
                    <th>Foto</th>
                    <th class="text-center">Opciones</th>
                </tr>
            </thead>
            <tbody id="tb-tables">
                <?php if (!empty($rows)) { 
                    foreach($rows as $key) { ?>
                    <tr>
                        <td><?php echo $key['id']; ?></td>
                        <td><?php echo $key['cedula']; ?></td>
                        <td><?php echo $key['nombres']; ?></td>
                        <td><?php echo $key['apellidos']; ?></td>
                        <td><?php echo $key['sexo']; ?></td>
                        <td><?php echo $key['fecha_nacimiento']; ?></td>
                        <td><?php echo $key['telefono']; ?></td>
                        <td title="<?php echo $key['direccion']; ?>"><?php echo $key['direccion']; ?></td>
                        <td><?php echo $key['fecha_ingreso']; ?></td>
                        <td><?php echo $key['ubicacion_adm']; ?></td>
                        <td><?php echo $key['cargo']; ?></td>
                        <td><?php echo $key['fecha_egreso']; ?></td>
                        <td class="text-center">
                            <?php if (!empty($key['foto'])): ?>
                                <img class="img-table" src="documentos/<?php echo $key['id']; ?>/<?php echo $key['foto']; ?>" width="50" height="50">
                            <?php else: ?>
                                <span class="badge badge-secondary">Sin foto</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
<form method="POST" action="?route=registro&method=ficha" target="_blank">
    <input type="hidden" name="clave" value="<?php echo $key['cedula']; ?>">
    <button type="submit" class="btn-action" title="Descargar Ficha PDF">
        <img height="22px" src="./public/img/preview.svg" alt="PDF">
    </button>
</form>
<form method="POST" action="?route=registro&method=consulta" style="display:inline;">
        <input type="hidden" name="clave" value="<?php echo htmlspecialchars($key['cedula']); ?>">
        <button type="submit" class="btn-action" title="Consultar / Previsualizar">
            <i class="fa fa-eye" style="font-size: 18px; color: #2c3e50;"></i>
        </button>
    </form>
                            <form method="POST" action="?route=registro&method=editar" style="display:inline;">
                                <input type="hidden" name="clave" value="<?php echo htmlspecialchars($key['cedula']); ?>">
                                <button type="submit" class="btn-action" title="Editar">
                                    <img height="20px" src="./public/img/editar.svg" alt="Editar">
                                </button>
                            </form>
<form id="form-eliminar-<?php echo $key['id']; ?>" method="POST" action="?route=registro&method=eliminar" style="display:inline;">
    <input type="hidden" name="id" value="<?php echo $key['id']; ?>">
    <button type="button" class="btn-action" onclick="confirmarEliminar('<?php echo $key['id']; ?>')" title="Eliminar">
        <i class="fa fa-trash" style="font-size: 18px; color: #e74c3c;"></i>
    </button>
</form>
                        </td>
                    </tr>
                <?php  }} ?>
            </tbody>
        </table>
    </div>
</div>

<?php include($_SERVER['DOCUMENT_ROOT']."/fdl/app/views/partials/footer.php");?>