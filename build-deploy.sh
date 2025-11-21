#!/bin/bash

# Script para gerar pacote de deploy para OCI
# Este script cria um .tar com tudo necessário para deploy

set -e

echo "🚀 Gerando pacote de deploy para OCI..."

# Cores para output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configurações
BUILD_DIR=".build"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
ARCHIVE_NAME="megasena-deploy-${TIMESTAMP}.tar.gz"

# Limpar build anterior
echo -e "${BLUE}🧹 Limpando build anterior...${NC}"
rm -rf ${BUILD_DIR}
mkdir -p ${BUILD_DIR}

# 1. Build dos assets
echo -e "${BLUE}📦 Buildando assets frontend...${NC}"
npm run build

if [ ! -d "public/build" ]; then
    echo -e "${YELLOW}⚠️  Pasta public/build não encontrada. Verifique o build.${NC}"
    exit 1
fi

# 2. Build da imagem Docker
echo -e "${BLUE}🐳 Buildando imagem Docker...${NC}"
docker build -t megasena:latest .

# 3. Exportar imagem Docker para .tar
echo -e "${BLUE}💾 Exportando imagem Docker...${NC}"
docker save -o ${BUILD_DIR}/megasena-image.tar megasena:latest

# 4. Copiar arquivos necessários
echo -e "${BLUE}📋 Copiando arquivos de configuração...${NC}"
cp docker-compose.yml ${BUILD_DIR}/
cp -r docker ${BUILD_DIR}/
cp .env.production ${BUILD_DIR}/.env.example

# Criar Dockerfile dummy (não será usado, mas docker compose precisa dele)
cat > ${BUILD_DIR}/Dockerfile << 'DOCKERFILE_EOF'
# Este Dockerfile não é usado no deploy
# A imagem já está carregada via megasena-image.tar
FROM megasena:latest
DOCKERFILE_EOF

# 5. Criar script de deploy para o servidor
echo -e "${BLUE}📝 Criando script de deploy...${NC}"
cat > ${BUILD_DIR}/deploy.sh << 'EOF'
#!/bin/bash

# Script de deploy no servidor OCI
set -e

echo "🚀 Iniciando deploy do MegaSena..."

# Cores
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

# Verificar se Docker está instalado
if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker não está instalado!${NC}"
    echo "Instale o Docker primeiro: curl -fsSL https://get.docker.com -o get-docker.sh && sh get-docker.sh"
    exit 1
fi

if ! docker compose version &> /dev/null; then
    echo -e "${RED}❌ Docker Compose não está instalado!${NC}"
    echo "Instale o Docker Compose primeiro ou use o plugin do Docker"
    exit 1
fi

# Carregar imagem Docker
echo -e "${BLUE}📦 Carregando imagem Docker...${NC}"
docker load -i megasena-image.tar

# Configurar .env
if [ ! -f .env ]; then
    echo -e "${YELLOW}⚠️  Arquivo .env não encontrado. Criando a partir do exemplo...${NC}"
    cp .env.example .env
    
    # Ajustar permissões imediatamente
    chmod 664 .env
    
    echo -e "${YELLOW}📝 Configure o arquivo .env antes de continuar!${NC}"
    echo "Principais configurações:"
    echo "  - APP_URL: URL pública da aplicação"
    echo "  - APP_ENV: production"
    echo "  - APP_DEBUG: false"
    echo ""
    read -p "Deseja editar o .env agora? (s/N) " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Ss]$ ]]; then
        ${EDITOR:-nano} .env
    fi
fi

# Parar containers antigos
echo -e "${BLUE}🛑 Parando containers antigos...${NC}"
docker compose down 2>/dev/null || true

# Criar diretórios necessários
echo -e "${BLUE}📁 Criando diretórios...${NC}"
mkdir -p storage/app/private storage/app/public storage/logs
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views
mkdir -p bootstrap/cache database

# Ajustar permissões antes de iniciar
chmod -R 775 storage bootstrap/cache database
chown -R 1000:1000 storage bootstrap/cache database 2>/dev/null || true

# Iniciar aplicação
echo -e "${BLUE}🚀 Iniciando aplicação...${NC}"
docker compose up -d

