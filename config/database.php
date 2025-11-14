<?php
/**
 * Archivo de configuración de base de datos
 * Este archivo establece la conexión con MySQL usando PDO
 */

define('DB_HOST', 'localhost');                    // Servidor de base de datos
define('DB_NAME', 'Secure_authentication_system'); // Nombre de tu base de datos
define('DB_USER', 'root');                         // Usuario de MySQL
define('DB_PASS', '');                             // Contraseña de MySQL (vacía por defecto en XAMPP/WAMP)
define('DB_CHARSET', 'utf8mb4');                   // Codificación de caracteres 

/**
 * Función para obtener la conexión a la base de datos
 * @return PDO Objeto de conexión PDO
 * @throws PDOException Si la conexión falla
 */
function getDBConnection() {
    
    static $pdo = null;
    
    // Si ya existe una conexión, la reutilizamos
    if ($pdo !== null) {
        return $pdo;
    }
    
    try {
        // Cadena que le dice a PDO cómo conectarse a la base de datos
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        
        // Opciones de PDO para mejorar seguridad y funcionalidad
        $options = [
            // Lanza excepciones en caso de error
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            
            // Devuelve arrays asociativos 
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            
            // EMULATE_PREPARES: false para usar prepared statements reales
            PDO::ATTR_EMULATE_PREPARES => false,
            
            // PERSISTENT: false para no usar conexiones persistentes
            PDO::ATTR_PERSISTENT => false
        ];
        
        // Crear la conexión PDO
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        
        return $pdo;
        
    } catch (PDOException $e) {
        
        die("Error de conexión a la base de datos: " . $e->getMessage());
        
    }
}

