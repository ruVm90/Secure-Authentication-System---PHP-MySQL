# Usar imagen oficial de PHP 8.2 con CLI (Command Line Interface)
# Esta es la base sobre la que construiremos
FROM php:8.2-cli

# Instalar las extensiones de PHP que necesitamos para conectar con MySQL
# pdo: Interfaz de base de datos
# pdo_mysql: Driver específico para MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Establecer el directorio de trabajo dentro del contenedor
# Todo nuestro código estará en /app
WORKDIR /app

# Copiar TODOS los archivos de tu proyecto al contenedor
# El primer punto (.) significa "todo en mi ordenador"
# El segundo /app significa "cópialo a /app en el contenedor"
COPY . /app

# Decirle a Docker que nuestra aplicación usará un puerto
# Railway asignará el puerto automáticamente con la variable $PORT
EXPOSE 8080

# El comando que se ejecutará cuando el contenedor inicie
# Inicia un servidor PHP en el puerto que Railway nos dé
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-8080} -t ."]