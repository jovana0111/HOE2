FROM php:8.2-apache

# Copiar los archivos de la aplicación
COPY . /var/www/html/

# Configurar Apache para usar index.php por defecto
RUN echo "DirectoryIndex index.php" >> /etc/apache2/apache2.conf

# Habilitar módulos necesarios de Apache
RUN a2enmod rewrite

# Establecer permisos adecuados
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Puerto expuesto
EXPOSE 8080

# Configurar Apache para escuchar en el puerto 8080
RUN sed -i 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf && \
    sed -i 's/:80/:8080/g' /etc/apache2/sites-available/*.conf

# Comando de inicio
CMD ["apache2-foreground"]