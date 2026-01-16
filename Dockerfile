FROM php:8.2-apache

# Copiar archivos
COPY . /var/www/html/

# Configurar permisos
RUN chmod -R 755 /var/www/html

# Puerto expuesto
EXPOSE 80