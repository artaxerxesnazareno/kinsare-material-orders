# Descrição
Desenvolver um software básico para a gestão de pedidos de materiais, com dois perfis de
usuários: Solicitante e Aprovador. Este desafio visa avaliar a capacidade do candidato em projetar,
desenvolver e implementar uma aplicação Laravel seguindo boas práticas de desenvolvimento e
padrões de projeto. Boa sorte!

# Instruções
Enviar link do projecto no github para antonio.pedro@kinsari.com
Requisitos Técnicos – Obrigatórios
• Back/front-end: PHP, Laravel, Blade, CSS, HTML
• Banco de Dados: A sua escolha.
• Autenticação: A sua escolha.
• Versionamento: Git.
Requisitos Técnicos – Diferenciais
- Adicionar testes unitários ou integrados.
- Usar Tailwaind CSS.
- Usar Livewire.
- Usar SQL Server.
- Fazer o uso correcto das Models, Controllers, Middlewares e Rotas.
Requisitos Funcionais
1. Cadastro de Pedidos:
 - O usuário solicitante pode criar um pedido e incluir diversos materiais no pedido.
 - O pedido é criado dentro do grupo do usuário solicitante.
2. Fluxo de Aprovação:
 - O pedido criado pelo solicitante é enviado para o usuário aprovador.
 - O aprovador pode realizar as seguintes ações ao pedido:
 - Aprovar: O pedido é aprovado se o saldo permitido do grupo for maior que o total do pedido.
 - Solicitar Alterações: O aprovador pode pedir modificações no pedido antes de aprová-lo.
- Rejeitar: O pedido é rejeitado.
3. Gestão de Saldo:
 - O sistema deve verificar o saldo permitido do grupo antes de aprovar o pedido.
 - Apenas pedidos com valor total, menor ou igual ao saldo permitido podem ser aprovados.
4. Estados do Pedido (Opcional):
 - Novo: Quando o pedido é criado pelo solicitante.
 - Em Revisão: Quando o pedido está sob análise do aprovador.
 - Alterações Solicitadas: Quando o aprovador solicita modificações no pedido.
 - Aprovado: Quando o pedido é aprovado.
 - Rejeitado: Quando o pedido é rejeitado.
Requisitos Não Funcionais
1. Segurança:
 - Apenas usuários autenticados podem aceder ao sistema.
 - A autorização deve garantir que somente solicitantes possam criar pedidos e somente
aprovadores possam aprovar, rejeitar ou solicitar alterações.
2. Usabilidade:
 - A interface do usuário deve ser intuitiva e fácil de usar. Use a sua criatividade.
