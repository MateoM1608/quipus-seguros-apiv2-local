FROM centos:7

ADD .repo/nginx.repo /etc/yum.repos.d/nginx.repo
ADD .repo/mysql.repo /etc/yum.repos.d/mysql-community.repo

RUN rpm --import https://repo.mysql.com/RPM-GPG-KEY-mysql

RUN yum -y install mysql-community-client-5.7.30-1.el7

ADD .configuration/nginx.conf /etc/nginx/nginx.conf
ADD .configuration/default.conf /etc/nginx/conf.d/default.conf

ADD  . /var/www/quipus

RUN yum -y install epel-release

RUN yum -y install python-pip
RUN pip install supervisor

ADD .configuration/supervisord.conf /etc/

RUN yum -y --setopt=tsflags=nodocs update
RUN yum -y --setopt=tsflags=nodocs --nogpgcheck install epel-release
RUN yum -y --setopt=tsflags=nodocs --nogpgcheck install http://rpms.remirepo.net/enterprise/remi-release-7.rpm
RUN yum -y --setopt=tsflags=nodocs --nogpgcheck --enablerepo=remi-php73 install \
        php-cli \
        php-fpm \
        php-bcmath \
        php-gd \
        php-common \
        php-intl \
        php-json \
        php-ldap  \
        php-mbstring \
        php-mcrypt \
        php-process \
        php-opcache \
        php-pdo \
        php-pear \
        php-pecl-redis \
        php-mysqlnd \
        php-soap \
        php-tidy \
        php-openssl \
        php-xml \
        php-xmlrpc \
        php-zip \
        git \
        && yum clean all

RUN yum install -y libXrender fontconfig libXext

COPY .configuration/php/php.ini /etc/php.d/php.ini
COPY .configuration/php/opcache.ini /etc/php.d/10-opcache.ini
COPY .configuration/php/www.conf /etc/php-fpm.d/
RUN mkdir /var/run/php-fpm

RUN mkdir /var/www/archivos && mkdir /var/www/archivos/documentos_generados
RUN chmod 777 -R /var/www/archivos

RUN yum -y install nginx

#RUN yum -y install htop

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

RUN composer self-update --stable

RUN cd /var/www/quipus && composer install

RUN chmod 777 -R /var/www/quipus/storage

EXPOSE 80
EXPOSE 443
EXPOSE 8080
EXPOSE 587

VOLUME ["/etc/nginx/conf.d", "/var/www"]

CMD ["supervisord", "-n"]
