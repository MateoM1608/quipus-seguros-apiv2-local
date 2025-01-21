# Usar la imagen oficial de PHP con Apache
FROM php:7.2-apache

# Instalar dependencias necesarias para Laravel
RUN apt-get update && apt-get install -y \
    zip unzip curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configurar el directorio de trabajo
WORKDIR /var/www/html

# Copiar el proyecto al contenedor
COPY . /var/www/html

# Establecer permisos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer el puerto 80 para Apache
EXPOSE 80

# Comando de inicio para Apache
CMD ["apache2-foreground"]
