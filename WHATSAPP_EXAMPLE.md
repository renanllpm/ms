# 📋 Exemplo de Mensagem WhatsApp Gerada

## Estrutura da Mensagem

A mensagem é gerada automaticamente quando o admin clica em "💬 Gerar WhatsApp" após encerrar a votação.

### Formato Final (como aparecerá no WhatsApp):

```
🎰 RESULTADO DA VOTAÇÃO - MEGA-SENA 🎰

📊 RESUMO DE PARTICIPANTES
Total: 15
✅ Pagos: 12
⏳ Pendente: 3

🔢 NÚMEROS QUE SERÃO JOGADOS
(Mais votados pela grupo)

12 ➜ 14 votos (93.3%)
25 ➜ 13 votos (86.7%)
38 ➜ 12 votos (80.0%)
07 ➜ 11 votos (73.3%)
42 ➜ 10 votos (66.7%)
55 ➜ 9 votos (60.0%)

🎯 JOGO FINAL: 12 - 25 - 38 - 07 - 42 - 55

💡 Este será o jogo que o grupo vai jogar na próxima Mega-Sena!

Boa sorte! 🍀
```

## Dados que são Compilados

### 1. Resumo de Participantes

-   **Total** - Todos que votaram (incluindo abstinências)
-   **Pagos** - Quantos marcaram como "paid" no admin
-   **Pendente** - Total - Pagos

### 2. Números mais Escolhidos

-   Seleciona os **6 números com mais votos**
-   Mostra quantas vezes foi votado
-   Calcula percentual: (votos / total de participantes) × 100
-   Ordena por frequência (decrescente)
-   Formata como: `NN ➜ X votos (Y%)`

### 3. Jogo Final

-   Agrupa os 6 números separados por " - "
-   Mantém em ordem de frequência
-   Destacado com emoji 🎯

## Fluxo de Geração

```
1. Admin clica "💬 Gerar WhatsApp"
   ↓
2. Livewire chama copyWhatsAppMessage()
   ↓
3. compileVotingData() coleta dados do banco
   - SELECT * FROM participants
   - Conta WHERE paid = 1 e paid = 0
   - Processa numbers[] de cada participante
   ↓
4. generateWhatsAppMessage() formata a mensagem
   - Calcula percentuais
   - Formata números com leading zeros (02d)
   - Monta string com quebras de linha
   ↓
5. showMessagePreview = true abre modal
   ↓
6. Admin visualiza a mensagem no modal
   ↓
7. Clica "✅ Copiar Mensagem"
   ↓
8. confirmCopyMessage() copia para clipboard
   ↓
9. Toast "✅ Mensagem copiada!" aparece
   ↓
10. Admin cola no WhatsApp
```

## Exemplo com Números Reais

Supondo que temos:

-   20 participantes total
-   3 que se abstiveram
-   17 que votaram efetivamente
-   18 pagos, 2 pendentes

```
Frequência de números:
- 15 votos por: 5 números (25%)
- 14 votos por: 3 números (17.5%)
- 13 votos por: 2 números (12.5%)
- 12 votos por: 4 números (20%)
- 11 votos por: 3 números (17.5%)
- 10 votos por: 2 números (12.5%)
- 9 votos por: 1 número (5%)
- 8 votos por: 1 número (5%)

Top 6:
1º: 31 com 15 votos (75%)
2º: 44 com 15 votos (75%)
3º: 07 com 15 votos (75%)
4º: 52 com 14 votos (70%)
5º: 23 com 14 votos (70%)
6º: 38 com 13 votos (65%)
```

Mensagem gerada:

```
🎰 RESULTADO DA VOTAÇÃO - MEGA-SENA 🎰

📊 RESUMO DE PARTICIPANTES
Total: 20
✅ Pagos: 18
⏳ Pendente: 2

🔢 NÚMEROS QUE SERÃO JOGADOS
(Mais votados pela grupo)

31 ➜ 15 votos (75.0%)
44 ➜ 15 votos (75.0%)
07 ➜ 15 votos (75.0%)
52 ➜ 14 votos (70.0%)
23 ➜ 14 votos (70.0%)
38 ➜ 13 votos (65.0%)

🎯 JOGO FINAL: 31 - 44 - 07 - 52 - 23 - 38

💡 Este será o jogo que o grupo vai jogar na próxima Mega-Sena!

Boa sorte! 🍀
```

## Pontos Importantes

✅ **Sempre 6 números** - Independente da configuração, sempre tira os 6 mais votados  
✅ **Percentual real** - Baseado no total de participantes (incluindo abstinências)  
✅ **Ordem de frequência** - Os números aparecem sempre em ordem decrescente  
✅ **Formatação WhatsApp** - Usa asteriscos para negrito (_texto_)  
✅ **Emojis expressivos** - Tornando a mensagem mais atraente  
✅ **Pronto para copiar** - Um clique copia para colar no WhatsApp

## Possíveis Casos de Uso

1. **Enviar para grupo do WhatsApp** - Admin copia e cola em tempo real
2. **Documentação** - Registro oficial do resultado
3. **Compartilhamento** - Pode repassar para outros admins confirmarem
4. **Arquivo** - Histórico das votações

## Validações

-   ✅ Não gera mensagem se votação está aberta
-   ✅ Botão só aparece quando votação está encerrada
-   ✅ Modal está sempre sincronizado com dados atualizados
-   ✅ Funcionando em modo preview sem precisar copiar realmente
