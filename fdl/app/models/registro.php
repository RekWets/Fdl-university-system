<?php

require_once($_SERVER['DOCUMENT_ROOT']."/fdl/app/db/db.php");

class registro extends BDConexion{
    
    public $conn;
    public function __construct(){
        $this->conn = parent::__construct();

    }
   public function get() {
    $data = [];
    $sql = "SELECT * FROM registro WHERE status = 'si'";
    $stmt = $this->conn->prepare($sql);
    if ($stmt->execute()) {
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return $data;
}

public function consulta($form) {
    $data = [];

    // 1. Buscar en tabla registro usando la cédula (clave)
    $sql = "SELECT * FROM registro WHERE cedula = :clave";
    $stmt = $this->conn->prepare($sql);
    $stmt->bindParam(':clave', $form['clave']);

    if ($stmt->execute() && $stmt->rowCount() > 0) {
        // Aquí recuperas el registro completo (info principal)
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);
        $clienteId = $registro['id'];
        $data['registro'] = $registro;  // <--- AQUÍ ESTÁ LA INFO DE REGISTRO
        // Luego buscas info relacionada usando ese clienteId
        // Formación académica
      $sqlFormacion = "SELECT 
    id, 
    cliente_id, 
    titulo_obtenido, 
    certificados, 
    antecedentes_servicios, 
    anos_experiencia AS experiencia -- Usamos un alias para que la vista lo reconozca
    FROM formacion_academica WHERE cliente_id = :cliente_id";
        $stmtForm = $this->conn->prepare($sqlFormacion);
        $stmtForm->bindParam(':cliente_id', $clienteId);
        $stmtForm->execute();
        $data['formacion_academica'] = $stmtForm->fetch(PDO::FETCH_ASSOC);

        // Documentos
        $sqlDoc = "SELECT * FROM documentos WHERE cliente_id = :cliente_id";
        $stmtDoc = $this->conn->prepare($sqlDoc);
        $stmtDoc->bindParam(':cliente_id', $clienteId);
        $stmtDoc->execute();
        $data['documentos'] = $stmtDoc->fetch(PDO::FETCH_ASSOC);

        // Hijos
        $sqlHijos = "SELECT * FROM hijos WHERE cliente_id = :cliente_id";
        $stmtHijos = $this->conn->prepare($sqlHijos);
        $stmtHijos->bindParam(':cliente_id', $clienteId);
        $stmtHijos->execute();
        $data['hijos'] = $stmtHijos->fetchAll(PDO::FETCH_ASSOC);

    } else {
        $data['error'] = "Cliente no encontrado con cédula: " . htmlspecialchars($form['clave']);
    }

    return $data;
}



public function guardar($datos, $archivos) {
    
    try {
        $this->conn->beginTransaction();

        // 1. Insertar en la tabla registro
       // Dentro de public function guardar()...
$sqlRegistro = "INSERT INTO registro 
    (cedula, nombres, apellidos, fecha_nacimiento, telefono, sexo, direccion, fecha_ingreso, ubicacion_adm, cargo, fecha_egreso, foto, status) 
    VALUES 
    (:cedula, :nombres, :apellidos, :fecha_nacimiento, :telefono, :sexo, :direccion, :fecha_ingreso, :ubicacion_adm, :cargo, :fecha_egreso, :foto, 'si')";
// Agregamos 'si' al final ^
        
        $stmt = $this->conn->prepare($sqlRegistro);
        $stmt->execute([
            ':cedula' => $datos['cedula'],
            ':nombres' => $datos['nombres'],
            ':apellidos' => $datos['apellidos'],
            ':fecha_nacimiento' => $datos['fecha_nacimiento'],
            ':telefono' => $datos['telefono'],
            ':sexo' => $datos['sexo'],
            ':direccion' => $datos['direccion'], // sincronizado con lugar_nacimiento
            ':fecha_ingreso' => $datos['fecha_ingreso'],
            ':ubicacion_adm' => $datos['ubicacion_adm'],
            ':cargo' => $datos['cargo'],
            ':fecha_egreso' => $datos['fecha_egreso'] ?? null,
            ':foto' => $datos['foto'] ?? 'sin-foto.jpg'
        ]);

        $clienteId = $this->conn->lastInsertId();

        // 2. Insertar formación académica (no es array en el form)
        $sqlFormacion = "INSERT INTO formacion_academica 
            (cliente_id, titulo_obtenido, certificados, antecedentes_servicios, anos_experiencia) 
            VALUES (:cliente_id, :titulo_obtenido, :certificados, :antecedentes_servicios, :anos_experiencia)";

        $stmtForm = $this->conn->prepare($sqlFormacion);
        $stmtForm->execute([
            ':cliente_id' => $clienteId,
            ':titulo_obtenido' => $datos['titulo'],
            ':certificados' => $datos['certificados'],
            ':antecedentes_servicios' => $datos['antecedentes'] ?? '',
            ':anos_experiencia' => $datos['experiencia'] ?? 0
        ]);

        // 3. Insertar hijos dinámicamente
        $cantidadHijos = intval($datos['cantidad_hijos'] ?? 0);
        if ($cantidadHijos > 0) {
            $sqlHijos = "INSERT INTO hijos (cliente_id, nombre, fecha_nacimiento) VALUES (:cliente_id, :nombre, :fecha_nacimiento)";
            $stmtHijo = $this->conn->prepare($sqlHijos);

            for ($i = 1; $i <= $cantidadHijos; $i++) {
                if (!empty($datos["nombre_hijo_$i"])) {
                    $stmtHijo->execute([
                        ':cliente_id' => $clienteId,
                        ':nombre' => $datos["nombre_hijo_$i"],
                        ':fecha_nacimiento' => $datos["fecha_hijo_$i"] ?? null
                    ]);
                }
            }
        }

        // 4. Insertar documentos
        $sqlDoc = "INSERT INTO documentos (cliente_id, sintesis, cedula, rif, nacimiento, titulo, permisos, salud) 
            VALUES (:cliente_id, :sintesis, :cedula, :rif, :nacimiento, :titulo, :permisos, :salud)";

        $stmtDoc = $this->conn->prepare($sqlDoc);
        $stmtDoc->execute([
            ':cliente_id' => $clienteId,
            ':sintesis' => $archivos['sintesis']['name'] ?? null,
            ':cedula' => $archivos['cedula_doc']['name'] ?? null,
            ':rif' => $archivos['rif']['name'] ?? null,
            ':nacimiento' => $archivos['partida']['name'] ?? null,
            ':titulo' => $archivos['titulo_negro']['name'] ?? null,
            ':permisos' => $archivos['permisos']['name'] ?? null,
            ':salud' => $archivos['salud']['name'] ?? null
        ]);

        // 5. Guardar archivos físicamente
        $this->guardarArchivos($clienteId, $archivos);

        $this->conn->commit();
        return true;

    } catch (Exception $e) {
        $this->conn->rollBack();
        //    return $e->getMessage();
        // return false;
    }
}
  // 👇 Aquí va guardarArchivos()
private function guardarArchivos($formularioId, $archivos) {
    // Carpeta destino: documentos/{formularioId}/
    $carpetaDestino = $_SERVER['DOCUMENT_ROOT'] . "/fdl/documentos/" . $formularioId . "/";

    // Crear carpeta destino si no existe (con permisos 0777)
    if (!file_exists($carpetaDestino)) {
        if (!mkdir($carpetaDestino, 0777, true)) {
            throw new Exception("No se pudo crear la carpeta: $carpetaDestino");
        }
    }

    $nombresFinales = [];

    foreach ($archivos as $nombreCampo => $archivo) {
        // Verificar que el archivo se haya subido sin errores y tenga tamaño > 0
        if (isset($archivo['error']) && $archivo['error'] === 0 && isset($archivo['size']) && $archivo['size'] > 0) {
            
            // Verificar que el archivo temporal exista
            if (!isset($archivo['tmp_name']) || !file_exists($archivo['tmp_name'])) {
                throw new Exception("El archivo temporal no existe para el archivo {$archivo['name']}");
            }

            // Obtener extensión
            $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);

            // Crear nombre único para evitar sobreescritura
            $nombreUnico = time() . '_' . bin2hex(random_bytes(5)) . '.' . $extension;
            $rutaDestino = $carpetaDestino . $nombreUnico;

            // Mover archivo
            if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                $nombresFinales[$nombreCampo] = $nombreUnico;
            } else {
                throw new Exception("Error al mover el archivo {$archivo['name']} a $rutaDestino");
            }

        } else {
            // Si no hay archivo válido, opcional: continuar o lanzar error
            // Aquí simplemente seguimos con el siguiente archivo
            continue;
        }
    }

    return $nombresFinales;
}



