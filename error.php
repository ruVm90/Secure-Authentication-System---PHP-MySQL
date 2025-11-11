<?php
/**
 * Página de Error de Inicio de Sesión
 * Esta página muestra mensajes de error cuando el login falla
 * Es una página separada que cumple con el requisito específico de la prueba técnica
 */

// Iniciar la sesión para poder leer mensajes de error
session_start();

// headers de seguridad
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

// Variable para mostrar el error
$errorMessage = '';

// Si existe mensaje de error lo guardo y limpio
if (isset($_SESSION['login_error'])) {
    $errorMessage = $_SESSION['login_error'];
    unset($_SESSION['login_error']);
} 

// Si no hay mensaje de error, uso uno generico
if (empty($errorMessage)) {
    $errorMessage = 'Ha ocurrido un error durante el inicio de sesión';
}

// Determinar el tipo de error para mostrar diferentes iconos y mensajes
$errorType = 'general';
if (strpos(strtolower($errorMessage), 'usuario') !== false || strpos(strtolower($errorMessage), 'contraseña') !== false) {
    $errorType = 'credentials';
} elseif (strpos(strtolower($errorMessage), 'token') !== false || strpos(strtolower($errorMessage), 'csrf') !== false) {
    $errorType = 'security';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login error Page</title>
    <link rel="stylesheet" href="styles/error.css">
</head>
<body>
    <div class="error-container">
        <!-- Icono de error que cambia según el tipo -->
        <div class="error-icon <?php echo $errorType; ?>">
            <?php
            // Muestro diferentes emojis según el tipo de error
            switch($errorType) {
                case 'credentials':
                    echo '🔒';
                    break;
                case 'security':
                    echo '🛡️';
                    break;
                default:
                    echo '⚠️';
            }
            ?>
        </div>
        
        <h1>Login Error</h1>
        
        <!-- Mensaje de error principal -->
        <div class="error-message">
            <?php echo $errorMessage; ?>
        </div>
        
        <div class="actions">
            <a href="login.php" class="btn btn-primary">Volver a Intentar</a>
            <a href="register.php" class="btn btn-secondary">Crear una Cuenta Nueva</a>
        </div>
        
        <!-- Sección de ayuda con consejos -->
        <div class="help-section">
            <h3>Posibles soluciones</h3>
            <ul class="help-list">
                <?php if ($errorType === 'credentials'): ?>
                    <li>✓ Verifica que tu nombre de usuario esté escrito correctamente</li>
                    <li>✓ Asegúrate de que no tengas activado el bloqueo de mayúsculas</li>
                    <li>✓ Revisa que estés usando la contraseña correcta</li>
                    <li>✓ Si olvidaste tu contraseña, contacta al administrador</li>
                <?php elseif ($errorType === 'security'): ?>
                    <li>✓ Recarga la página de login e intenta de nuevo</li>
                    <li>✓ Asegúrate de que tu navegador permita cookies</li>
                    <li>✓ Verifica que JavaScript esté habilitado en tu navegador</li>
                    <li>✓ Si el problema persiste, prueba con otro navegador</li>
                <?php else: ?>
                    <li>✓ Verifica tu conexión a internet</li>
                    <li>✓ Intenta recargar la página</li>
                    <li>✓ Limpia la caché de tu navegador</li>
                    <li>✓ Si el problema persiste, contacta al soporte técnico</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
</body>
</html>