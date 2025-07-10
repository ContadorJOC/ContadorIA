# Usa la imagen oficial de PHP con soporte CLI
FROM php:8.2-cli

# Instala extensiones necesarias (PDO + SQLite)
RUN docker-php-ext-install pdo pdo_sqlite

# Copia todos los archivos de tu proyecto al contenedor
COPY . /app

# Define el directorio de trabajo dentro del contenedor
WORKDIR /app

# Expone el puerto que Render usará (10000 por defecto)
EXPOSE 10000

# Comando para iniciar el servidor embebido de PHP
CMD ["php", "-S", "0.0.0.0:10000", "-t", "."]
