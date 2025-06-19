//funcion para el ojo de mostrar y ocultar contraseña
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.querySelector('.show-hide');
    
    if(eyeIcon) {
        eyeIcon.addEventListener('click', function() {
            if(passwordInput.type === 'password') {
                passwordInput.type = 'text';
                this.classList.replace('bx-hide', 'bx-show');
            } else {
                passwordInput.type = 'password';
                this.classList.replace('bx-show', 'bx-hide');
            }
        });
    }
});
// Función para obtener ubicación
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        alert("La geolocalización no es soportada por este navegador.");
    }
}

function showPosition(position) {
    const lat = position.coords.latitude;
    const lng = position.coords.longitude;
    document.getElementById('direccion').value = `${lat}, ${lng}`;
    
    // Opcional: Mostrar mapa embebido
    const url = `https://maps.google.com/maps?q=${lat},${lng}&output=embed`;
    window.open(url, '_blank');
}

// Función para mostrar/ocultar contraseña
function togglePassword() {
    const passwordField = document.querySelector('input[type="password"]');
    const icon = document.querySelector('.bx-hide');
    
    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.replace('bx-hide', 'bx-show');
    } else {
        passwordField.type = "password";
        icon.classList.replace('bx-show', 'bx-hide');
    }
}
//scripts para actualizar la informacion enla base de datos
async function actualizarPuntosBD(clienteId, nuevosPuntos, esRedencion = false) {
    try {
        // Obtener puntos actuales
        const response = await fetch(`api_puntos.php?id=${clienteId}`);
        const data = await response.json();
        
        // Calcular nuevos valores
        let puntosAcumulados = data.puntosacomulados;
        let puntosRedimidos = data.puntosredimidos;
        
        if(esRedencion) {
            puntosRedimidos += nuevosPuntos;
            puntosAcumulados -= nuevosPuntos;
        } else {
            puntosAcumulados += nuevosPuntos;
        }
        
        // Actualizar en BD
        const updateResponse = await fetch('api_puntos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: clienteId,
                puntosacumulados: puntosAcumulados,
                puntosredimidos: puntosRedimidos
            })
        });
        
        return await updateResponse.json();
    } catch (error) {
        console.error('Error:', error);
    }
}
//Modificar las funciones de puntos para usar la BD:
async function agregarPuntos(cantidad) {
    const clienteId = obtenerIdCliente(); // Debes implementar esta función
    const resultado = await actualizarPuntosBD(clienteId, cantidad);
    
    if(resultado.status === 'success') {
        puntos += cantidad;
        actualizarInterfaz();
        agregarAlHistorial(`+${cantidad} puntos (Código escaneado)`);
    }
}

async function redimirPuntos(cantidad) {
    const clienteId = obtenerIdCliente();
    const resultado = await actualizarPuntosBD(clienteId, cantidad, true);
    
    if(resultado.status === 'success') {
        puntos -= cantidad;
        actualizarInterfaz();
        agregarAlHistorial(`-${cantidad} puntos (Canje realizado)`);
    }
}
//Para la interfaz del barbero (generar códigos con ID de cliente):
function generarCodigo() {
    const puntos = document.getElementById('puntosInput').value;
    const clienteId = document.getElementById('clienteId').value; // Añadir input para ID
    
    const datos = {
        cliente_id: clienteId,
        puntos: parseInt(puntos),
        timestamp: new Date().getTime()
    };
    
    // Resto del código para generar QR...
}
//Asegurar la conexión en el escáner:
scanner.addListener('scan', async function(content) {
    const datos = validarCodigo(content);
    if(datos) {
        await agregarPuntos(datos.puntos, datos.cliente_id);
        cerrarCamara();
    }
});
// Mostrar popup de éxito
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('exito')) {
    alert('Registro exitoso, ya puedes iniciar sesión');
    window.history.replaceState({}, document.title, window.location.pathname);
}


//modal de terminos y condiciones
function mostrarTerminos() {
    // Mostrar el modal y la superposición
    document.getElementById('termsModal').style.display = 'block';
    document.getElementById('termsOverlay').style.display = 'block';

    // Mostrar mensaje de carga
    document.getElementById('termsContent').innerHTML = '<p>Cargando términos y condiciones...</p>';

    // Cargar el contenido de terminos.html
    fetch('./terminos.html')
        .then(response => response.text())
        .then(data => {
            // Inyectar el contenido en el modal
            document.getElementById('termsContent').innerHTML = data;
        })
        .catch(error => {
            console.error('Error al cargar los términos:', error);
            document.getElementById('termsContent').innerHTML = '<p>Error al cargar los términos y condiciones. Por favor, inténtalo más tarde.</p>';
        });
}

function closeTerms() {
    // Ocultar el modal y la superposición
    document.getElementById('termsModal').style.display = 'none';
    document.getElementById('termsOverlay').style.display = 'none';
}
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeTerms();
    }
});

   