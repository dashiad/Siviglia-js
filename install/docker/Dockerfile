FROM ubuntu:16.04
RUN apt-get update
RUN apt-get -y install nginx
RUN apt-get -y install php7.0-fpm
RUN apt-get -y install php-xdebug php-curl php-mbstring php-mcrypt php-mysql
#COPY nginx/adtopy /etc/nginx/sites-available
RUN rm -rf /etc/nginx/sites-enabled
RUN mkdir /var/www/config
COPY ../config/CONFIG_default.php /var/www/config
COPY ../config/localConfig.php /var/www/config
COPY ../php/xdebug.ini /etc/php/7.0/mods-available
# NOTA : Hay que linkar manualmente /var/www/adtopy/install/nginx en /etc/nginx/sites-enabled,
# ya que no estaran disponibles hasta que se monte el volumen, y el volumen no puede montarse
# en el Dockerfile, sino desde linea de comando.
RUN mkdir /var/www/adtopy

