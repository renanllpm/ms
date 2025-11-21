# 👨‍💼 Sistema Administrativo - Mega-Sena

## 📊 Funcionalidades Administrativas

Sistema completo de análise estatística dos números mais escolhidos pelos participantes.

---

## 🔐 Acesso Administrativo

### Criar Usuário Admin

Para tornar um usuário administrador, você pode:

#### 1. Via Tinker (Recomendado)

```bash
php artisan tinker
```

```php
// Criar novo usuário admin
$admin = \App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('senha_segura'),
    'is_admin' => true
]);

// OU tornar um usuário existente admin
$user = \App\Models\User::where('email', 'usuario@example.com')->first();
$user->is_admin = true;
$user->save();
```

#### 2. Via Seeder

```bash
php artisan db:seed --class=MegasenaSeeder
```

Isso criará automaticamente:

-   **Admin:** admin@megasena.com | Senha: admin123
-   **Usuários de teste:** joao@example.com, maria@example.com, pedro@example.com | Senha: password

---

## 📈 Painel de Estatísticas

Acesse: `/admin/statistics`

### O que você verá:

#### 1️⃣ **Resumo Geral**

-   Total de escolhas realizadas
-   Total de números escolhidos
-   Média de números por escolha
-   Números disponíveis (1-60)

#### 2️⃣ **Top 10 - Números Mais Escolhidos** 🔥

-   Lista dos 10 números mais populares
-   Quantidade de vezes que cada número foi escolhido
-   Percentual de participação
-   Barra de progresso visual

**Exemplo:**

```
Número 07: 15x (50%)  ████████████████████
Número 13: 12x (40%)  ████████████████
Número 25: 10x (33%)  █████████████
```

#### 3️⃣ **Top 10 - Números Menos Escolhidos** ❄️

-   Lista dos 10 números menos populares
-   Útil para identificar números "esquecidos"
-   Mesma visualização dos mais escolhidos

#### 4️⃣ **Mapa de Calor (1-60)** 🗺️

-   Grid visual de TODOS os 60 números
-   Sistema de cores por popularidade:

    -   🔴 **Vermelho**: ≥ 50% (Muito escolhido)
    -   🟠 **Laranja**: 30-49% (Popular)
    -   🟡 **Amarelo**: 15-29% (Médio)
    -   🟢 **Verde**: 5-14% (Pouco escolhido)
    -   ⚫ **Cinza**: < 5% (Raro)

-   Hover sobre cada número mostra:
    -   Número
    -   Quantidade de escolhas
    -   Percentual

#### 5️⃣ **Últimas 5 Escolhas** 🕐

-   Feed das escolhas mais recentes
-   Nome do usuário
-   Números escolhidos
-   Tempo decorrido (ex: "há 2 horas")

---

## 🛡️ Segurança

### Middleware `IsAdmin`

Todas as rotas administrativas são protegidas por dois middlewares:

1. `auth` - Usuário deve estar autenticado
2. `admin` - Usuário deve ter `is_admin = true`

**Tentativa de acesso não autorizado:**

-   HTTP 403 Forbidden
-   Mensagem: "Acesso negado. Apenas administradores podem acessar esta página."

### Localização do Middleware

```
app/Http/Middleware/IsAdmin.php
```

### Registro no Bootstrap

```
bootstrap/app.php
```

---

## 🧭 Navegação

### Para Administradores

O menu lateral exibirá automaticamente:

-   🏠 Dashboard
-   👥 Participantes
-   **📊 Estatísticas Admin** ← Apenas para admins
-   👤 Users
-   ↩️ Welcome Page

### Para Usuários Comuns

Não verão a opção "Estatísticas Admin".

---

## 📊 Casos de Uso

### 1. Identificar Números Populares

Útil para:

-   Análise de padrões de escolha
-   Identificar tendências
-   Entender comportamento dos usuários

### 2. Estratégia de Divulgação

-   Mostrar números menos escolhidos para incentivar diversidade
-   Gamificação: "Escolha um número único!"

### 3. Relatórios Gerenciais

-   Total de participantes
-   Engajamento
-   Atividade recente

### 4. Auditoria

-   Verificar integridade das escolhas
-   Conferir distribuição dos números

---

## 🔧 Comandos Úteis

### Criar Admin Rapidamente

