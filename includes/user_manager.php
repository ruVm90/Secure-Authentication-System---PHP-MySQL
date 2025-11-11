<?php

// ARCHIVO DE GESTIÓN DE USUARIOS

require_once __DIR__ . '/security.php';
require_once __DIR__ . '/../config/database.php';
/**
 * Registra un nuevo usuario en la base de datos
 * 
 * @param string $username Nombre de usuario
 * @param string $password Contraseña 
 * @param string $email Email del usuario
 * @return array Array con 'success' (bool), 'message' (string)
 */
function registerUser($username, $password, $passwordConfirmation, $email) {
    try {
        $pdo = getDBConnection();
        
        // Sanitizar entradas
        $username = sanitizeInput($username);
        $email = sanitizeInput($email);
        
        // Validar que los campos no estén vacíos
        if (empty($username) || empty($password) || empty($email) || empty($passwordConfirmation)) {
            return ['success' => false, 'message' => 'Todos los campos son obligatorios'];
        }
        
        // Validar formato de email
        if (!validateEmail($email)) {
            return ['success' => false, 'message' => 'El formato del email no es válido'];
        }
       
        // Validar fortaleza de contraseña
        $passwordValidation = validatePassword($password);
        if (!$passwordValidation['valid']) {
            return ['success' => false, 'message' => $passwordValidation['message']];
        }
        
        // Verificar si el usuario ya existe
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'El nombre de usuario o email ya está registrado'];
        }
        
        // Hasheo la contraseña usando BCRYPT
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Inserto el nuevo usuario
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hashedPassword, $email]);
        
        
        return [
            'success' => true,
            'message' => 'Usuario registrado exitosamente',
        ];
        
    } catch (PDOException $e) {
        error_log("Error en registerUser: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error al registrar el usuario. Intenta de nuevo.'];
    }
}

/**
 * Autentica un usuario verificando sus credenciales
 * 
 * @param string $username Nombre de usuario
 * @param string $password Contraseña en texto plano
 * @return array Array con 'success' (bool), 'message' (string), y opcionalmente 'user' (array)
 */
function loginUser($username, $password) {
    try {
        $pdo = getDBConnection();
        
        // Sanitizar entrada
        $username = sanitizeInput($username);
        
        // Validar que los campos no estén vacíos
        if (empty($username) || empty($password)) {
            return ['success' => false, 'message' => 'Usuario y contraseña son obligatorios'];
        }
        
        // Buscar el usuario en la base de datos
        $stmt = $pdo->prepare("SELECT id, username, password, email FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        // Si no existe el usuario o la contraseña no coincide
        if (!$user || !password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Credenciales incorrectos'];
        }
        
        // Iniciar sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Esto crea un nuevo ID de sesión y elimina el antiguo
        session_regenerate_id(true);
        
        // Guardar datos del usuario en la sesión
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['logged_in'] = true;
        
        // Elimino la contraseña antes de devolver los datos
        unset($user['password']);
        
        return [
            'success' => true,
            'message' => 'Inicio de sesión exitoso',
            'user' => $user
        ];
        
    } catch (PDOException $e) {
        error_log("Error en loginUser: " . $e->getMessage());
        return ['success' => false, 'message' => 'Error al iniciar sesión. Intenta de nuevo.'];
    }
}

/**
 * Obtiene la lista de todos los usuarios registrados
 * 
 * @return array Array con 'success' (bool), 'message' (string), y 'users' (array)
 */
function getAllUsers() {
    try {
        $pdo = getDBConnection();
        
        // Seleccionamos todos los campos EXCEPTO la contraseña
        $stmt = $pdo->query("SELECT id, username, email FROM users");
        $users = $stmt->fetchAll();
        
        return [
            'success' => true,
            'users' => $users
        ];
        
    } catch (PDOException $e) {
        error_log("Error en getAllUsers: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Error al obtener la lista de usuarios',
            'users' => []
        ];
    }
}

/**
 * Verifica si un usuario está autenticado
 * 
 * @return bool True si el usuario está logueado
 */
function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Cierra la sesión del usuario actual
 */
function logoutUser() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Limpiar todas las variables de sesión
    $_SESSION = [];
    
    // Destruir la cookie de sesión si existe
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    // Destruir la sesión
    session_destroy();
}