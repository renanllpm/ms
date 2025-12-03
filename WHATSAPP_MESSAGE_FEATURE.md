# Gerador de Mensagem WhatsApp - Resultado da Votação

## Descrição

Funcionalidade que permite gerar e copiar uma mensagem formatada com o resultado da votação, compilando dados de:

-   Total de participantes
-   Status de pagamento (pagos e pendentes)
-   Números mais escolhidos com percentual
-   Indicação de qual será o jogo final

## Componentes

### VotingControl.php - Novos Métodos

#### `compileVotingData(): array`

Compila todos os dados necessários:

-   `totalParticipants` - Total de pessoas que votaram
-   `paidCount` - Quantos pagaram
-   `unpaidCount` - Quantos ainda não pagaram
-   `topNumbers` - Array dos 6 números mais votados
-   `totalVotes` - Total de votos (igual a totalParticipants)

#### `getPercentage(int $count, int $total): float`

Calcula o percentual de um número em relação ao total.

#### `generateWhatsAppMessage(): string`

Gera a mensagem formatada com:

-   Título e emojis
-   Resumo de participantes
-   Lista dos 6 números mais escolhidos com votos e percentual
-   Jogo final formatado
-   Mensagem de boa sorte

#### `copyWhatsAppMessage(): void`

Abre um modal para visualizar a mensagem antes de copiar.

#### `confirmCopyMessage(): void`

Copia a mensagem para a área de transferência.

#### `closeMessagePreview(): void`

Fecha o modal de preview.

## Interface - voting-control.blade.php

### Mudanças

-   Botão "💬 Gerar WhatsApp" aparece apenas quando votação está **encerrada** (ao lado do botão "Reabrir")
-   Clique no botão abre um **modal de preview**
-   Modal mostra a mensagem formatada
-   Dois botões no modal: "Cancelar" e "✅ Copiar Mensagem"

### Fluxo do Usuário

1. Admin encerra a votação clicando "🔒 Encerrar Votação"
2. Botão "💬 Gerar WhatsApp" aparece
3. Admin clica no botão
4. Modal com preview da mensagem abre
5. Admin verifica se está tudo correto
6. Clica "✅ Copiar Mensagem" e mensagem é copiada
7. Toast de sucesso aparece
8. Admin cola no WhatsApp

## Exemplo de Mensagem Gerada

```
🎰 *RESULTADO DA VOTAÇÃO - MEGA-SENA* 🎰

📊 *RESUMO DE PARTICIPANTES*
Total: 12
✅ Pagos: 10
⏳ Pendente: 2

🔢 *NÚMEROS QUE SERÃO JOGADOS*
(Mais votados pela grupo)

15 ➜ 11 votos (91.7%)
28 ➜ 10 votos (83.3%)
42 ➜ 9 votos (75.0%)
07 ➜ 8 votos (66.7%)
33 ➜ 7 votos (58.3%)
51 ➜ 6 votos (50.0%)

🎯 *JOGO FINAL:* 15 - 28 - 42 - 07 - 33 - 51

💡 Este será o jogo que o grupo vai jogar na próxima Mega-Sena!

Boa sorte! 🍀
```

## Características

✅ **Formatação WhatsApp** - Usa asteriscos (\*) para negrito  
✅ **Emojis** - Mensagem visualmente atraente  
✅ **Preview Modal** - Admin vê antes de copiar  
✅ **Copiar Automático** - Copia para área de transferência  
✅ **Percentual Preciso** - Calcula percentual por número  
✅ **6 Números** - Seleciona os 6 mais votados  
✅ **Dark Mode** - Modal compatível com tema escuro  
✅ **Responsivo** - Funciona em qualquer dispositivo

## Funcionamento Técnico

### Quando o botão é clicado:

1. `copyWhatsAppMessage()` é chamado no Livewire
2. Chama `generateWhatsAppMessage()` que compila os dados
3. Armazena mensagem em `$messagePreview`
4. Define `$showMessagePreview = true` para exibir modal
5. Modal renderiza com preview da mensagem

### Quando confirma cópia:

1. `confirmCopyMessage()` é chamado
2. Dispara evento Livewire `copy-whatsapp-message`
3. JavaScript no frontend intercepta o evento
4. `navigator.clipboard.writeText()` copia para clipboard
5. Modal fecha (`$showMessagePreview = false`)
6. Toast de sucesso é exibido

## Tecnologias Utilizadas

-   **Livewire** - Componente reativo
-   **Alpine.js** - Listeners de eventos (via Livewire)
-   **Clipboard API** - Copiar para área de transferência
-   **TallStackUi** - Toast de sucesso

## Próximas Melhorias Sugeridas

-   [ ] Adicionar botão para enviar diretamente via API WhatsApp Business
-   [ ] Permitir customização da mensagem antes de copiar
-   [ ] Adicionar histórico de mensagens enviadas
-   [ ] QR Code com link para consultar resultado
-   [ ] Enviar por email também
-   [ ] Agendamento para enviar mensagem depois
