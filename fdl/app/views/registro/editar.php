<?php include($_SERVER['DOCUMENT_ROOT']."/fdl/app/views/partials/header.php"); ?>
<section class="container d-flex ">
	<title>Editar Registro</title>
	<h2 class="">Editar Registro</h2>
	<a href="?route=registro&method=index" class="btn">
		<i class="fa fa-arrow-left"></i> Regresar
	</a>
</section>
<hr>
<section class="container p-4 bg-white">
	<?php if (!empty($data)) {
		$key = $data['registro'];
		$form = $data['formacion_academica'] ?? [];
		$docs = $data['documentos'] ?? [];
		$hijos = $data['hijos'] ?? [];
	?>
	<form class="row g-3" action="?route=registro&method=actualizar" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?= htmlspecialchars($key['id']) ?>">

		<div class="col-md-4 my-2">
			<label for="cedula">Cédula:</label>
			<input type="text" name="cedula" id="cedula" class="form-control" value="<?= htmlspecialchars($key['cedula']) ?>">
		</div>

		<div class="col-md-4 my-2">
			<label for="nombres">Nombres:</label>
			<input type="text" name="nombres" id="nombres" class="form-control" value="<?= htmlspecialchars($key['nombres']) ?>">
		</div>

		<div class="col-md-4 my-2">
			<label for="apellidos">Apellidos:</label>
			<input type="text" name="apellidos" id="apellidos" class="form-control" value="<?= htmlspecialchars($key['apellidos']) ?>">
		</div>

		<div class="col-md-4 my-2">
			<label for="sexo">Sexo:</label>
			<select name="sexo" id="sexo" class="form-control">
				<option value="M" <?= $key['sexo'] === 'M' ? 'selected' : '' ?>>Masculino</option>
				<option value="F" <?= $key['sexo'] === 'F' ? 'selected' : '' ?>>Femenino</option>
			</select>
		</div>

		<div class="col-md-4 my-2">
			<label for="fecha_nacimiento">Fecha de nacimiento:</label>
			<input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" value="<?= htmlspecialchars($key['fecha_nacimiento']) ?>">
		</div>

		<div class="col-md-4 my-2">
			<label for="telefono">Teléfono:</label>
			<input type="text" name="telefono" id="telefono" class="form-control" value="<?= htmlspecialchars($key['telefono']) ?>">
		</div>

		<div class="col-md-6 my-2">
			<label for="direccion">Dirección:</label>
			<input type="text" name="direccion" id="direccion" class="form-control" value="<?= htmlspecialchars($key['direccion']) ?>">
		</div>

		<div class="col-md-6 my-2">
			<label>Foto actual:</label><br>
            <input type="hidden" name="foto_actual" value="<?= htmlspecialchars($key['foto'] ?? '') ?>">


			<?php if (!empty($key['foto'])): ?>
				<img src="/fdl/documentos/<?= htmlspecialchars($key['id']) ?>/<?= htmlspecialchars($key['foto']) ?>" alt="Foto" width="100">
			<?php else: ?>
				<p>No hay foto subida</p>
			<?php endif; ?>
			<input type="file" name="foto" class="form-control mt-2">
		</div>

		<div class="col-md-4 my-2">
			<label for="fecha_ingreso">Fecha de ingreso:</label>
			<input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control" value="<?= htmlspecialchars($key['fecha_ingreso']) ?>">
		</div>

		<div class="col-md-4 my-2">
			<label for="fecha_egreso">Fecha de egreso:</label>
			<input type="date" name="fecha_egreso" id="fecha_egreso" class="form-control" value="<?= htmlspecialchars($key['fecha_egreso']) ?>">
		</div>

		<div class="col-md-4 my-2">
			<label for="ubicacion_adm">Ubicación administrativa:</label>
			<input type="text" name="ubicacion_adm" id="ubicacion_adm" class="form-control" value="<?= htmlspecialchars($key['ubicacion_adm']) ?>">
		</div>

		<div class="col-md-4 my-2">
			<label for="cargo">Cargo:</label>
			<input type="text" name="cargo" id="cargo" class="form-control" value="<?= htmlspecialchars($key['cargo']) ?>">
		</div>

		<!-- Formación académica -->
		<div class="col-md-4 my-2">
			<label for="titulo">Título obtenido:</label>
			<input type="text" name="titulo" id="titulo" class="form-control" value="<?= htmlspecialchars($form['titulo_obtenido'] ?? '') ?>">
		</div>
		<div class="col-md-4 my-2">
			<label for="certificados">Certificados:</label>
			<input type="text" name="certificados" id="certificados" class="form-control" value="<?= htmlspecialchars($form['certificados'] ?? '') ?>">
		</div>
		<div class="col-md-4 my-2">
			<label for="antecedentes">Antecedentes de servicios:</label>
			<input type="text" name="antecedentes" id="antecedentes" class="form-control" value="<?= htmlspecialchars($form['antecedentes_servicios'] ?? '') ?>">
		</div>
		<div class="col-md-4 my-2">
			<label for="experiencia">Años de experiencia:</label>
			<input type="number" name="experiencia" id="experiencia" class="form-control" value="<?= htmlspecialchars($form['experiencia'] ?? '') ?>">
		</div>

		<!-- Hijos -->
		<?php if (!empty($hijos)): ?>
			<?php foreach ($hijos as $i => $hijo): ?>
				<div class="col-md-6 my-2">
					<label for="nombre_hijo_<?= $i ?>">Nombre hijo <?= $i+1 ?>:</label>
					<input type="text" name="nombre_hijo_<?= $i ?>" id="nombre_hijo_<?= $i ?>" class="form-control" value="<?= htmlspecialchars($hijo['nombre']) ?>">
				</div>
				<div class="col-md-6 my-2">
					<label for="fecha_hijo_<?= $i ?>">Fecha nacimiento hijo <?= $i+1 ?>:</label>
					<input type="date" name="fecha_hijo_<?= $i ?>" id="fecha_hijo_<?= $i ?>" class="form-control" value="<?= htmlspecialchars($hijo['fecha_nacimiento']) ?>">
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<input type="hidden" name="cantidad_hijos" value="<?= count($hijos) ?>">

		<!-- Documentos -->
		<div class="col-md-6 my-2">
			<label>Documento Síntesis actual: <?= htmlspecialchars($docs['sintesis'] ?? 'Ninguno') ?></label>
			<input type="file" name="sintesis" class="form-control">
		</div>
		<div class="col-md-6 my-2">
			<label>Documento Cédula actual: <?= htmlspecialchars($docs['cedula_doc'] ?? 'Ninguno') ?></label>
			<input type="file" name="cedula_doc" class="form-control">
		</div>
		<div class="col-md-6 my-2">
			<label>RIF actual: <?= htmlspecialchars($docs['rif'] ?? 'Ninguno') ?></label>
			<input type="file" name="rif" class="form-control">
		</div>
		<div class="col-md-6 my-2">
			<label>Partida de nacimiento actual: <?= htmlspecialchars($docs['partida'] ?? 'Ninguno') ?></label>
			<input type="file" name="partida" class="form-control">
		</div>
		<div class="col-md-6 my-2">
			<label>Título negro actual: <?= htmlspecialchars($docs['titulo_negro'] ?? 'Ninguno') ?></label>
			<input type="file" name="titulo_negro" class="form-control">
		</div>
		<div class="col-md-6 my-2">
			<label>Permisos actuales: <?= htmlspecialchars($docs['permisos'] ?? 'Ninguno') ?></label>
			<input type="file" name="permisos" class="form-control">
		</div>
		<div class="col-md-6 my-2">
			<label>Salud actual: <?= htmlspecialchars($docs['salud'] ?? 'Ninguno') ?></label>
			<input type="file" name="salud" class="form-control">
		</div>

		<div class="col-12 mt-4">
			<button type="submit" class="btn btn-primary">
				<i class="fa fa-save"></i> Guardar Cambios
			</button>
		</div>
	</form>
	<?php } else { ?>
		<p>No se encontraron datos para editar.</p>
	<?php } ?>
</section>
<?php include($_SERVER['DOCUMENT_ROOT']."/fdl/app/views/partials/footer.php"); ?>
