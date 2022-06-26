FROM php:7.4-apache

# 設定ファイルをコンテナ内で読み込ませる
ADD php.ini /usr/local/etc/php/
ADD 000-default.conf /etc/apache2/sites-enabled/

# composerインストール
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer

# その他インストール
RUN apt-get update \
&& apt-get install -y \
git \
zip \
unzip \
vim \
libzip-dev \
libpng-dev \
libpq-dev \
libfreetype6-dev \
libjpeg62-turbo-dev \
&& docker-php-ext-install pdo_mysql zip bcmath gd \
&& docker-php-ext-configure gd --with-freetype --with-jpeg \
&& docker-php-ext-install -j$(nproc) gd

# modRewriteを有効化
RUN mv /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled
RUN /bin/sh -c a2enmod rewrite

WORKDIR /var/www/rese-backend