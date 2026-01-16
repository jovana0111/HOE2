# Imagen oficial de PHP con Apache
FROM php:8.2-apache

# Copiamos todo el proyecto al directorio de Apache
COPY . /var/www/html/

# Habilitamos mod_rewrite por si necesitas rutas amigables
RUN a2enmod rewrite

# Exponemos el puerto 80
EXPOSE 80