# Aguardar container iniciar
echo -e "${BLUE}⏳ Aguardando container inicializar...${NC}"
sleep 10

# Verificar status
echo -e "${BLUE}📊 Verificando status...${NC}"
docker compose ps

# Verificar logs
echo -e "${BLUE}📄 Últimas linhas do log:${NC}"
docker compose logs --tail=20

# Health check
echo -e "${BLUE}🏥 Testando health check...${NC}"
if curl -f http://localhost > /dev/null 2>&1; then
    echo -e "${GREEN}✅ Aplicação está respondendo!${NC}"
else
    echo -e "${RED}❌ Aplicação não está respondendo. Verifique os logs:${NC}"
    echo "docker compose logs -f"
    exit 1
fi

echo ""
echo -e "${GREEN}✅ Deploy concluído com sucesso!${NC}"
echo ""
echo "📌 Próximos passos:"
echo "  1. Configure o firewall para permitir porta 80"
echo "  2. Acesse: http://$(curl -s ifconfig.me)"
echo "  3. Login padrão: renanllpm@gmail.com / mudar@123"
echo "  4. ALTERE A SENHA PADRÃO imediatamente!"
echo ""
echo "📊 Comandos úteis:"
echo "  - Ver logs: docker compose logs -f"
echo "  - Parar: docker compose down"
echo "  - Reiniciar: docker compose restart"
echo "  - Status: docker compose ps"
echo ""
EOF

chmod +x ${BUILD_DIR}/deploy.sh

# 6. Criar README de deploy
cat > ${BUILD_DIR}/README.md << 'EOF'
# 🚀 Deploy MegaSena - Pacote OCI

Este pacote contém tudo necessário para fazer deploy da aplicação MegaSena em uma instância OCI Free Tier.

## 📦 Conteúdo do Pacote

- `megasena-image.tar` - Imagem Docker da aplicação
- `docker-compose.yml` - Configuração do Docker Compose
- `docker/` - Configurações de Nginx, Supervisor e scripts
- `.env.example` - Template de variáveis de ambiente
- `deploy.sh` - Script automatizado de deploy
- `README.md` - Este arquivo

## 🔧 Pré-requisitos no Servidor

1. Docker instalado
2. Docker Compose instalado
3. Portas 80 e 443 liberadas no firewall

### Instalação rápida do Docker (se necessário):
```bash
# Ubuntu/Debian
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

## 📤 Upload para o Servidor

```bash
# Fazer upload do arquivo .tar.gz
scp megasena-deploy-*.tar.gz usuario@servidor:~

# Conectar ao servidor
ssh usuario@servidor

# Extrair arquivos
tar -xzf megasena-deploy-*.tar.gz
cd megasena-deploy-*/
```

## 🚀 Deploy

### Opção 1: Deploy Automatizado (Recomendado)
```bash
sudo ./deploy.sh
```

### Opção 2: Deploy Manual
```bash
# 1. Carregar imagem
docker load -i megasena-image.tar

# 2. Configurar ambiente
cp .env.example .env
nano .env  # Edite conforme necessário

# 3. Criar diretórios
mkdir -p storage/{app,logs,framework/{cache,sessions,views}} bootstrap/cache database

# 4. Iniciar aplicação
docker compose up -d

# 5. Verificar
docker compose ps
docker compose logs -f
```

## ⚙️ Configuração do .env

Edite o arquivo `.env` com suas configurações:

```bash
APP_NAME=MegaSena
APP_ENV=production
APP_DEBUG=false
APP_URL=http://seu-ip-ou-dominio

# Locale
APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR

# Database (SQLite - já configurado)
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite

# Cache e Session
CACHE_STORE=file
SESSION_DRIVER=database
QUEUE_CONNECTION=database

# Logs
LOG_CHANNEL=daily
LOG_LEVEL=error
```

## 🔒 Configurar Firewall OCI

### No Console OCI:
1. Vá em **Networking → Virtual Cloud Networks**
2. Selecione sua VCN
3. Clique em **Security Lists**
4. Adicione regra de Ingress:
   - Source CIDR: `0.0.0.0/0`
   - IP Protocol: `TCP`
   - Destination Port: `80`

### No Servidor (Oracle Linux):
```bash
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --reload
```

### No Servidor (Ubuntu):
```bash
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

