FROM amazonlinux:2

RUN yum -y update

RUN ln -sf /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

RUN amazon-linux-extras install php7.3 -y \
  && yum -y install php-pdo \
    php-mbstring \
    php-xml \
    php-bcmath \
    php-mysqlnd \
    php-pear \
    php-devel \
    gcc \
    make

RUN pecl install xdebug

RUN yum clean all

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php -r "if (hash_file('sha384', 'composer-setup.php') === 'c31c1e292ad7be5f49291169c0ac8f683499edddcfd4e42232982d0fd193004208a58ff6f353fde0012d35fdd72bc394') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
  && php composer-setup.php \
  && php -r "unlink('composer-setup.php');" \
  && mv composer.phar /usr/local/bin/composer

RUN ln -sf /dev/stdout /var/log/php-fpm/access.log \
  && ln -sf /dev/stderr /var/log/php-fpm/error.log

WORKDIR /usr/share/nginx/html

EXPOSE 9000
CMD ["php-fpm", "--nodaemonize"]
