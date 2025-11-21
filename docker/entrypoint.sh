#!/bin/sh
set -e

echo "🚀 Iniciando MegaSena Application..."

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
php artisan cache:clear
php artisan view:clear
php artisan route:clear

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
