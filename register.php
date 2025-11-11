<?php

/**
 * Página de registro de usuarios
 * Este archivo maneja tanto la visualización del formulario como el procesamiento de datos
 */

// Iniciar la sesión para poder usar tokens CSRF y mensajes
session_start();

// archivos necesarios
require_once 'config/database.php';
require_once 'includes/security.php';
require_once 'includes/user_manager.php';

// HEADERS DE SEGURIDAD
// previene que la página sea cargada en un iframe, protegiendo contra clickjacking
header("X-Frame-Options: DENY");

// previene que el navegador "adivine" el tipo de los archivos
header("X-Content-Type-Options: nosniff");

// activa el filtro XSS del navegador
header("X-XSS-Protection: 1; mode=block");

// Variables para almacenar mensajes y datos del formulario
$errors = [];
$success = '';
$formData = [
    'username' => '',
    'email' => ''
];

// Verifico si el usuario ya está logueado
// Si ya está logueado, redirijo a la página de dashboard
if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

// PROCESAMIENTO DEL FORMULARIO
// Verifico si el formulario fue enviado mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verifico el token CSRF primero
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $errors[] = 'Token de seguridad inválido. Por favor, recarga la página e intenta de nuevo.';
    } else {
        // Obtener y sanitizar los datos del formulario
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $passwordConfirmation = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';

        // Guardo los datos del formulario
        $formData['username'] = sanitizeInput($username);
        $formData['email'] = sanitizeInput($email);

        // VALIDACIONES EN EL BACKEND
        // Valido que todos los campos estén completos
        if (empty($username)) {
            $errors[] = 'El nombre de usuario es obligatorio';
        } elseif (strlen($username) < 3) {
            $errors[] = 'El nombre de usuario debe tener al menos 3 caracteres';
        } elseif (strlen($username) > 50) {
            $errors[] = 'El nombre de usuario no puede tener más de 50 caracteres';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors[] = 'El nombre de usuario solo puede contener letras, números y guiones bajos';
        }

        if (empty($email)) {
            $errors[] = 'El email es obligatorio';
        } elseif (!validateEmail($email)) {
            $errors[] = 'El formato del email no es válido';
        }

        if (empty($password)) {
            $errors[] = 'La contraseña es obligatoria';
        }

        if (empty($passwordConfirmation)) {
            $errors[] = 'Debes confirmar tu contraseña';
        }

        // Verifico que las contraseñas coincidan
        if (!empty($password) && !empty($passwordConfirmation) && $password !== $passwordConfirmation) {
            $errors[] = 'Las contraseñas no coinciden';
        }

        // Si no hay errores, intento registrar al usuario
        if (empty($errors)) {
            $result = registerUser($username, $password, $passwordConfirmation, $email);

            if ($result['success']) {
                // Registro exitoso
                $success = $result['message'];
                // Limpiar el formulario
                $formData = ['username' => '', 'email' => ''];
                // Logeo al usuario
                $login = loginUser($username, $password);
                if ($login['success']) {
                    // Redirijo 
                    header("Location: dashboard.php");
                    exit;
                }
            } else {
                // Error al registrar
                $errors[] = $result['message'];
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
    <title>User Register</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>
    <div class="container">
        <h1>Create Account</h1>
        <p class="subtitle">Complete the form to register</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php" id="registerForm" novalidate>
            <!-- Campo oculto para el token CSRF -->
            <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">

            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?php echo htmlspecialchars($formData['username']); ?>"
                    required
                    minlength="3"
                    maxlength="50"
                    pattern="[a-zA-Z0-9_]+">
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?php echo htmlspecialchars($formData['email']); ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Password Confirmation</label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    required>
                <div id="password-match-message"></div>
            </div>

            <button type="submit">Register</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Log in here</a>
        </div>

    </div>

    <script>
        // VALIDACIÓN DEL FORMULARIO ANTES DE ENVIAR

        const form = document.getElementById('registerForm');

        // Escucho el evento de envío del formulario
        form.addEventListener('submit', function(e) {
            // Obtengo los valores de todos los campos
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            // Array para almacenar los mensajes de error
            let errors = [];

            if (username === '') {
                errors.push('El nombre de usuario es obligatorio');
            }

            if (email === '') {
                errors.push('El correo electrónico es obligatorio');
            }

            if (password === '') {
                errors.push('La contraseña es obligatoria');
            }

            if (confirmPassword === '') {
                errors.push('Debes confirmar tu contraseña');
            }

            if (username !== '' && username.length < 3) {
                errors.push('El nombre de usuario debe tener al menos 3 caracteres');
            }

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email !== '' && !emailPattern.test(email)) {
                errors.push('El formato del email no es válido');
            }

            if (password !== '' && password.length < 8) {
                errors.push('La contraseña debe tener al menos 8 caracteres');
            }

            if (password !== '' && !/[A-Z]/.test(password)) {
                errors.push('La contraseña debe contener al menos una letra mayúscula');
            }

            if (password !== '' && !/[a-z]/.test(password)) {
                errors.push('La contraseña debe contener al menos una letra minúscula');
            }

            if (password !== '' && !/[0-9]/.test(password)) {
                errors.push('La contraseña debe contener al menos un número');
            }

            if (password !== '' && confirmPassword !== '' && password !== confirmPassword) {
                errors.push('Las contraseñas no coinciden');
            }

            // Si hay errores, muestro un alert y no envío el formulario
            if (errors.length > 0) {
                e.preventDefault();

                // Muestro todos los errores
                alert('Por favor, corrige los siguientes errores:\n\n' + errors.join('\n'));

                // Me aseguro que no se envíe
                return false;
            }
            // Si no hay errores, el formulario se enviará normalmente
            return true;
        });
    </script>
</body>

</html>