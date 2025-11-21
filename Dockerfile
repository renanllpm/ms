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
    && docker-php-ext-install pdo_mysql zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Criar usuário para Laravel
RUN addgroup -g 1000 laravel && \
    adduser -D -u 1000 -G laravel laravel

# Diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos da aplicação (incluindo assets já buildados)
COPY --chown=laravel:laravel . .

# Instalar dependências PHP (otimizado para produção)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Criar diretórios necessários e ajustar permissões
RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/logs \
    bootstrap/cache \
    database \
    && chown -R laravel:laravel storage bootstrap/cache database \
    && chmod -R 775 storage bootstrap/cache

# Criar banco de dados SQLite
RUN touch database/database.sqlite && chown laravel:laravel database/database.sqlite

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
