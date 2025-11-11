<?php
/**
 * Archivo de cierre de sesión (Logout)
 * Este archivo maneja la destrucción segura de la sesión del usuario
 * 
 */

// Archivos necesarios
require_once 'includes/user_manager.php';
 
// Cierro sesion
logoutUser();

// Redirijo al usuario a la página de login
header("Location: login.php");
exit();
