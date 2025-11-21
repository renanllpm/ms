#!/bin/sh
set -e

echo "🚀 Iniciando MegaSena Application..."

# Instalar extensões PHP necessárias
echo "📦 Instalando extensões PHP..."
apk add --no-cache nginx supervisor sqlite libzip-dev zip unzip curl
docker-php-ext-install pdo_mysql pdo_sqlite zip 2>/dev/null || true

# Criar usuário laravel se não existir
if ! id -u laravel >/dev/null 2>&1; then
    addgroup -g 1000 laravel 2>/dev/null || true
    adduser -D -u 1000 -G laravel laravel 2>/dev/null || true
fi

# Configurar PHP-FPM logs
mkdir -p /var/log/php-fpm
echo "php_admin_value[error_log] = /var/log/php-fpm/error.log" >> /usr/local/etc/php-fpm.d/www.conf 2>/dev/null || true
echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf 2>/dev/null || true

# Aguardar um momento para garantir que o filesystem está pronto
sleep 2

# Verificar se o arquivo .env existe, senão criar a partir do .env.example
if [ ! -f /var/www/html/.env ]; then
    echo "📝 Criando arquivo .env..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Ajustar permissões do .env antes de gerar chave
chown laravel:laravel /var/www/html/.env
chmod 664 /var/www/html/.env

# Gerar chave da aplicação se não existir
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
    echo "🔑 Gerando APP_KEY..."
    php artisan key:generate --force
fi

# Ajustar permissões
echo "🔒 Ajustando permissões..."
chown -R laravel:laravel /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Criar diretório de cache se não existir
mkdir -p /var/www/html/storage/framework/cache/data
chown -R laravel:laravel /var/www/html/storage/framework/cache
chmod -R 775 /var/www/html/storage/framework/cache

# Criar banco de dados SQLite se não existir
if [ ! -f /var/www/html/database/database.sqlite ]; then
    echo "💾 Criando banco de dados SQLite..."
    touch /var/www/html/database/database.sqlite
    chown laravel:laravel /var/www/html/database/database.sqlite
    chmod 664 /var/www/html/database/database.sqlite
fi

# Limpar cache
echo "🧹 Limpando cache..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rodar migrações
echo "🗄️  Executando migrações..."
php artisan migrate --force

# Rodar seeders (apenas na primeira vez)
if [ ! -f /var/www/html/database/.seeded ]; then
    echo "🌱 Executando seeders..."
    php artisan db:seed --force
    touch /var/www/html/database/.seeded
fi

# Otimizar para produção
echo "⚡ Otimizando aplicação..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Criar link simbólico do storage
echo "🔗 Criando link simbólico do storage..."
php artisan storage:link || true

echo "✅ Aplicação pronta!"

# Iniciar Supervisor
exec /usr/bin/supervisord -c /etc/supervisord.conf