public function actualizar($datosPost, $archivos) {
    $id = $datosPost['id'];
    $fotoActual = $datosPost['foto_actual'] ?? null;

    try {
        $this->conn->beginTransaction();

        // 1. Guardar archivos nuevos (foto y documentos)
$nombresArchivos = $this->guardarArchivos($datosPost['id'], $archivos);

        // Foto nueva (si se subió)
        $fotoNueva = $nombresArchivos['foto'] ?? null;
        $fotoParaGuardar = $fotoNueva ?? $fotoActual;

        // 2. Actualizar tabla principal (registro)
        $sql = "UPDATE registro SET 
            cedula = :cedula,
            nombres = :nombres,
            apellidos = :apellidos,
            fecha_nacimiento = :fecha_nacimiento,
            telefono = :telefono,
            sexo = :sexo,
            direccion = :direccion,
            fecha_ingreso = :fecha_ingreso,
            fecha_egreso = :fecha_egreso,
            ubicacion_adm = :ubicacion_adm,
            cargo = :cargo,
            foto = :foto
            WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
             ':cedula' => $datosPost['cedula'],
            ':nombres' => $datosPost['nombres'],
            ':apellidos' => $datosPost['apellidos'],
            ':fecha_nacimiento' => $datosPost['fecha_nacimiento'],
            ':telefono' => $datosPost['telefono'],
            ':sexo' => $datosPost['sexo'],
            ':direccion' => $datosPost['direccion'],
            ':fecha_ingreso' => $datosPost['fecha_ingreso'],
            ':fecha_egreso' => $datosPost['fecha_egreso'],
            ':ubicacion_adm' => $datosPost['ubicacion_adm'],
            ':cargo' => $datosPost['cargo'],
            ':foto' => $fotoParaGuardar,
            ':id' => $id
        ]);

        // 3. Actualizar formación académica
        $sqlForm = "UPDATE formacion_academica SET 
            titulo_obtenido = :titulo,
            certificados = :certificados,
            antecedentes_servicios = :antecedentes,
            anos_experiencia = :experiencia
            WHERE cliente_id = :cliente_id";

        $stmtForm = $this->conn->prepare($sqlForm);
        $stmtForm->execute([
            ':titulo' => $datosPost['titulo'],
            ':certificados' => $datosPost['certificados'],
            ':antecedentes' => $datosPost['antecedentes'],
            ':experiencia' => $datosPost['experiencia'],
            ':cliente_id' => $id
        ]);

        // 4. Actualizar documentos (solo si se subió un nuevo archivo)
        $camposDocs = [
            'sintesis', 'cedula', 'rif', 'nacimiento',
            'titulo', 'permisos', 'salud'
        ];

        $setDocs = [];
        $paramsDocs = [':cliente_id' => $id];

        foreach ($camposDocs as $campo) {
            if (isset($nombresArchivos[$campo])) {
                $setDocs[] = "$campo = :$campo";
                $paramsDocs[":$campo"] = $nombresArchivos[$campo];
            }
        }

        if (!empty($setDocs)) {
            $sqlDocs = "UPDATE documentos SET " . implode(', ', $setDocs) . " WHERE cliente_id = :cliente_id";
            $stmtDocs = $this->conn->prepare($sqlDocs);
            $stmtDocs->execute($paramsDocs);
        }

        $this->conn->commit();
        return true;

    } catch (Exception $e) {
        $this->conn->rollBack();
        return $e->getMessage();
    }
}
public function eliminar($id)
{
    try {
        $sql = "UPDATE registro SET status = 'no' WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        
        return $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
public function getInactivos() {
    $data = [];
    $sql = "SELECT * FROM registro WHERE status = 'no'"; 
    $stmt = $this->conn->prepare($sql);
    if ($stmt->execute()) {
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return $data;
}

public function activar($id) {
    try {
        $sql = "UPDATE registro SET status = 'si' WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
        return $stmt->execute(); // Retorna true si tuvo éxito
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
public function getUsuariosSistema() {
    // Ajusta el nombre de la tabla según tu base de datos (ej. 'usuarios' o 'login')
    $sql = "SELECT * FROM users WHERE status = 'si'"; 
    $stmt = $this->conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function updatePassword($id, $actual, $nueva) {
    try {
        // Buscamos la contraseña en la tabla 'usuarios' usando la columna 'id'
        $sql = "SELECT password FROM users WHERE id = :id AND status = 'si'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verificamos la contraseña actual
            if (password_verify($actual, $user['password'])) {
                
                // Encriptamos la nueva contraseña antes de guardar
                $nueva_encriptada = password_hash($nueva, PASSWORD_DEFAULT);
                
                $update = "UPDATE users SET password = :pass WHERE id = :id";
                $stmt_upd = $this->conn->prepare($update);
                $stmt_upd->bindParam(':pass', $nueva_encriptada);
                $stmt_upd->bindParam(':id', $id, PDO::PARAM_INT);
                
                return $stmt_upd->execute();
            } else {
                return "La contraseña actual no es correcta.";
            }
        } else {
            return "Usuario no encontrado o inactivo.";
        }
    } catch (PDOException $e) {
        return "Error de base de datos: " . $e->getMessage();
    }
}
} 
