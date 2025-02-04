# Sistema de Pedidos de Materiais

Sistema para gerenciamento de pedidos de materiais desenvolvido com Laravel 11 e Livewire 3.

## 🚀 Tecnologias

- PHP 8.2
- Laravel 11
- Livewire 3
- Laravel Breeze
- TailwindCSS
- MySQL/MariaDB
- Pest PHP (Testes)

## 📋 Pré-requisitos

- PHP >= 8.2
- Composer
- Node.js
- NPM ou Yarn
- MySQL/MariaDB

## 🔧 Instalação

1. Clone o repositório
```bash
git clone [url-do-repositorio]
```

2. Instale as dependências do PHP
```bash
composer install
```

3. Instale as dependências do Node.js
```bash
npm install
```

4. Copie o arquivo de ambiente
```bash
cp .env.example .env
```

5. Gere a chave da aplicação
```bash
php artisan key:generate
```

6. Configure o banco de dados no arquivo .env
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kinsare_material_orders
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

7. Execute as migrações e seeders
```bash
php artisan migrate --seed
```

8. Inicie o servidor de desenvolvimento
```bash
# Em um terminal
php artisan serve

# Em outro terminal
npm run dev
```

## 👥 Usuários do Sistema

O sistema possui três tipos de perfis:

### 1. Administrador
- Email: admin@example.com
- Senha: password
- Permissões: Gerenciar usuários e todas as funcionalidades do sistema

### 2. Aprovadores
- João Aprovador
  - Email: joao.aprovador@example.com
  - Senha: password
  
- Maria Aprovadora
  - Email: maria.aprovadora@example.com
  - Senha: password
  
- Permissões: Aprovar/rejeitar pedidos dos departamentos sob sua responsabilidade

### 3. Solicitantes
- Pedro Solicitante
  - Email: pedro.solicitante@example.com
  - Senha: password
  
- Ana Solicitante
  - Email: ana.solicitante@example.com
  - Senha: password
  
- Carlos Solicitante
  - Email: carlos.solicitante@example.com
  - Senha: password
  
- Permissões: Criar e gerenciar pedidos de materiais

## 🏢 Departamentos

O sistema possui os seguintes departamentos com seus respectivos limites de saldo:

- Departamento de TI (R$ 5.000,00)
- Departamento Financeiro (R$ 3.000,00)
- Departamento de RH (R$ 2.000,00)
- Departamento Comercial (R$ 4.000,00)

## 🧪 Testes

Para executar os testes:

```bash
php artisan test
# ou
./vendor/bin/pest
```

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.
