# Usamos la imagen PHP CLI
FROM php:8.2-cli

# Copiamos todos los archivos al contenedor
WORKDIR /app
COPY . /app

# Exponemos el puerto que Railway asigna
EXPOSE 8080

# Comando para iniciar el servidor PHP
CMD ["php", "-S", "0.0.0.0:8080", "-t", "."]
