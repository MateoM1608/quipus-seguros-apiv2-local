FROM centos:7

# Actualizar y configurar repositorios
RUN yum -y update && \
    yum -y install epel-release && \
    yum -y install https://rpms.remirepo.net/enterprise/remi-release-7.rpm && \
    rpm --import https://repo.mysql.com/RPM-GPG-KEY-mysql

# Instalar dependencias de Python y Supervisor
RUN yum -y install python-pip && \
    pip install supervisor

# Instalar cliente MySQL
RUN yum -y install mysql-community-client

# Instalar PHP 7.4 y extensiones necesarias
RUN yum -y --enablerepo=remi-php74 install \
    php-cli php-fpm php-mysqlnd php-zip git && \
    yum clean all

# Configuración y permisos
COPY . /var/www/quipus
RUN composer install --no-dev --optimize-autoloader && \
    chmod 777 -R /var/www/quipus/storage

# Exponer puertos
EXPOSE 80 443

# Ejecutar supervisord
CMD ["supervisord", "-n"]
