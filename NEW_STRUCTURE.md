# 🎉 Sistema Mega-Sena Reestruturado!

## 📋 Nova Arquitetura

### **Separação: Usuários vs Participantes**

#### 👥 **Usuários (Administradores)**

-   Acesso ao sistema via login tradicional
-   Apenas admins podem fazer login
-   Gerenciam todo o sistema

#### 🎲 **Participantes (Apostadores)**

-   **Sem autenticação necessária**
-   Acesso público para fazer apostas
-   Recebem código de acesso único

---

## ✨ Funcionalidades Implementadas

### 1️⃣ **Página Pública de Apostas** (/)

**Acesso:** Qualquer pessoa, sem login

✅ Formulário de aposta com:

-   Nome completo
-   Telefone (obrigatório)
-   E-mail (opcional)
-   Valor da aposta
-   Seleção de 6 números (1-60)
-   Upload de comprovante (opcional)

✅ Após confirmação:

-   **Código de acesso gerado** automaticamente (8 caracteres)
-   Tela de sucesso com código
-   Números escolhidos exibidos

### 2️⃣ **Tabela Participants**

```sql
participants:
- id
- name
- email (nullable)
- phone
- access_code (único)
- numbers (json - 6 números)
- amount (decimal)
- paid (boolean)
- payment_proof (arquivo)
- paid_at (timestamp)
- notes (texto)
- timestamps
```

### 3️⃣ **Painel Admin - Gerenciar Participantes** (/admin/participants)

**Acesso:** Apenas administradores

✅ **Estatísticas Financeiras:**

-   Total de participantes
-   Participantes pagos/pendentes
-   Total arrecadado (R$)
-   Total pago (R$)
-   Total pendente (R$)

✅ **Tabela de Participantes:**

-   Código de acesso
-   Nome e contato
-   Números escolhidos (bolinhas verdes)
-   Valor da aposta
-   Status (PAGO/PENDENTE)
-   Link para comprovante
-   Ações:
    -   ✅ Marcar como pago/não pago
    -   🗑️ Excluir participante

✅ **Filtros:**

-   Busca por nome, telefone ou código
-   Filtro: Todos / Pagos / Pendentes

### 4️⃣ **Dashboard Admin Modernizado** (/dashboard)

✅ Cards de acesso rápido:

-   💰 Gerenciar Participantes
-   📊 Estatísticas
-   👥 Usuários Admin

✅ Instruções de como funciona
✅ Link público para compartilhar
✅ Botão copiar link

### 5️⃣ **Menu Lateral Atualizado**

Para admins:

-   Dashboard
-   💰 Gerenciar Participantes
-   📊 Estatísticas
-   Gerenciar Usuários

### 6️⃣ **Rotas**

```php
// Público (sem auth)
GET  /              → Página de apostas públicas
GET  /apostar       → Alternativa

// Admin (com auth + admin)
GET  /dashboard                 → Dashboard admin
GET  /admin/participants        → Gerenciar participantes
GET  /admin/statistics          → Estatísticas
GET  /users                     → Gerenciar usuários admin
```

---

## 💰 Fluxo Completo

### **Para Participantes:**

1. **Acessar** `http://seusite.com/`
2. **Preencher** dados pessoais
3. **Escolher** 6 números
4. **(Opcional)** Enviar comprovante
5. **Confirmar** aposta
6. **Receber** código de acesso único
7. **Guardar** código para consultas futuras

### **Para Administradores:**

1. **Fazer login** como admin
2. **Acessar** "Gerenciar Participantes"
3. **Visualizar** todos os participantes
4. **Ver** comprovantes enviados
5. **Marcar** como pago quando confirmar
6. **Acompanhar** totais arrecadados
7. **Filtrar** por status de pagamento

---

## 📊 Dados Financeiros

### Métricas Disponíveis:

-   ✅ Total de participantes
-   ✅ Quantos pagaram
-   ✅ Quantos estão pendentes
-   ✅ Valor total das apostas
-   ✅ Valor total recebido
-   ✅ Valor pendente
-   ✅ Valor individual de cada aposta

