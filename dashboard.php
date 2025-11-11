<?php
/**
 * Dashboard - Página de inicio de sesión exitoso
 * Esta página muestra la lista de todos los usuarios registrados
 * Solo es accesible para usuarios autenticados
 */

// Iniciar la sesión para poder verificar si el usuario está logueado
session_start();

// archivos necesarios
require_once 'config/database.php';
require_once 'includes/user_manager.php';

// Configurar headers de seguridad
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

// VERIFICACIÓN DE AUTENTICACIÓN
if (!isLoggedIn()) {
    // Redirijo al login si no está autenticado
    header("Location: login.php");
    exit();
}

// Obtengo los datos del usuario actual desde la sesión
$currentUser = [
    'id' => $_SESSION['user_id'],
    'username' => $_SESSION['username'],
    'email' => $_SESSION['email']
];

// Obtengo la lista de todos los usuarios registrados
$usersResult = getAllUsers();

// Verifico si hubo un error al obtener los usuarios
if (!$usersResult['success']) {
    // En caso de error muestro un mensaje
    $error = $usersResult['message'];
    $users = [];
} else {
    $users = $usersResult['users'];
}

// Calculo el total de usuarios
$totalUsers = count($users);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Usuarios</title>
    <link rel="stylesheet" href="styles/dashboard.css">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar">
        <div class="navbar-content">
            <h1>📊 Sistema de Gestión de Usuarios</h1>
            <div class="user-info">
                <span>👤 <?php echo htmlspecialchars($currentUser['username']); ?></span>
                <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <!-- Mensaje de bienvenida -->
        <div class="welcome-message">
            <h2>¡Bienvenido, <?php echo htmlspecialchars($currentUser['username']); ?>!</h2>
            <p>Has iniciado sesión exitosamente. Aquí puedes ver todos los usuarios registrados en el sistema.</p>
        </div>
        
        <!-- Total de usuarios -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total de Usuarios</h3>
                <div class="value"><?php echo $totalUsers; ?></div>
                <div class="subtext">Registrados en el sistema</div>
            </div>
            
            <div class="stat-card">
                <h3>Tu Usuario</h3>
                <div class="value" style="font-size: 24px;"><?php echo htmlspecialchars($currentUser['username']); ?></div>
                <div class="subtext"><?php echo htmlspecialchars($currentUser['email']); ?></div>
            </div>
            
        </div>
        
        <!-- Sección de la tabla de usuarios -->
        <div class="users-section">
            <h2>Lista de Usuarios Registrados</h2>
            
            <!-- Mostrar mensaje de error si lo hay -->
            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if (empty($users)): ?>
                <!-- Mostrar cuando no hay usuarios -->
                <div class="no-users">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p>No hay usuarios registrados todavía</p>
                </div>
            <?php else: ?>
                <!-- Tabla de usuarios -->
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre de Usuario</th>
                            <th>Correo Electrónico</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($user['username']); ?>
                                </td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>