# Build Deploy Package

## 🎯 O que faz este script?

O script `build-deploy.sh` gera um pacote completo `.tar.gz` com tudo necessário para fazer deploy na OCI Free Tier.

## 📦 Conteúdo do Pacote Gerado

O pacote inclui:

-   ✅ **megasena-image.tar** - Imagem Docker completa da aplicação
-   ✅ **docker-compose.yml** - Orquestração dos containers
-   ✅ **docker/** - Configurações (Nginx, Supervisor, entrypoint)
-   ✅ **.env.example** - Template de variáveis de ambiente
-   ✅ **deploy.sh** - Script automatizado de deploy (executar no servidor)
-   ✅ **README.md** - Documentação completa de deploy
-   ✅ **BUILD_INFO.txt** - Informações da build (data, commit, etc)

## 🚀 Como Usar

### 1. Gerar o Pacote (Na sua máquina local)

```bash
# Executar o script de build
./build-deploy.sh
```

O script irá:

1. ✅ Buildar os assets frontend (`npm run build`)
2. ✅ Criar a imagem Docker
3. ✅ Exportar imagem para `.tar`
4. ✅ Copiar configurações necessárias
5. ✅ Gerar script de deploy automatizado
6. ✅ Comprimir tudo em `.tar.gz`

**Resultado**: Arquivo `megasena-deploy-YYYYMMDD_HHMMSS.tar.gz`

### 2. Upload para o Servidor

```bash
# Fazer upload via SCP
scp megasena-deploy-*.tar.gz opc@seu-ip-oci:~

# Ou via rsync (mais eficiente)
rsync -avz --progress megasena-deploy-*.tar.gz opc@seu-ip-oci:~
```

### 3. Deploy no Servidor (OCI)

```bash
# Conectar ao servidor
ssh opc@seu-ip-oci

# Extrair o pacote
tar -xzf megasena-deploy-*.tar.gz
cd megasena-deploy-*/

# Executar deploy automatizado
sudo ./deploy.sh
```

## 📋 Pré-requisitos no Servidor

O script `deploy.sh` verifica automaticamente, mas você precisa ter:

-   ✅ Docker instalado
-   ✅ Docker Compose instalado
-   ✅ Portas 80/443 abertas no firewall

### Instalação Rápida do Docker (se necessário)

```bash
# Ubuntu/Oracle Linux
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

# Reiniciar sessão para aplicar grupo docker
exit
```

## ⚙️ O que o deploy.sh faz?

O script automatizado no servidor:

1. ✅ Verifica se Docker está instalado
2. ✅ Carrega a imagem Docker do `.tar`
3. ✅ Cria arquivo `.env` (se não existir)
4. ✅ Para containers antigos
5. ✅ Cria diretórios necessários com permissões corretas
6. ✅ Inicia a aplicação com `docker-compose up -d`
7. ✅ Executa health check
8. ✅ Mostra logs e status

## 🔧 Configuração Manual do .env

Se preferir configurar manualmente:

```bash
# Após extrair, antes de rodar deploy.sh
cp .env.example .env
nano .env

# Configurações importantes:
# APP_URL=http://seu-ip-publico-oci
# APP_ENV=production
# APP_DEBUG=false
```

## 📊 Após o Deploy

```bash
# Ver logs em tempo real
docker-compose logs -f

# Status dos containers
docker-compose ps

# Testar aplicação
curl http://localhost

# Ver uso de recursos
docker stats
```

## 🔄 Atualizar Aplicação

Para fazer uma nova versão:

```bash
# 1. Na sua máquina local
./build-deploy.sh  # Gera novo pacote

# 2. Upload
scp megasena-deploy-*.tar.gz opc@seu-ip:~

# 3. No servidor
tar -xzf megasena-deploy-*.tar.gz
cd megasena-deploy-*/
docker-compose down  # Parar versão antiga
sudo ./deploy.sh     # Deploy nova versão
```

## 🆘 Troubleshooting

### Build falha

```bash
# Verificar se npm build funcionou
npm run build
ls -la public/build

# Verificar Docker
docker --version
docker ps
```

### Deploy falha no servidor

```bash
# Ver logs detalhados
docker-compose logs app

# Verificar permissões
sudo chown -R 1000:1000 storage database bootstrap/cache
sudo chmod -R 775 storage database bootstrap/cache

# Resetar e tentar novamente
docker-compose down -v
sudo ./deploy.sh
```

### Firewall bloqueando

```bash
# Oracle Linux
sudo firewall-cmd --list-all
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --reload

# Ubuntu
sudo ufw status
sudo ufw allow 80/tcp
sudo ufw enable
```

## 💡 Dicas

-   **Tamanho**: O `.tar.gz` ficará entre 150-300MB dependendo das dependências
-   **Tempo**: Build leva ~5-10 minutos, deploy ~2-3 minutos
-   **Rede**: Upload depende da sua velocidade (pode demorar em conexões lentas)
-   **Backup**: Sempre faça backup do `database.sqlite` antes de atualizar

## 🎯 Workflow Completo

```bash
# 1. Desenvolvimento local
git add .
git commit -m "Nova feature"
npm run build
git add public/build
git commit -m "Build assets"

# 2. Gerar pacote
./build-deploy.sh

# 3. Upload
scp megasena-deploy-*.tar.gz opc@ip-oci:~

# 4. Deploy
ssh opc@ip-oci
tar -xzf megasena-deploy-*.tar.gz
cd megasena-deploy-*/
sudo ./deploy.sh

# 5. Verificar
curl http://ip-oci
docker-compose ps
```

## 📝 Notas Importantes

-   ⚠️ O script assume que os assets já foram buildados (`public/build` existe)
-   ⚠️ Certifique-se de commitar os assets antes de gerar o pacote
-   ⚠️ O `.tar.gz` contém a imagem Docker completa (pode ser grande)
-   ⚠️ Sempre teste localmente antes de fazer deploy em produção
-   ⚠️ Altere a senha padrão após o primeiro acesso!

## 🔗 Links Úteis

-   [Docker Install](https://docs.docker.com/engine/install/)
-   [Docker Compose Install](https://docs.docker.com/compose/install/)
-   [OCI Firewall Config](https://docs.oracle.com/en-us/iaas/Content/Network/Concepts/securitylists.htm)