### Ações no Painel:

-   ✅ Marcar participante como "pago"
-   ✅ Marcar participante como "não pago"
-   ✅ Ver comprovante enviado
-   ✅ Excluir participante
-   ✅ Filtrar por status
-   ✅ Buscar por nome/telefone/código

---

## 🔐 Segurança

### Níveis de Acesso:

**Nível 1 - Público:**

-   Pode fazer apostas
-   Não pode ver outros participantes
-   Não pode alterar status de pagamento

**Nível 2 - Admin:**

-   Pode ver todos os participantes
-   Pode gerenciar pagamentos
-   Pode ver estatísticas
-   Pode gerenciar outros admins
-   Acesso total ao sistema

---

## 📁 Arquivos Criados/Modificados:

### **NOVOS:**

```
✅ database/migrations/2025_11_21_000003_create_participants_table.php
✅ app/Models/Participant.php
✅ app/Livewire/PublicBet.php
✅ app/Livewire/Admin/ManageParticipants.php
✅ resources/views/livewire/public-bet.blade.php
✅ resources/views/livewire/admin/manage-participants.blade.php
✅ resources/views/admin/participants.blade.php
```

### **MODIFICADOS:**

```
✅ routes/web.php
✅ resources/views/dashboard.blade.php
✅ resources/views/layouts/app.blade.php
```

### **REMOVIDOS:**

```
❌ app/Livewire/MegasenaSelector.php (não usado mais)
❌ app/Livewire/ParticipantsList.php (não usado mais)
❌ app/Models/MegasenaChoice.php (substituído por Participant)
```

---

## 🚀 Como Testar:

### 1. **Testar Aposta Pública**

```
1. Abrir navegador em: http://localhost:8000/
2. Preencher formulário
3. Escolher 6 números
4. (Opcional) Enviar comprovante
5. Confirmar
6. Anotar código gerado
```

### 2. **Testar Painel Admin**

```
1. Login: admin@megasena.com / admin123
2. Menu: "Gerenciar Participantes"
3. Ver lista de participantes
4. Clicar em botão verde/vermelho para marcar pago
5. Ver estatísticas financeiras
```

### 3. **Testar Upload de Comprovante**

```
1. Fazer aposta pública
2. Enviar imagem/PDF
3. Login como admin
4. Clicar no ícone de documento
5. Comprovante abre em nova aba
```

---

## 💡 Recursos Adicionais:

### Model Participant:

```php
// Gerar código único
Participant::generateAccessCode()

// Marcar como pago
$participant->markAsPaid()

// Marcar como não pago
$participant->markAsUnpaid()

// Números ordenados
$participant->sorted_numbers

// Telefone formatado
$participant->formatted_phone

// Escopos
Participant::paid()->get()
Participant::unpaid()->get()
```

---

## 📝 Observações Importantes:

1. **Comprovantes** são salvos em `storage/app/public/payment-proofs/`

    - Execute: `php artisan storage:link` se não existir

2. **Código de acesso** é único e imutável

    - 8 caracteres alfanuméricos
    - Maiúsculas
    - Exemplo: `AB12CD34`

3. **Sistema antigo** (`megasena_choices`) ainda existe no banco

    - Pode ser removido ou mantido para histórico
    - Nova tabela: `participants`

4. **Login** agora é apenas para admins
    - Participantes não fazem login
    - Acesso público direto

---

## 🎯 Próximos Passos Sugeridos:

-   [ ] Página de consulta de aposta (participante usa código)
-   [ ] Notificações por SMS/Email quando marcado como pago
-   [ ] Relatório de apostas em PDF/Excel
-   [ ] Configuração do valor da aposta
-   [ ] Sistema de múltiplos concursos/sorteios
-   [ ] QR Code para pagamento PIX

---

**✨ Sistema completamente reestruturado e funcional!**

🎲 Agora você tem um sistema profissional de gestão de apostas com controle financeiro completo!
