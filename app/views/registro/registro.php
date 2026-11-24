<?php include($_SERVER['DOCUMENT_ROOT']."/fdl/app/views/partials/header.php");?>

<style> 

 form {
    max-width: 900px;      
    width: 90%;            
    margin: 30px auto;     
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
    }@media (min-width: 1200px) {
    form {
        max-width: 1000px;
    }
 }

        .step {
            display: none;
        }

        .step.active {
            display: block;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #444;
        }

        input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .steps-nav {
            text-align: center;
            margin-top: 20px;
        }

        .steps-nav button {
            padding: 10px 20px;
            margin: 0 5px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #prevBtn {
            background-color: #ccc;
        }

        #nextBtn {
            background-color: #d17700;
            color: white;
        }

        /* Barra de pasos */
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .step-circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: #e0e0e0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            position: relative;
        }

        .step-circle.active {
            background-color: #d17700;
            color: white;
        }

        .step-circle.completed {
            background-color: #d17700;
            color: white;
        }

        .step-line {
            flex-grow: 1;
            height: 3px;
            background-color: #e0e0e0;
            position: relative;
            top: 16px;
            margin: 0 5px;
        }

        .step-line.active {
            background-color: #d17700;
        }

        #resumen ul {
            padding-left: 20px;
        }

        #resumen li {
            margin-bottom: 5px;
        }

</style>
<section>
<form id="multiStepForm" action="index.php?route=registro&method=guardar" method="POST" enctype="multipart/form-data">


    <h2>Formulario</h2>

    <!-- Barra de pasos -->
    <div class="step-indicator">
        <div class="step-circle" id="circle1">1</div>
        <div class="step-line" id="line1"></div>
        <div class="step-circle" id="circle2">2</div>
        <div class="step-line" id="line2"></div>
        <div class="step-circle" id="circle3">3</div>
        <div class="step-line" id="line3"></div>
        <div class="step-circle" id="circle4">4</div>
        <div class="step-line" id="line4"></div>
        <div class="step-circle" id="circle5">5</div>
    </div>

    <!-- Paso 1 -->
    <div class="step active" id="step1">
        <h3>Paso 1: Información Personal</h3>
        <div class="form-group">
            <label for="apellidos">Apellidos</label>
            <input name="apellidos" id="apellidos" required>
            
        </div>
        <div class="form-group">
            <label for="nombres">Nombres</label>
            <input name="nombres" id="nombres" required>
        </div>
        <div class="form-group">
    <label for="sexo">Sexo</label>
    <select name="sexo" id="sexo" required class="custom-select"> 
        <option value="">*Seleccione</option>
        <option value="M">Masculino</option>
        <option value="F">Femenino</option>
    </select>
</div>

<div class="form-group">
    <label for="telefono">Teléfono</label>
    <input name="telefono" id="telefono" required>
</div>

        <div class="form-group">
            <label for="cedula">Cédula de Identidad</label>
            <input name="cedula" id="cedula" required>
        </div>
        <div class="form-group">
            <label for="fecha_nacimiento">Fecha de Nacimiento</label>
            <input name="fecha_nacimiento" type="date" id="fecha_nacimiento" required>
        </div>
        <div class="form-group">
            <label for="lugar_nacimiento">Lugar de Nacimiento</label>
            <input name="lugar_nacimiento" id="lugar_nacimiento" required>
        </div>
    </div>

    <!-- Paso 2 -->
    <div class="step" id="step2">
        <h3>Paso 2: Formación Académica</h3>
        <div class="form-group">
            <label for="titulo">Título Obtenido</label>
            <input name="titulo" id="titulo" required>
        </div>
        <div class="form-group">
            <label for="certificados">Certificados</label>
            <input name="certificados" id="certificados" required>
        </div>
        <div class="form-group">
            <label for="antecedentes">Antecedentes de servicios</label>
            <input name="antecedentes" id="antecedentes">
        </div>
        <div class="form-group">
            <label for="experiencia">Años de experiencia</label>
            <input name="experiencia" type="number" id="experiencia">
        </div>
    </div>

   <!-- Paso 3 -->
<div class="step" id="step3">
    <h3>Paso 3: Datos Familiares</h3>
    <div class="form-group">
        <label for="cantidad_hijos">Cantidad de Hijos</label>
        <input name="cantidad_hijos" type="number" id="cantidad_hijos" min="0" value="0" oninput="generarHijosInputs()">
    </div>

    <div id="hijosContainer"></div>
</div>


<!-- Paso 4 -->
<div class="step" id="step4">
    <h3>Paso 4: Documentos del Expediente (Opcionales)</h3>
    <div class="form-group">
        <label for="sintesis">Síntesis Curricular</label>
        <input type="file" name="sintesis" id="sintesis" accept=".pdf,.doc,.docx,.jpg,.png">
    </div>
    <div class="form-group">
        <label for="cedula_doc">Cédula de Identidad</label>
        <input type="file" name="cedula_doc" id="cedula_doc" accept=".pdf,.jpg,.png">
    </div>
    <div class="form-group">
        <label for="rif">RIF</label>
        <input type="file" name="rif" id="rif" accept=".pdf,.jpg,.png">
    </div>
    <div class="form-group">
        <label for="partida">Partida de Nacimiento</label>
        <input type="file" name="partida" id="partida" accept=".pdf,.jpg,.png">
    </div>
    <div class="form-group">
        <label for="titulo_negro">Título o Fondo Negro</label>
        <input type="file" name="titulo_negro" id="titulo_negro" accept=".pdf,.jpg,.png">
    </div>
    <div class="form-group">
        <label for="permisos">Antecedentes de Permisos</label>
        <input type="file" name="permisos" id="permisos" accept=".pdf,.jpg,.png">
    </div>
    <div class="form-group">
        <label for="salud">Certificado de Salud</label>
        <input type="file" name="salud" id="salud" accept=".pdf,.jpg,.png">
    </div>
    <input type="hidden" name="ubicacion_adm" value="No asignada">
<input type="hidden" name="cargo" value="Sin cargo">
<input type="hidden" name="foto" value="sin-foto.jpg">

</div>


    <!-- Paso 5 -->
    <div class="step" id="step5">
        <h3>Paso 5: Revisión Final</h3>
        <div id="resumen"></div>
    </div>

    <!-- Botones -->
    <div class="steps-nav">
        <button type="button" id="prevBtn" onclick="nextPrev(-1)">Anterior</button>
        <button type="button" id="nextBtn" onclick="nextPrev(1)">Siguiente</button>
    </div>
</form>
</section>



<?php include($_SERVER['DOCUMENT_ROOT']."/fdl/app/views/partials/footer.php");?>