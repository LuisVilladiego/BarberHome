<?php
session_start();
include 'config.php';

if (!isset($_POST['terminos'])) {
    $_SESSION['errores'][] = "Debes aceptar los términos y condiciones para registrarte";
    header("Location: RegistrarUsuario.php");
    exit;
}

// Generar token CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errores[] = "Token de seguridad inválido";
    }
    
    // Sanitizar entradas
    $campos = [
        'nombres' => trim($_POST['nombres']),
        'apellidos' => trim($_POST['apellidos']),
        'telefono' => trim($_POST['telefono']),
        'direccion' => trim($_POST['direccion']),
        'correo' => filter_var(trim($_POST['correo']), FILTER_SANITIZE_EMAIL),
        'clave' => $_POST['clave']
    ];
    
    // Validaciones
    foreach ($campos as $key => $value) {
        if (empty($value)) {
            $errores[] = "El campo " . ucfirst($key) . " es obligatorio";
        }
    }
    
    if (!filter_var($campos['correo'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Formato de correo inválido";
    }
    
    if (strlen($campos['clave']) < 8) {
        $errores[] = "La contraseña debe tener al menos 8 caracteres.";
    }
    
    // Verificar correo único
    $sql = "SELECT id FROM puntosclientes WHERE correo = '" . $conn->real_escape_string($campos['correo']) . "'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $errores[] = "Este correo ya está registrado";
    }
    if ($result) {
        $result->free();
    }
    
    if (empty($errores)) {
        // Hashear la contraseña correctamente
        $hash = password_hash($campos['clave'], PASSWORD_DEFAULT);
        
        // Usar consultas preparadas para evitar inyecciones SQL
        $sql = "INSERT INTO puntosclientes (nombres, apellidos, telefono, direccion, correo, clave, puntosacomulados, puntosredimidos) 
                VALUES (?, ?, ?, ?, ?, ?, 0, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $campos['nombres'], $campos['apellidos'], $campos['telefono'], $campos['direccion'], $campos['correo'], $hash);
        
        if ($stmt->execute()) {
            // Éxito: Mostrar SweetAlert y redirigir
            echo '
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Swal.fire({
                    title: "¡Registro Exitoso!",
                    text: "Tu cuenta ha sido creada correctamente.",
                    icon: "success",
                    confirmButtonColor: "#4CAF50",
                    showConfirmButton: false,
                    timer: 3000
                }).then(() => {
                    window.location.href = "../index.html";
                });
            </script>
            ';
            exit();
        } else {
            echo "Error al insertar datos: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>