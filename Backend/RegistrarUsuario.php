<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="../estilos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/jpg" href="img/favicon-32x32.png"/>
    <title>BarberHome - Registro</title>
</head>
<body>  
<div class="modal-overlay" id="termsOverlay" onclick="closeTerms()"></div>

<!-- Ventana Modal -->
<div class="terms-modal" id="termsModal">
    <div class="modal-header">
        <h2>Términos y Condiciones</h2>
        <button class="modal-close" onclick="closeTerms()">&times;</button>
    </div>
    <div class="modal-content">
        <!-- Tu contenido de términos y condiciones -->
        <h1>Términos y Condiciones Puntos BarberHome</h1>
        <h2>1. Acumulación de Puntos</h2>
        <p>Los puntos se acumulan según el valor del servicio realizado. 1 punto = $1.000 COP. Los puntos se acreditan 24 horas después de realizado el servicio.</p>
        
        <h2>2. Redención de Puntos</h2>
        <p>Los puntos pueden ser redimidos por servicios de igual o menor valor. No son canjeables por dinero en efectivo.</p>
        
        <h2>3. Vigencia de Puntos</h2>
        <p>Los puntos acumulados tienen una vigencia de 12 meses a partir de su adquisición. Los puntos no utilizados expirarán automáticamente.</p>
        
        <h2>4. Datos Personales</h2>
        <p>La información proporcionada será utilizada únicamente para el funcionamiento del programa de fidelidad y no será compartida con terceros.</p>
        
        <h2>5. Modificaciones</h2>
        <p>BarberHome se reserva el derecho de modificar estos términos y condiciones con previo aviso de 30 días a través de nuestros canales oficiales.</p>
        
        <h2>6. Pérdida de Puntos</h2>
        <p>Se perderán todos los puntos acumulados en caso de detectarse cualquier tipo de fraude o mal uso del programa.</p>
        
        <h2>7. Transferencia</h2>
        <p>Los puntos son personales e intransferibles. No pueden ser vendidos ni transferidos a terceros.</p>
        
        <h2>8. Contacto</h2>
        <p>Para consultas sobre el programa de fidelidad: contacto@barberhome.com</p>
        
        <p>Fecha de última actualización: [Fecha]</p>
    </div>
</div>

<section id="formulario">
    <div class="contenedor">
        <div class="contorno">
            <form action="registrar.php" method="POST">
                <h1>Bienvenido a BarberHome</h1>
                <br>
                <center><h4>Registrarme</h4></center>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <?php if (!empty($errores)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errores as $error): ?>
                            <p><?= htmlspecialchars($error) ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="boton-registro">
                    <input type="text" name="nombres" placeholder="Nombres" required>
                    <i class='bx bx-user-pin'></i>
                </div>
                
                <div class="boton-registro">
                    <input type="text" name="apellidos" placeholder="Apellidos" required>
                    <i class='bx bx-user'></i>
                </div>
                
                <div class="boton-registro">
                    <input type="tel" name="telefono" placeholder="Teléfono" required>
                    <i class='bx bx-phone'></i>
                </div>
                
                <div class="boton-registro">
                    <input type="text" name="direccion" id="direccion" placeholder="Dirección" required>
                    <i class='bx bx-map' onclick="getLocation()"></i>
                </div>
                
                <div class="boton-registro">
                    <input type="email" name="correo" placeholder="Correo" required>
                    <i class='bx bx-envelope'></i>
                </div>
                
                <div class="boton-registro">
                    <input type="password" name="clave" placeholder="Contraseña" required>
                    <i class='bx bx-hide show-hide' onclick="togglePassword()"></i>
                </div>
                <div class="terminos-condiciones">
                    <label>
                        <input type="checkbox" name="terminos" id="terminosCheckbox" required>
                        Acepto los <a href="#" onclick="mostrarTerminos(); return false;">Términos y Condiciones</a>
                    </label>
                </div>

                <button type="submit" class="btningresar" id="submitButton" disabled>Registrarse</button>

                <div class="recordar">
                    <label><input type="checkbox">Recordar Usuario</label>
                    <a href="#">¿Olvidaste tu Contraseña?</a>
                </div>
                
                <div class="crear-cuenta">
                    <p>¿Ya tienes cuenta? <a href="../index.html">Iniciar Sesión</a></p>
                </div>
            </form>
        </div>
    </div>
</section>

<script src="./js/scripts.js"></script>
<script>
function mostrarTerminos() {
    document.getElementById('termsOverlay').style.display = 'block';
    document.getElementById('termsModal').style.display = 'block';
}

function closeTerms() {
    document.getElementById('termsOverlay').style.display = 'none';
    document.getElementById('termsModal').style.display = 'none';
}

// Habilitar/deshabilitar botón de registro
document.getElementById('terminosCheckbox').addEventListener('change', function() {
    document.getElementById('submitButton').disabled = !this.checked;
});
</script>
</body>
</html>