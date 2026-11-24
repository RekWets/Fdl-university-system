</div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <!-- <span>Copyright &copy; UPTT "Mario Briceño Iragorry" 2025</span> -->
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">¿Estás seguro(a) de cerrar sesión?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Selecciona el botón "Salir" para confirmar el cierre de sesión.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="?route=auth&method=logout">Salir</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap core JavaScript-->
    <script src="public/vendor/jquery/jquery.min.js"></script>
    <script src="public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="public/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="public/js/sb-admin-2.min.js"></script>
    <script src="public/js/jquery.dataTables.min.js"></script>
    <script src="public/js/dataTables.bootstrap4.min.js"></script>
    <script src="public/js/datatables-spanish.js"></script>
    <script src="public/js/emp.js"></script>
    <script src="public/js/functions.js"></script>
    <script src="public/js/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="public/js/sweetalert2/sweetalert2.all.js"></script>
    <script src="public/js/sweetalert2/sweetalert2.min.js"></script>
    <script src="public/js/sweetalert2/sweetalert2.js"></script>
    

    <script>
        $(document).ready(function () {
            $('.table').DataTable({language: translate});
        });
        
    </script> 
    <script>
$(document).ready(function () {
    var table = $('.table').DataTable();

    // Filtro personalizado global
    $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
        var selectedCargo = $('#filter-cargo').val();
        var selectedDireccion = $('#filter-direccion').val();

        var cargo = data[10]; // índice columna "cargo"
        var direccion = data[7]; // índice columna "ubicación administrativa"

        var cargoMatch = selectedCargo === "" || cargo === selectedCargo;
        var direccionMatch = selectedDireccion === "" || direccion === selectedDireccion;

        return cargoMatch && direccionMatch;
    });

    // Cuando cambian los select, redibuja la tabla
    $('#filter-cargo, #filter-direccion').on('change', function () {
        table.draw();
    });
});
</script>
<!-- Script de funcionalidad del formulario multistep -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    let currentStep = 0;
    const steps = document.querySelectorAll('.step');
    const form = document.getElementById('multiStepForm');
    const circleIds = ['circle1', 'circle2', 'circle3', 'circle4', 'circle5'];
    const lineIds = ['line1', 'line2', 'line3', 'line4'];

    // Mostrar paso actual
    function showStep(n) {
        steps.forEach((step, i) => {
            step.classList.toggle('active', i === n);
        });

        document.getElementById("prevBtn").style.display = n === 0 ? "none" : "inline";
        document.getElementById("nextBtn").innerText = n === steps.length - 1 ? "Guardar" : "Siguiente";

        if (n === steps.length - 1) showResumen();
        updateIndicators();
    }

    // Cambiar paso anterior/siguiente
    window.nextPrev = function (n) {
        if (n === 1 && !validateStep(currentStep)) return;

        currentStep += n;

        if (currentStep >= steps.length) {
            form.submit();
            return;
        }

        showStep(currentStep);
    }

    // Validar campos requeridos del paso actual
    function validateStep(index) {
        const inputs = steps[index].querySelectorAll("input, select");
        for (let input of inputs) {
            if (!input.checkValidity()) {
                input.reportValidity();
                return false;
            }
        }
        return true;
    }

    // Actualizar los círculos e indicadores
    function updateIndicators() {
        circleIds.forEach((id, index) => {
            const el = document.getElementById(id);
            el.classList.remove('active', 'completed');
            if (index < currentStep) el.classList.add('completed');
            else if (index === currentStep) el.classList.add('active');
        });

        lineIds.forEach((id, index) => {
            const line = document.getElementById(id);
            line.classList.toggle('active', index < currentStep);
        });
    }

    // Generar inputs de hijos dinámicamente
    window.generarHijosInputs = function () {
        const cantidad = parseInt(document.getElementById('cantidad_hijos').value) || 0;
        const container = document.getElementById('hijosContainer');
        container.innerHTML = '';

        for (let i = 1; i <= cantidad; i++) {
            const grupo = document.createElement('div');
            grupo.classList.add('form-group');

            // Nombre del hijo
            const labelNombre = document.createElement('label');
            labelNombre.textContent = `Nombre del hijo #${i}`;
            const inputNombre = document.createElement('input');
            inputNombre.name = `nombre_hijo_${i}`;
            inputNombre.id = `nombre_hijo_${i}`;
            inputNombre.type = 'text';

            // Fecha de nacimiento del hijo
            const labelFecha = document.createElement('label');
            labelFecha.textContent = `Fecha de nacimiento del hijo #${i}`;
            const inputFecha = document.createElement('input');
            inputFecha.name = `fecha_hijo_${i}`;
            inputFecha.id = `fecha_hijo_${i}`;
            inputFecha.type = 'date';

            grupo.appendChild(labelNombre);
            grupo.appendChild(inputNombre);
            grupo.appendChild(labelFecha);
            grupo.appendChild(inputFecha);
            container.appendChild(grupo);
        }
    }

    // Mostrar resumen final del formulario
    function showResumen() {
        const resumen = document.getElementById('resumen');
        const data = new FormData(form);
        let html = '<ul>';

        for (let [key, value] of data.entries()) {
            const displayValue = value instanceof File ? (value.name || '(No seleccionado)') : value;
            html += `<li><strong>${key.replace(/_/g, ' ')}:</strong> ${displayValue}</li>`;
        }

        html += '</ul>';
        resumen.innerHTML = html;
    }

    // Rellenar automáticamente campos ocultos
    (function autofillHiddenFields() {
        const today = new Date().toISOString().split('T')[0];

        // Fecha de ingreso automática
        let fechaIngreso = document.getElementById('fecha_ingreso');
        if (!fechaIngreso) {
            fechaIngreso = document.createElement('input');
            fechaIngreso.type = 'hidden';
            fechaIngreso.name = 'fecha_ingreso';
            fechaIngreso.id = 'fecha_ingreso';
            form.appendChild(fechaIngreso);
        }
        fechaIngreso.value = today;

        // Dirección sincronizada con lugar_nacimiento
        let direccion = document.getElementById('direccion');
        if (!direccion) {
            direccion = document.createElement('input');
            direccion.type = 'hidden';
            direccion.name = 'direccion';
            direccion.id = 'direccion';
            form.appendChild(direccion);
        }

        const lugar = document.getElementById('lugar_nacimiento');
        if (lugar) {
            direccion.value = lugar.value;
            lugar.addEventListener('input', () => direccion.value = lugar.value);
        }
    })();

    // Inicializar formulario
    showStep(currentStep);
});
</script>
<script>
document.getElementById('miFormulario').addEventListener('submit', function(e) {
    e.preventDefault(); // evita recargar la página

    const formData = new FormData(this);

    fetch('index.php?controlador=registro&accion=actualizar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Actualizado!',
                text: data.message
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error de red',
            text: 'No se pudo conectar con el servidor.'
        });
        console.error(error);
    });
});
</script>
<?php if (!empty($errorMensaje)): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error al actualizar',
        text: '<?= addslashes($errorMensaje) ?>'
    });
    
</script>
<?php endif; ?>


</html>