## ✅ Verificação

```bash
# Status dos containers
docker compose ps

# Logs em tempo real
docker compose logs -f

# Teste local
curl http://localhost

# Teste externo (substitua pelo seu IP)
curl http://seu-ip-publico
```

## 🔄 Atualização

Para atualizar a aplicação:

1. Faça upload do novo pacote
2. Extraia em outro diretório
3. Pare a aplicação antiga: `docker compose down`
4. Execute o novo deploy.sh

## 🆘 Troubleshooting

### Container não inicia
```bash
docker compose logs app
```

### Permissões
```bash
sudo chown -R 1000:1000 storage bootstrap/cache database
sudo chmod -R 775 storage bootstrap/cache database
```

### Resetar aplicação
```bash
docker compose down -v
rm -rf storage/logs/* bootstrap/cache/* database/database.sqlite
./deploy.sh
```

## 📞 Acesso

- **Aplicação**: http://seu-ip-publico
- **Login Admin**: http://seu-ip-publico/login
- **Credenciais padrão**: 
  - Email: renanllpm@gmail.com
  - Senha: mudar@123
  
**⚠️ IMPORTANTE: Altere a senha padrão imediatamente!**

## 📊 Comandos Úteis

```bash
# Ver logs
docker compose logs -f

# Entrar no container
docker compose exec app sh

# Reiniciar
docker compose restart

# Parar
docker compose down

# Status e recursos
docker stats

# Backup do banco
docker cp megasena-app:/var/www/html/database/database.sqlite ./backup-$(date +%Y%m%d).sqlite
```

## 📝 Notas

- O banco de dados SQLite está em: `database/database.sqlite`
- Logs da aplicação: `storage/logs/laravel.log`
- A aplicação roda na porta 80
- Usuário padrão do container: laravel (UID 1000)
EOF

# 7. Criar arquivo de informações do build
cat > ${BUILD_DIR}/BUILD_INFO.txt << EOF
MegaSena - Build Information
============================

Build Date: $(date)
Build Time: ${TIMESTAMP}
Git Commit: $(git rev-parse --short HEAD 2>/dev/null || echo "N/A")
Git Branch: $(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "N/A")

Docker Image: megasena:latest
Package Name: ${ARCHIVE_NAME}

Contents:
- megasena-image.tar (Docker image)
- docker-compose.yml
- docker/ (configs)
- .env.example
- deploy.sh
- README.md

Deploy Instructions:
1. Upload ${ARCHIVE_NAME} to server
2. Extract: tar -xzf ${ARCHIVE_NAME}
3. Run: cd $(basename ${ARCHIVE_NAME} .tar.gz) && sudo ./deploy.sh

EOF

# 8. Comprimir tudo
echo -e "${BLUE}📦 Comprimindo pacote...${NC}"
cd ${BUILD_DIR}
tar -czf ../${ARCHIVE_NAME} *
cd ..

# 9. Limpar diretório temporário (opcional)
echo -e "${BLUE}🧹 Limpando arquivos temporários...${NC}"
rm -rf ${BUILD_DIR}

# 10. Informações finais
FILE_SIZE=$(du -h ${ARCHIVE_NAME} | cut -f1)

echo ""
echo -e "${GREEN}✅ Pacote de deploy criado com sucesso!${NC}"
echo ""
echo -e "${BLUE}📦 Arquivo:${NC} ${ARCHIVE_NAME}"
echo -e "${BLUE}📏 Tamanho:${NC} ${FILE_SIZE}"
echo ""
echo -e "${YELLOW}📤 Para fazer upload para o servidor:${NC}"
echo "   scp ${ARCHIVE_NAME} usuario@servidor:~"
echo ""
echo -e "${YELLOW}🚀 No servidor, execute:${NC}"
echo "   tar -xzf ${ARCHIVE_NAME}"
echo "   cd megasena-deploy-${TIMESTAMP}"
echo "   sudo ./deploy.sh"
echo ""
echo -e "${GREEN}✨ Pronto para deploy!${NC}"
