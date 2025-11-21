# Imagem base da aplicação
FROM php:8.2-fpm-alpine

# Instalar dependências do sistema
RUN apk add --no-cache \
    nginx \
    supervisor \
    sqlite \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql pdo_sqlite zip

# Criar usuário para Laravel
RUN addgroup -g 1000 laravel && \
    adduser -D -u 1000 -G laravel laravel

# Diretório de trabalho
WORKDIR /var/www/html

# Configuração do Nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/http.d/default.conf

# Configuração do Supervisor
COPY docker/supervisord.conf /etc/supervisord.conf

# Configuração do PHP-FPM
RUN echo "php_admin_value[error_log] = /var/log/php-fpm/error.log" >> /usr/local/etc/php-fpm.d/www.conf && \
    echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf && \
    mkdir -p /var/log/php-fpm && \
    chown laravel:laravel /var/log/php-fpm

# Script de inicialização
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
