FROM centos:7

# Añadir repositorios
ADD .repo/nginx.repo /etc/yum.repos.d/nginx.repo
ADD .repo/mysql.repo /etc/yum.repos.d/mysql-community.repo
RUN rpm --import https://repo.mysql.com/RPM-GPG-KEY-mysql

# Instalar dependencias
RUN yum -y install epel-release python-pip && \
    pip install supervisor && \
    yum -y install mysql-community-client-5.7.30-1.el7 && \
    yum -y install http://rpms.remirepo.net/enterprise/remi-release-7.rpm && \
    yum -y --enablerepo=remi-php74 install \
        php-cli php-fpm php-mysqlnd php-zip git && \
    yum clean all

# Configuración de Nginx y PHP
COPY .configuration/nginx.conf /etc/nginx/nginx.conf
COPY .configuration/default.conf /etc/nginx/conf.d/default.conf
COPY .configuration/php/php.ini /etc/php.d/php.ini
COPY .configuration/php/www.conf /etc/php-fpm.d/
RUN mkdir /var/run/php-fpm

# Configuración de Supervisor
ADD .configuration/supervisord.conf /etc/

# Código de la aplicación
ADD . /var/www/quipus
RUN composer install --no-dev --optimize-autoloader && \
    chmod 777 -R /var/www/quipus/storage

# Exponer puertos
EXPOSE 80 443

# Comando para iniciar Supervisor
CMD ["supervisord", "-n"]
