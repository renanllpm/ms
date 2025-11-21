# 🚀 Deploy MegaSena - Oracle Cloud Infrastructure (Free Tier)

## 📋 Pré-requisitos

1. **Instância OCI Free Tier** configurada (Ubuntu/Oracle Linux)
2. **Docker** e **Docker Compose** instalados na instância
3. **Git** instalado
4. **Portas abertas**: 80 (HTTP) e 443 (HTTPS se configurar SSL)

## 🔧 Preparação da Instância OCI

### 1. Conectar via SSH
```bash
ssh -i sua-chave.pem opc@seu-ip-publico
```

### 2. Atualizar o sistema
```bash
sudo yum update -y  # Oracle Linux
# ou
sudo apt update && sudo apt upgrade -y  # Ubuntu
```

### 3. Instalar Docker
```bash
# Oracle Linux
sudo yum install -y docker
sudo systemctl start docker
sudo systemctl enable docker
sudo usermod -aG docker $USER

# Ubuntu
sudo apt install -y docker.io docker-compose
sudo systemctl start docker
sudo systemctl enable docker
sudo usermod -aG docker $USER
```

### 4. Instalar Docker Compose (se não instalado)
```bash
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
docker-compose --version
```

### 5. Configurar Firewall (OCI)
No painel da OCI:
- Vá em **Networking > Virtual Cloud Networks**
- Selecione sua VCN
- Clique em **Security Lists**
- Adicione regra de ingresso:
  - Source CIDR: `0.0.0.0/0`
  - IP Protocol: `TCP`
  - Destination Port Range: `80`
  - (Opcional) Porta `443` para HTTPS

No servidor (Firewall interno):
```bash
# Oracle Linux
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --reload

# Ubuntu
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

## 📦 Deploy da Aplicação

### 1. Build dos assets localmente (ANTES de commitar)
```bash
# Na sua máquina local
npm run build

# Verificar se a pasta public/build foi criada
ls -la public/build

# Commitar os assets
git add public/build
git commit -m "Build production assets"
git push origin main
```

### 2. Clonar o repositório
```bash
cd /home/opc  # ou /home/ubuntu
git clone https://github.com/seu-usuario/megasena.git
cd megasena
```

### 3. Configurar variáveis de ambiente
```bash
# Copiar o arquivo de produção
cp .env.production .env

# Editar com suas configurações
nano .env

# Importante: Adicionar seu domínio/IP em APP_URL
# APP_URL=http://seu-ip-publico
```

### 3. Build e iniciar os containers
```bash
# Build da imagem (primeira vez)
docker-compose build

# Iniciar os containers
docker-compose up -d

# Verificar logs
docker-compose logs -f
```

### 4. Verificar status
```bash
# Status dos containers
docker-compose ps

# Health check
curl http://localhost

# Logs em tempo real
docker-compose logs -f app
```

## 🔄 Comandos Úteis

### Atualizar aplicação
```bash
cd /home/opc/megasena
git pull origin main
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### Executar comandos Laravel
```bash
# Entrar no container
docker-compose exec app sh

# Rodar migrations
docker-compose exec app php artisan migrate

# Criar novo usuário admin
docker-compose exec app php artisan tinker
# >>> User::create(['name'=>'Admin', 'email'=>'admin@megasena.com', 'password'=>bcrypt('senha123'), 'is_admin'=>true]);

# Limpar cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan view:clear
```

### Backup do banco de dados
```bash
# Copiar banco SQLite do container
docker cp megasena-app:/var/www/html/database/database.sqlite ./backup-$(date +%Y%m%d).sqlite

# Ou fazer backup direto do volume
sudo cp ./database/database.sqlite ./backup-$(date +%Y%m%d).sqlite
```

### Restaurar backup
```bash
# Parar container
docker-compose down

# Restaurar arquivo
cp backup-20250121.sqlite ./database/database.sqlite

# Iniciar container
docker-compose up -d
```

### Monitoramento
```bash
# Ver uso de recursos
docker stats

# Logs de erro do Nginx
docker-compose exec app tail -f /var/log/nginx/error.log

# Logs do Laravel
docker-compose exec app tail -f /var/www/html/storage/logs/laravel.log
```

## 🔒 Segurança (Produção)

### 1. Configurar HTTPS com Let's Encrypt (Opcional)
```bash
# Instalar Certbot
sudo snap install --classic certbot

# Obter certificado
sudo certbot --nginx -d seu-dominio.com

# Renovação automática
sudo certbot renew --dry-run
```

### 2. Configurar variáveis sensíveis
```bash
# Gerar nova APP_KEY
docker-compose exec app php artisan key:generate

# Desabilitar debug
# No .env: APP_DEBUG=false
```

### 3. Atualizar usuário padrão
```bash
docker-compose exec app php artisan tinker
# >>> $user = User::where('email', 'renanllpm@gmail.com')->first();
# >>> $user->password = bcrypt('nova-senha-forte');
# >>> $user->save();
```

## 📊 Monitoramento de Recursos (OCI Free Tier)

A instância Free Tier tem recursos limitados:
- **RAM**: 1GB (A1) ou 2GB (E2)
- **CPU**: 1 core OCPU

Para otimizar:
```bash
# Ver uso de memória
free -h

# Ver processos
htop

# Limitar recursos do Docker (editar docker-compose.yml)
services:
  app:
    deploy:
      resources:
        limits:
          cpus: '0.5'
          memory: 512M
```

## 🆘 Troubleshooting

### Container não inicia
```bash
# Ver logs detalhados
docker-compose logs app

# Verificar sintaxe do nginx
docker-compose exec app nginx -t

# Verificar permissões
docker-compose exec app ls -la /var/www/html/storage
```

### Erro 500
```bash
# Ver logs do Laravel
docker-compose exec app tail -100 /var/www/html/storage/logs/laravel.log

# Limpar todos os caches
docker-compose exec app php artisan optimize:clear
```

### Banco de dados corrompido
```bash
# Resetar banco (CUIDADO: apaga todos os dados)
docker-compose exec app rm /var/www/html/database/database.sqlite
docker-compose exec app touch /var/www/html/database/database.sqlite
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan db:seed --force
```

## 📝 Notas Importantes

1. **Sempre faça backup** do banco de dados antes de atualizações
2. **Monitore o uso de recursos** da instância Free Tier
3. **Mantenha o sistema atualizado**: `sudo yum update -y` regularmente
4. **Configure alertas** no OCI Console para uso de CPU/RAM
5. **Use HTTPS em produção** para segurança

## 🎯 Acesso à Aplicação

Após o deploy:
- **Aplicação**: http://seu-ip-publico
- **Login Admin**: http://seu-ip-publico/login
- **Credenciais padrão**: renanllpm@gmail.com / mudar@123

**⚠️ IMPORTANTE: Altere a senha padrão imediatamente após o primeiro acesso!**

## 📞 Suporte

Em caso de problemas:
1. Verifique os logs: `docker-compose logs -f`
2. Verifique o health do container: `docker-compose ps`
3. Teste conectividade: `curl http://localhost`
4. Verifique firewall: `sudo firewall-cmd --list-all`