```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'name' => 'Super Admin',
    'email' => 'super@admin.com',
    'password' => bcrypt('senha123'),
    'is_admin' => true
]);
```

### Ver Todos os Admins

```bash
php artisan tinker
```

```php
\App\Models\User::where('is_admin', true)->get(['id', 'name', 'email']);
```

### Remover Status Admin

```bash
php artisan tinker
```

```php
$user = \App\Models\User::find(1);
$user->is_admin = false;
$user->save();
```

### Resetar e Popular Banco

```bash
php artisan migrate:fresh --seed
```

---

## 🎨 Personalização

### Ajustar Cores do Mapa de Calor

Edite: `app/Livewire/Admin/Statistics.php`

```php
public function getNumberColor(int $frequency): string
{
    $percentage = $this->getPercentage($frequency);

    // Ajuste os limites aqui
    if ($percentage >= 70) return 'red';    // Mudar de 50 para 70
    if ($percentage >= 40) return 'orange'; // Mudar de 30 para 40
    // ...
}
```

### Alterar Quantidade de Top Numbers

Edite: `app/Livewire/Admin/Statistics.php`

```php
// De 10 para 15, por exemplo
$this->topNumbers = array_slice($frequency, 0, 15, true);
```

### Adicionar Mais Estatísticas

No método `calculateStatistics()`, você pode adicionar:

```php
// Número mais escolhido de todos
$this->mostPopularNumber = array_key_first($frequency);

// Média de popularidade
$this->averageFrequency = $this->totalNumbers / 60;

// Números nunca escolhidos
$this->neverChosen = array_keys(array_filter($frequency, fn($count) => $count === 0));
```

---

## 📱 Responsividade

O painel administrativo é totalmente responsivo:

-   **Mobile**: Grid 6 colunas, cards empilhados
-   **Tablet**: Grid 8-10 colunas
-   **Desktop**: Grid completa de 10 colunas, estatísticas lado a lado

---

## 🚀 Melhorias Futuras

-   [ ] Export para Excel/CSV
-   [ ] Gráficos interativos (Chart.js)
-   [ ] Filtros por data
-   [ ] Comparação entre períodos
-   [ ] Análise de combinações mais frequentes
-   [ ] Previsão de próximos números baseada em ML
-   [ ] Dashboard em tempo real (WebSockets)
-   [ ] Notificações quando número atinge X escolhas

---

## 🐛 Troubleshooting

### "Acesso negado"

-   Verifique se o usuário tem `is_admin = true`
-   Confira se está logado

### "View not found"

-   Execute: `php artisan view:clear`
-   Execute: `php artisan optimize:clear`

### Estatísticas não carregam

-   Verifique se há escolhas no banco: `SELECT COUNT(*) FROM megasena_choices`
-   Execute: `php artisan livewire:discover`

### Middleware não funciona

-   Verifique `bootstrap/app.php`
-   Certifique-se que o alias 'admin' está registrado

---

## 📄 Arquivos Criados

```
app/
├── Http/
│   └── Middleware/
│       └── IsAdmin.php
├── Livewire/
│   └── Admin/
│       └── Statistics.php
└── Models/
    └── User.php (modificado)

resources/views/
├── admin/
│   └── statistics.blade.php
├── livewire/
│   └── admin/
│       └── statistics.blade.php
└── layouts/
    └── app.blade.php (modificado)

database/
└── migrations/
    └── 2025_11_21_000002_add_is_admin_to_users_table.php

routes/
└── web.php (modificado)

bootstrap/
└── app.php (modificado)
```

---

## 🎯 Checklist de Implementação

-   [x] Migration para campo `is_admin`
-   [x] Middleware `IsAdmin`
-   [x] Componente Livewire `Admin\Statistics`
-   [x] View de estatísticas completa
-   [x] Rotas administrativas protegidas
-   [x] Menu condicional para admins
-   [x] Seeder com usuário admin
-   [x] Mapa de calor visual
-   [x] Top 10 mais/menos escolhidos
-   [x] Feed de últimas escolhas
-   [x] Botão de atualização (refresh)
-   [x] Loading states
-   [x] Responsividade

---

**Desenvolvido com ❤️ para análise de dados da Mega-Sena**

📊 Dashboard completo para insights poderosos!
