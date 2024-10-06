FROM php:8.2-apache

RUN apt update -y
RUN apt upgrade -y
RUN apt install -y apache2 locales git
RUN locale-gen ja_JP.UTF-8

# Apache のモジュールを有効化
RUN a2enmod deflate expires rewrite actions

# install-php-extensions をインストール
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

# PHP のエクステンションをインストール
RUN install-php-extensions xdebug

RUN install-php-extensions opcache xdebug

COPY php.ini /etc/php.ini

# Composerインストール
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer

# タイムゾーン設定
RUN ln -sf  /usr/share/zoneinfo/Asia/Tokyo /etc/localtime

CMD ["apache2-foreground"]
