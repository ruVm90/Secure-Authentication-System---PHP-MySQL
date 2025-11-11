<?php
/**
 * Página de inicio de sesión
 * Este archivo maneja la autenticación de usuarios existentes
 */

// Iniciar la sesión para poder usar tokens CSRF y almacenar datos de usuario
session_start();

// archivos necesarios
require_once 'config/database.php';
require_once 'includes/security.php';
require_once 'includes/user_manager.php';

// headers de seguridad

header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

// Variables para almacenar mensajes
$error = '';
$success = '';
// Variable para mantener el nombre de usuario en caso de error
$username = '';

// Verificar si hay un mensaje de logout exitoso
if (isset($_SESSION['logout_message'])) {
    $success = $_SESSION['logout_message'];
    // Eliminar el mensaje después de mostrarlo (solo debe aparecer una vez)
    unset($_SESSION['logout_message']);
}

// Verificar si el usuario ya está logueado
// Si ya tiene una sesión activa, no tiene sentido que esté en el login
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

// PROCESAMIENTO DEL FORMULARIO DE LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Primero verifico el token CSRF
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $error = 'Token de seguridad inválido. Por favor, recarga la página e intenta de nuevo.';
    } else {
        // Obtengo los datos del formulario
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        // Validación de campos vacios
        if (empty($username) || empty($password)) {
            // Guardo el error en la sesión y redirijo a la página de error
            $_SESSION['login_error'] = 'Por favor, completa todos los campos';
            header("Location: error.php");
            exit();
        } else {
            // Intento autenticar al usuario
            $result = loginUser($username, $password);
            
            if ($result['success']) {
                // Login exitoso
                // Redirijo
                header("Location: dashboard.php");
                exit();
            } else {
                // Login fallido
                // Guardo el mensaje de error en la sesión
                // Redirijo a la pagina de error
                $_SESSION['login_error'] = $result['message'];
                header("Location: error.php");
                exit();
                
            }
        }
    }
}

// Genero un nuevo token CSRF para el formulario
$csrfToken = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <p class="subtitle">Enter your credentials to continue.</p>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="login.php" id="loginForm">
            <!-- Campo oculto para el token CSRF -->
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
            
            <div class="form-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    value="<?php echo htmlspecialchars($username); ?>"
                    required
                    autocomplete="username"
                    autofocus
                >
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        autocomplete="current-password"
                    >
            </div>
            
           
            <button type="submit">login</button>
        </form>
        <div class="links">
            <a href="register.php">Create an account</a>
        </div>
    </div>
    
    <script>
        
        // Validación básica del formulario antes de enviarlo
        const loginForm = document.getElementById('loginForm');
        
        loginForm.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            // Verificar que los campos no estén vacíos
            if (username === '' || password === '') {
                e.preventDefault();
                alert('Por favor, completa todos los campos');
                return false;
            }
            // Si todo está bien, se envia
            return true;
        });
        
    </script>
</body>
</html>