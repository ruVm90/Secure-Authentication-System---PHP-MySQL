
# 🔐 Sistema de Autenticación Seguro - PHP & MySQL

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Security](https://img.shields.io/badge/Security-First-green?style=for-the-badge&logo=security&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-yellow?style=for-the-badge)

Sistema completo de registro y autenticación de usuarios desarrollado con **PHP puro** y **MySQL**, implementando las **mejores prácticas de seguridad web** para proteger contra las vulnerabilidades más comunes.

---

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Seguridad Implementada](#-seguridad-implementada)
- [Tecnologías Utilizadas](#-tecnologías-utilizadas)
- [Instalación](#-instalación)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Lo que Aprendí](#-lo-que-aprendí)
- [Roadmap](#-roadmap)
- [Contacto](#-contacto)

---

## ✨ Características

- ✅ **Registro de usuarios** con validación robusta de contraseñas
- ✅ **Sistema de login/logout** seguro con gestión de sesiones
- ✅ **Dashboard personalizado** con lista de usuarios registrados
- ✅ **Página de error** dedicada con mensajes informativos
- ✅ **Validación dual** (Frontend con JavaScript + Backend con PHP)
- ✅ **Interfaz responsive** con diseño moderno y gradientes
- ✅ **Código completamente documentado** con explicaciones técnicas

---

##  Seguridad Implementada

🛡️ Este proyecto fue desarrollado con un **enfoque de seguridad primero**, implementando protección contra las vulnerabilidades más críticas:

### 1️⃣ Protección contra SQL Injection
- **PDO con Prepared Statements** en todas las consultas
- Los datos nunca se concatenan directamente en las queries
- Separación completa entre código SQL y datos del usuario

```php
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = ?");
$stmt->execute([$username]);
```

### 2️⃣ Protección contra XSS (Cross-Site Scripting)
- **Sanitización** de todas las entradas con `htmlspecialchars()`
- Conversión de caracteres especiales en entidades HTML
- Prevención de ejecución de código malicioso en el navegador

### 3️⃣ Protección contra CSRF (Cross-Site Request Forgery)
- **Tokens CSRF únicos** generados para cada formulario
- Verificación de tokens antes de procesar peticiones POST
- Prevención de peticiones no autorizadas desde sitios externos

### 4️⃣ Hashing Seguro de Contraseñas
- **BCRYPT** con `password_hash()` y `PASSWORD_DEFAULT`
- **Salt único automático** generado para cada contraseña
- Contraseñas **NUNCA almacenadas en texto plano**
- Verificación segura con `password_verify()`

### 5️⃣ Validación en Capas (Defense in Depth)
- Validación en **Frontend** (JavaScript) para experiencia de usuario
- Validación en **Backend** (PHP) para seguridad real
- Principio: "Nunca confiar en el cliente"

### 6️⃣ Headers de Seguridad HTTP
```php
X-Frame-Options: DENY              // Previene clickjacking
X-Content-Type-Options: nosniff    // Previene MIME sniffing
X-XSS-Protection: 1; mode=block    // Activa filtro XSS del navegador
```

### 7️⃣ Gestión Segura de Sesiones
- **Regeneración de ID** de sesión después del login (previene session fixation)
- **Destrucción completa** de sesión al hacer logout
- Eliminación de cookies de sesión del navegador

### 8️⃣ Protección de Rutas
- Verificación de autenticación antes de acceder a páginas protegidas
- Redirección automática al login si no hay sesión activa
- Prevención de acceso no autorizado a recursos

### 9️⃣ Validación de Fortaleza de Contraseña
- Mínimo 8 caracteres
- Al menos una letra mayúscula
- Al menos una letra minúscula
- Al menos un número

---

## 🛠️ Tecnologías Utilizadas

| Tecnología | Propósito |
|-----------|-----------|
| **PHP 8.0+** | Lenguaje backend |
| **MySQL 8.0+** | Base de datos relacional |
| **PDO** | Capa de abstracción de base de datos |
| **HTML5** | Estructura de páginas |
| **CSS3** | Estilos y diseño responsive |
| **JavaScript (Vanilla)** | Validaciones del lado del cliente |
| **BCRYPT** | Algoritmo de hashing para contraseñas |

### ¿Por qué estas tecnologías?

- **PHP Puro**: Para demostrar comprensión profunda del lenguaje sin depender de frameworks
- **PDO sobre mysqli**: Soporte multi-base de datos, mejor manejo de errores con excepciones
- **Vanilla JS**: Para mostrar conocimiento fundamental sin dependencias de librerías
- **BCRYPT**: Estándar de la industria para hashing de contraseñas con salt automático

---

## 🚀 Instalación

### Requisitos Previos

- PHP 7.4 o superior
- MySQL 5.7 o superior (o MariaDB 10.2+)
- Servidor web (Apache/Nginx) o XAMPP/WAMP/MAMP

### Paso 1: Clonar el Repositorio

```bash
git clone https://github.com/tu-usuario/sistema-autenticacion-php.git
cd sistema-autenticacion-php
```

### Paso 2: Configurar la Base de Datos

1. Accede a phpMyAdmin o tu cliente MySQL preferido
2. Ejecuta el script SQL:

```bash
mysql -u root -p < database.sql
```

O importa `database.sql` desde phpMyAdmin.

### Paso 3: Configurar Credenciales

Edita `config/database.php` con tus credenciales:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'usuarios_db');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
```

### Paso 4: Iniciar el Servidor

**Opción A: Con servidor integrado de PHP**
```bash
php -S localhost:8000
```

**Opción B: Con XAMPP/WAMP**
- Coloca el proyecto en `htdocs/` o `www/`
- Accede a `http://localhost/sistema-autenticacion-php/register.php`

### Paso 5: Acceder a la Aplicación

- **Registro**: `http://localhost:8000/register.php`
- **Login**: `http://localhost:8000/login.php`

---

## 📁 Estructura del Proyecto

```
sistema-autenticacion-php/
│
├── config/
│   └── database.php          # Configuración de PDO y conexión a MySQL
│
├── includes/
│   └── functions.php         # Funciones de seguridad y gestión de usuarios
│
├── screenshots/              # Capturas de pantalla para README
│   ├── registro.png
│   ├── login.png
│   ├── dashboard.png
│   └── error.png
│
├── register.php              # Formulario de registro con validación
├── login.php                 # Formulario de inicio de sesión
├── error.php                 # Página de error de autenticación
├── dashboard.php             # Panel principal (requiere autenticación)
├── logout.php                # Cierre de sesión seguro
├── database.sql              # Script SQL para crear base de datos
├── README.md                 # Este archivo
└── LICENSE                   # Licencia MIT

```

### Descripción de Archivos Clave

- **`config/database.php`**: Conexión PDO con singleton pattern y configuración de seguridad
- **`includes/functions.php`**: Todas las funciones reutilizables (sanitización, validación, CSRF, operaciones DB)
- **`register.php`**: Formulario con validación de fortaleza de contraseña y confirmación
- **`login.php`**: Autenticación con protección CSRF y manejo de errores
- **`dashboard.php`**: Página protegida que muestra lista de usuarios con búsqueda
- **`error.php`**: Página dedicada para errores de login con consejos útiles
- **`logout.php`**: Destrucción completa de sesión y cookies

---

## 💡 Lo que Aprendí

Este proyecto fue una oportunidad para profundizar en conceptos fundamentales de seguridad web:

### 🎯 Conceptos Técnicos

1. **Diferencia crítica entre validación cliente vs servidor**: El frontend puede ser bypaseado, el backend es la autoridad final
2. **Defense in Depth**: Múltiples capas de seguridad son mejores que una sola barrera
3. **Ataques CSRF**: Cómo funcionan y por qué los tokens son esenciales
4. **Hashing vs Encriptación**: Por qué BCRYPT es unidireccional y por qué eso es bueno
5. **Race Conditions**: Por qué las restricciones UNIQUE en la base de datos son cruciales
6. **Session Fixation**: Importancia de regenerar IDs de sesión después del login
7. **Timing Attacks**: Por qué usar `hash_equals()` en lugar de `==` para comparar tokens

### 🔍 Buenas Prácticas

- Separación de responsabilidades (config, lógica, vistas)
- Código autodocumentado con comentarios explicativos
- Preparación para escalabilidad (VARCHAR(255) para contraseñas)
- Mensajes de error genéricos para no revelar información
- Principio de privilegio mínimo en operaciones de base de datos

### 🚧 Desafíos Superados

- Implementar CSRF tokens sin usar frameworks
- Entender el flujo completo de autenticación desde cero
- Balance entre seguridad y experiencia de usuario
- Manejo correcto del ciclo de vida de sesiones PHP

---

## 🗺️ Roadmap

Mejoras futuras planificadas para el proyecto:

### Corto Plazo
- [ ] **Rate Limiting**: Limitar intentos de login (5 por IP/usuario)
- [ ] **Logging de Seguridad**: Registrar intentos fallidos y accesos sospechosos
- [ ] **Recuperación de Contraseña**: Sistema con tokens temporales por email
- [ ] **Validación de Email**: Confirmación por email al registrarse

### Mediano Plazo
- [ ] **Autenticación de Dos Factores (2FA)**: TOTP con Google Authenticator
- [ ] **Tests Unitarios**: PHPUnit para funciones críticas de seguridad
- [ ] **Docker**: Containerización para facilitar deployment
- [ ] **CI/CD**: GitHub Actions para testing automático

### Largo Plazo
- [ ] **API REST**: Endpoints JSON para consumo desde aplicaciones móviles
- [ ] **OAuth**: Login con Google/GitHub
- [ ] **Roles y Permisos**: Sistema de autorización multinivel
- [ ] **Audit Trail**: Historial completo de acciones del usuario

---

## 📚 Recursos y Referencias

Durante el desarrollo, estos recursos fueron fundamentales:

- [OWASP Top 10](https://owasp.org/www-project-top-ten/) - Vulnerabilidades web más críticas
- [PHP.net - Password Hashing](https://www.php.net/manual/en/book.password.php) - Documentación oficial de hashing
- [PDO Documentation](https://www.php.net/manual/en/book.pdo.php) - Uso correcto de PDO
- [OWASP CSRF Prevention](https://cheatsheetseries.owasp.org/cheatsheets/Cross-Site_Request_Forgery_Prevention_Cheat_Sheet.html) - Guía sobre CSRF

---

## 🤝 Contribuciones

Este es un proyecto de portfolio personal, pero **las sugerencias son bienvenidas**. Si encuentras algún problema de seguridad o tienes ideas de mejora:

1. Abre un **Issue** describiendo el problema o sugerencia
2. Si quieres contribuir con código, crea un **Pull Request**

---

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

---

## 👨‍💻 Contacto

**Tu Nombre**

- GitHub: [@tu-usuario](https://github.com/tu-usuario)
- LinkedIn: [tu-perfil](https://linkedin.com/in/tu-perfil)
- Email: tu.email@ejemplo.com

---

## 🙏 Agradecimientos

Desarrollado como parte de mi aprendizaje continuo en desarrollo web seguro y buenas prácticas de programación PHP.

---

<div align="center">

**Si este proyecto te resultó útil, considera darle una ⭐ en GitHub**

Hecho con ❤️ y muchas horas de investigación sobre seguridad web

</div>
