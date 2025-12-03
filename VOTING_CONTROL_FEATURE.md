# Funcionalidade de Encerramento de Votação

## Descrição

Implementação de um sistema completo para abrir e encerrar a votação. Após encerramento, usuários podem apenas consultar os dados, mas não podem mais votar ou alterar suas votações.

## Arquivos Criados

### 1. Migration: `database/migrations/2025_12_03_000001_add_voting_status_to_settings.php`

-   Adiciona a configuração `voting_status` à tabela de settings
-   Valor padrão: `'open'` (votação aberta)
-   Valores possíveis: `'open'` | `'closed'`

### 2. Componente Livewire: `app/Livewire/Admin/VotingControl.php`

-   Componente para gerenciar o status da votação
-   Propriedades computadas:
    -   `votingStatus()`: retorna o status atual ('open' ou 'closed')
    -   `isVotingOpen()`: retorna boolean indicando se votação está aberta
-   Métodos:
    -   `toggleVotingStatus()`: alterna entre aberto e fechado, exibe toast de sucesso

### 3. View: `resources/views/livewire/admin/voting-control.blade.php`

-   Interface para admin controlar a votação
-   Botão para encerrar votação (quando aberta)
-   Botão para reabrir votação (quando encerrada)
-   Indicadores visuais com ícones e cores
-   Mensagem de aviso quando votação está encerrada
-   Suporte a dark mode

## Arquivos Modificados

### 1. `resources/views/dashboard.blade.php`

-   Adicionado componente `<livewire:admin.voting-control />` logo após as ações rápidas
-   Coloca o controle de votação em destaque no painel admin

### 2. `app/Livewire/PublicBet.php`

-   Adicionada propriedade computada `isVotingOpen()` para verificar status
-   Validação em `submitBet()` impede envio de votos quando votação está encerrada
-   Exibe toast de erro: "❌ Votação encerrada"

### 3. `app/Livewire/CheckBet.php`

-   Adicionada propriedade computada `isVotingOpen()` para verificar status
-   Validação em `startEditingNumbers()` impede edição de votação encerrada
-   Exibe toast de erro quando tenta editar após encerramento

### 4. `resources/views/livewire/public-bet.blade.php`

-   Adicionado banner vermelho informando votação encerrada (quando `!$this->isVotingOpen`)
-   Adicionada seção de mensagem dentro do formulário se votação está encerrada
-   Banner aparece de forma destacada com ícone de cadeado
-   Oferece link para consultar dados da votação

### 5. `resources/views/livewire/check-bet.blade.php`

-   Adicionado indicador de votação encerrada após buscar participante
-   Exibe aviso vermelho se votação está encerrada
-   Permite visualizar dados mas impede alterações

## Comportamento

### Quando votação está ABERTA:

-   ✅ Usuários podem votar normalmente
-   ✅ Usuários que se abstiveram podem alterar para votar
-   ✅ Novo banner não aparece
-   ✅ Todos os formulários funcionam normalmente

### Quando votação está ENCERRADA:

-   ❌ Usuários não podem criar novos votos
-   ❌ Usuários não podem alterar votações (incluindo abstinência)
-   ✅ Usuários podem consultar dados da votação
-   ✅ Banners vermelhos aparecem nas telas de votação
-   ✅ Mensagens de erro claras informam o usuário

## Como Usar

### Abrir/Encerrar votação:

1. Acesse o painel administrativo (`/dashboard`)
2. Na seção "Status da Votação", clique no botão:
    - "🔒 Encerrar Votação" para encerrar
    - "🔓 Reabrir Votação" para reabrir
3. Uma mensagem de sucesso será exibida

### Efeitos imediatos:

-   Usuários verão banners informando que votação foi encerrada
-   Tentativas de votar serão impedidas com mensagem de erro
-   Tentativas de editar votação serão impedidas com mensagem de erro

## Configuração

A configuração é armazenada automaticamente em `settings`:

-   Chave: `voting_status`
-   Valores: `open` (padrão) ou `closed`
-   O sistema usa cache e limpa automaticamente quando status é alterado

## Validações

1. **PublicBet.submitBet()**: Valida se votação está aberta antes de processar
2. **CheckBet.startEditingNumbers()**: Valida se votação está aberta antes de permitir edição
3. Todos os fluxos exibem mensagens de erro claras em português

## Próximas Melhorias Sugeridas

-   [ ] Adicionar timestamp de quando votação foi encerrada
-   [ ] Adicionar histórico de aberturas/encerramentos
-   [ ] Exportar relatório final quando votação é encerrada
-   [ ] Notificar participantes via email/WhatsApp quando votação é encerrada
-   [ ] Adicionar contador regressivo antes do encerramento automático
