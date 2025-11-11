<?php

// ARCHIVO PARA SEGURIDAD Y VALIDACIÓN

/** 
 * Sanitiza una cadena de texto para prevenir XSS
 * @param string $data Datos a sanitizar
 * @return string Datos sanitizados
 */
function sanitizeInput($data) {
    $data = trim($data);
    
    // htmlspecialchars() convierte caracteres especiales en entidades HTML
    // ENT_QUOTES convierte tanto comillas simples como dobles
    // 'UTF-8' especifica la codificación de caracteres
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    
    return $data;
}

/**
 * Valida el formato de un email
 * @param string $email Email a validar
 * @return bool True si el email es válido
 */
function validateEmail($email) {
    // verifico que el formato sea correcto
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valida la fortaleza de una contraseña
 * 
 * @param string $password Contraseña a validar
 * @return array Array con 'valid' (bool) y 'message' (string)
 */
function validatePassword($password) {
    $result = ['valid' => true, 'message' => ''];
    
    // Verificar longitud mínima de 8 caracteres
    if (strlen($password) < 8) {
        $result['valid'] = false;
        $result['message'] = 'The password must contain at least 8 characters';
        return $result;
    }
    
    // Verificar que contenga al menos una letra mayúscula
    if (!preg_match('/[A-Z]/', $password)) {
        $result['valid'] = false;
        $result['message'] = 'The password must contain an uppercase letter';
        return $result;
    }
    
    // Verificar que contenga al menos una letra minúscula
    if (!preg_match('/[a-z]/', $password)) {
        $result['valid'] = false;
        $result['message'] = 'The password must contain a lowercase letter';
        return $result;
    }
    
    // Verificar que contenga al menos un número
    if (!preg_match('/[0-9]/', $password)) {
        $result['valid'] = false;
        $result['message'] = 'The password must contain a number';
        return $result;
    }
    
    return $result;
}

/**
 * Genera un token CSRF
 * 
 * @return string Token CSRF generado
 */
function generateCSRFToken() {
    // Iniciar sesión si no está iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // bin2hex convierte datos binarios a hexadecimal legible
    // random_bytes genera bytes aleatorios criptográficamente seguros
    $token = bin2hex(random_bytes(32));
    
    // Guardamos el token en la sesión para verificarlo después
    $_SESSION['csrf_token'] = $token;
    
    return $token;
}

/**
 * Verifica que el token CSRF enviado coincida con el de la sesión
 * 
 * @param string $token Token a verificar
 * @return bool True si el token es válido
 */
function verifyCSRFToken($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Verificar que exista un token en la sesión
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    // hash_equals previene ataques de timing
    return hash_equals($_SESSION['csrf_token'], $token);
}


