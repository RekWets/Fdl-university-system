<?php
require_once($_SERVER['DOCUMENT_ROOT']."/fdl/app/models/registro.php");

class registroController{
    private $model;

    public function __construct()
    {
        $this->model = new registro();

    }

    public function dashboard()
    {
        $title = "dashboard";
        $rows = $this->model->get();
        include('app/views/registro/dashboard.php');
    }

 public function registro()
    {
        $title = "registro";
        $rows = $this->model->get();
        include('app/views/registro/registro.php');
    }
public function consulta($form)
{
    $title = "consulta";
  $clave = $form['clave'] ?? null;
    $data = $this->model->consulta($form);
    include('app/views/registro/consulta.php');
}
    
public function ficha($form)
{
    $clave = $form['clave'] ?? null;
    $data = $this->model->consulta($form);
    if (empty($data) || isset($data['error'])) {
        die("Error: No se encontraron datos para la cédula " . htmlspecialchars($clave));
    }
    require_once('app/views/registro/ficha.php');
    $pdfGenerator = new ficha(); 
    $pdfGenerator->pdf($data);
}

    public function editar($form) {
         $title = "Editar Registro";
    $clave = $form['clave'] ?? null;
    $data = $this->model->consulta($form);

    if (!$data) {
        echo "Cliente no encontrado.";
        return;
    }

    include('app/views/registro/editar.php');
}
public function guardar()
{
    $title = "registro";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $result = $this->model->guardar($_POST, $_FILES);

        if ($result === true) {
            header("Location: index.php?route=registro&method=dashboard&msg=success");
            exit;
        } else {
            // Muestra el mensaje de error real (asegúrate que $result es string con error)
echo "<script>
    Swal.fire({
        icon: 'error',
        title: 'Error al guardar el formulario',
        ,
        confirmButtonText: 'Aceptar'
    });
</script>";

        }
    }

    include('app/views/registro/registro.php');
}
public function actualizar()
{
    $title = "Editar Registro";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Llama al modelo y obtiene resultado (true o mensaje error)
        $resultado = $this->model->actualizar($_POST, $_FILES);

        if ($resultado === true) {
            // Si todo salió bien, redirige a dashboard con mensaje de éxito
            header("Location: index.php?route=registro&method=dashboard&msg=edit_success");
            exit;
        } else {
            // Si hubo error, guarda mensaje para mostrar en la vista
            $errorMensaje = $resultado;
        }
    }

    // Si es GET o hubo error, carga datos para el formulario de edición
    $form = ['clave' => $_GET['cedula'] ?? null]; // Asegúrate que 'cedula' venga
    $rows = $this->model->consulta($form);
    $cliente = $rows[0] ?? null;

    // Incluye la vista, pasándole el cliente y el posible error
    include('app/views/registro/editar.php');
}


}
