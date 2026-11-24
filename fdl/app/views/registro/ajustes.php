<?php include($_SERVER['DOCUMENT_ROOT']."/fdl/app/views/partials/header.php");?>
<?php if(isset($_GET['msg'])): ?>
    <div class="alert alert-<?php echo ($_GET['msg'] == 'success') ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
        <?php 
            switch($_GET['msg']){
                case 'success': echo "¡Contraseña actualizada correctamente!"; break;
                case 'campos_vacios': echo "Por favor, llene todos los campos."; break;
                case 'error_match': echo "Las nuevas contraseñas no coinciden."; break;
                case 'success': echo "Cambio exitoso."; break;
                default: echo urldecode($_GET['msg']); break;
            }
        ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>
<style>
    /* Reutilizamos tus estilos del Dashboard */
    .table-responsive-custom {
        overflow-x: auto;
        background: white;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .table thead th {
        background-color: #d17700; /* El naranja que usas */
        color: white;
        white-space: nowrap;
        font-size: 0.85rem;
        text-transform: uppercase;
        border: none;
    }

    .table tbody td {
        font-size: 0.85rem;
        vertical-align: middle;
    }

    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
    }

    .btn-orange {
        background-color: #d17700;
        color: white;
        border: none;
    }

    .btn-orange:hover {
        background-color: #b56600;
        color: white;
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h3 class="h3 mb-0 text-gray-800">Ajustes del Sistema</h3>
        <a href="?route=registro&method=inactivos" class="btn btn-warning btn-sm shadow-sm">
            <i class="fa fa-archive fa-sm text-white-50"></i> Ver Registros Inactivos
        </a>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Seguridad: Cambiar Contraseña</h6>
                </div>
                <div class="card-body">
                    <form action="?route=registro&method=cambiarPassword" method="POST" id="formPassword">
                        <div class="form-group">
                            <label class="font-weight-bold">Contraseña Actual</label>
                            <input type="password" name="old_pass" class="form-control" placeholder="********" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Nueva Contraseña</label>
                            <input type="password" name="new_pass" id="new_pass" class="form-control" placeholder="Nueva" required>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Confirmar Nueva Contraseña</label>
                            <input type="password" name="confirm_pass" id="confirm_pass" class="form-control" placeholder="Repetir" required>
                        </div>
                        <button type="submit" class="btn btn-orange btn-block shadow-sm">
                            <i class="fa fa-key"></i> Actualizar Contraseña
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7">
            <div class="table-responsive-custom">
                <div class="mb-3">
                    <h6 class="m-0 font-weight-bold text-primary">Usuarios con acceso al sistema</h6>
                    <!-- <small class="text-muted">Lista de cuentas activas (excluyendo administrador)</small> -->
                </div>
                
                <table class="table table-hover table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Correo Electrónico</th>
                            <th class="text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($usuarios)): ?>
                            <?php foreach($usuarios as $user): ?>
                                <?php if($user['username'] !== 'admin'): ?>
                                    <tr>
                                        <td class="font-weight-bold"><?php echo $user['username']; ?></td>
                                        <td><?php echo $user['email'] ?? 'No asignado'; ?></td>
                                        <td class="text-center">
                                            <span class="badge badge-success">
                                                <i class="fa fa-check"></i> Activo
                                            </span>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No hay otros usuarios registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('formPassword').onsubmit = function(e) {
    var pass = document.getElementById('new_pass').value;
    var confirm = document.getElementById('confirm_pass').value;

    if (pass !== confirm) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Contraseñas no coinciden',
            text: 'Por favor, asegúrese de que la nueva contraseña y la confirmación sean iguales.',
            confirmButtonColor: '#d17700'
        });
    }
};
</script>

<?php include($_SERVER['DOCUMENT_ROOT']."/fdl/app/views/partials/footer.php");?>