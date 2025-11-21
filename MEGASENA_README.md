# 🍀 Sistema Mega-Sena - Laravel + TallStackUI

Sistema completo de escolha de números da Mega-Sena desenvolvido com Laravel 11, Livewire 3, TallStackUI e SQLite.

## 📋 Funcionalidades

-   ✅ Autenticação de usuários
-   ✅ Seleção interativa de 6 números (1-60)
-   ✅ Gerador de números aleatórios
-   ✅ Validação completa (cada usuário pode escolher apenas uma vez)
-   ✅ Visualização de todos os participantes
-   ✅ Interface responsiva e moderna com TallStackUI
-   ✅ Notificações toast para feedback ao usuário
-   ✅ Banco de dados SQLite

## 🚀 Instalação

O projeto já está configurado. Para começar:

### 1. Rodar as Migrations

```bash
php artisan migrate
```

### 2. (Opcional) Popular com dados de teste

```bash
php artisan db:seed --class=MegasenaSeeder
```

Isso criará 3 usuários de teste com escolhas já feitas:

-   **Email:** joao@example.com | **Senha:** password
-   **Email:** maria@example.com | **Senha:** password
-   **Email:** pedro@example.com | **Senha:** password

### 3. Iniciar o servidor

```bash
php artisan serve
```

### 4. Compilar assets (em outro terminal)

```bash
npm run dev
```

### 5. Acessar a aplicação

Abra o navegador em: `http://localhost:8000`

## 📁 Estrutura do Projeto

### Models

-   `app/Models/User.php` - Usuário com relação `hasOne` para MegasenaChoice
-   `app/Models/MegasenaChoice.php` - Armazena escolhas dos usuários

### Livewire Components

-   `app/Livewire/MegasenaSelector.php` - Componente de seleção de números
-   `app/Livewire/ParticipantsList.php` - Lista de participantes

### Views

-   `resources/views/dashboard.blade.php` - Dashboard principal
-   `resources/views/participants.blade.php` - Página de participantes
-   `resources/views/livewire/megasena-selector.blade.php` - Interface de seleção
-   `resources/views/livewire/participants-list.blade.php` - Lista de participantes

### Migration

-   `database/migrations/2025_11_21_000001_create_megasena_choices_table.php`

## 🎯 Como Usar

### Para o Usuário Final

1. **Registrar-se** ou **fazer login**
2. No dashboard, você verá o grid com 60 números
3. **Clique** nos números para selecionar (máximo 6)
4. Use **"Gerar Aleatório"** para sortear números automaticamente
5. Use **"Limpar"** para resetar sua seleção
6. Quando tiver 6 números selecionados, clique em **"Confirmar Escolha"**
7. ⚠️ **Atenção:** Após confirmar, não será possível alterar!
8. Clique em **"Ver Todos os Participantes"** para ver as escolhas de todos

### Regras de Negócio

-   ✅ Cada usuário pode escolher **apenas uma vez**
-   ✅ Devem ser selecionados **exatamente 6 números**
-   ✅ Números devem estar entre **1 e 60**
-   ✅ Não pode haver **números duplicados**
-   ✅ Constraint `UNIQUE` no banco impede duplicação

## 🎨 Componentes TallStackUI Utilizados

-   `x-card` - Containers principais
-   `x-button` - Botões de ação
-   `x-badge` - Contadores e status
-   `x-alert` - Mensagens informativas
-   `toast()` - Notificações de feedback

## 🔐 Segurança

-   Middleware `auth` protege todas as rotas
-   Validação de entrada no backend
-   Constraint de banco de dados para prevenir duplicação
-   Try-catch para tratamento de exceções

## 📊 Banco de Dados

### Tabela `megasena_choices`

| Campo      | Tipo      | Descrição           |
| ---------- | --------- | ------------------- |
| id         | bigint    | ID da escolha       |
| user_id    | bigint    | ID do usuário (FK)  |
| numbers    | json      | Array com 6 números |
| created_at | timestamp | Data da escolha     |
| updated_at | timestamp | Última atualização  |

**Constraint:** `UNIQUE(user_id)` - Garante uma escolha por usuário

## 🧪 Testes

Para criar um novo usuário de teste manualmente:

```bash
php artisan tinker
```

```php
$user = \App\Models\User::create([
    'name' => 'Seu Nome',
    'email' => 'seu@email.com',
    'password' => bcrypt('password')
]);
```

## 🛠️ Comandos Úteis

```bash
# Limpar cache
php artisan optimize:clear

# Ver rotas
php artisan route:list

# Resetar banco de dados
php artisan migrate:fresh --seed

# Rodar testes
php artisan test
```

## 📱 Responsividade

-   **Mobile:** Grid de 6 colunas
-   **Tablet:** Grid de 8 colunas
-   **Desktop:** Grid de 10 colunas

## 🎨 Paleta de Cores

-   **Verde principal:** Tema Mega-Sena (`green-500`, `green-600`)
-   **Roxo:** Botão "Gerar Aleatório" (`purple-600`)
-   **Cinza:** Botões neutros e backgrounds

## 📝 Melhorias Futuras

-   [ ] Sistema de sorteio automático
-   [ ] Histórico de sorteios anteriores
-   [ ] Estatísticas de números mais escolhidos
-   [ ] Notificações por email
-   [ ] Export de dados para CSV/Excel
-   [ ] API REST para integração
-   [ ] Múltiplos concursos simultâneos

## 🤝 Contribuindo

Sinta-se à vontade para contribuir com melhorias!

## 📄 Licença

Este projeto é open-source e está disponível sob a licença MIT.

---

**Desenvolvido com ❤️ usando Laravel + TallStackUI**

💚 Boa sorte na Mega-Sena!
