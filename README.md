
# 🚀 MyLoc

MyLoc é uma aplicação web para gerenciamento de itens de menu e pedidos, construída com **Laravel**.

> ⚠️ **Nota:** Este projeto está em desenvolvimento.

---

## ✨ Funcionalidades

- 🔑 **Autenticação de Usuários:** Registro, login e logout com JWT.
- 🍔 **Gerenciamento de Itens de Menu:** CRUD de itens (restrito a administradores e estoquistas).
- 🛒 **Gerenciamento de Pedidos:** Criação, atualização, exclusão e pagamento de pedidos (usuários e administradores).
- 🛡 **Controle de Acesso:** Papéis de **usuário**, **administrador** e **estoquista**.

---

## 🛠 Pilha Tecnológica

- **Backend:** Laravel (PHP)
- **Banco de Dados:** MySQL (configurável via `.env`)

---

## 📂 Estrutura do Projeto

| Diretório / Arquivo | Descrição |
|--------------------|-----------|
| `.editorconfig` | Consistência de estilo entre editores |
| `.env.example` | Exemplo de configuração do ambiente |
| `.gitignore` | Arquivos ignorados pelo Git |
| `README.md` | Documentação do projeto |
| `app/` | Lógica principal |
| `app/Http/Controllers/` | Controllers: `ItemsController.php`, `LoginController.php`, `OrderController.php` |
| `app/Models/` | Modelos: `User.php`, `Menu_Item.php`, `Orders.php` |
| `routes/api.php` | Rotas da API |
| `database/migrations/` | Migrações: `users`, `menu_items`, `orders` |
| `composer.json` | Dependências PHP |
| `package.json` | Dependências frontend (Vite + Axios) |

---

## 📝 Rotas API

| Método | Endpoint | Controller | Middleware |
|---------|----------|------------|------------|
| POST | `/users/register` | `LoginController::RegisterUser` | - |
| POST | `/users/login` | `LoginController::LoginUser` | - |
| GET | `/users/me` | `LoginController::AuthUser` | `jwt.auth` |
| POST | `/users/exit` | `LoginController::Userlogout` | `jwt.auth` |
| POST | `/items/register` | `ItemsController::RegisterItems` | `jwt.auth`, `user.type:admin,stockist` |
| PUT | `/items/changed` | `ItemsController::UpdateItems` | `jwt.auth`, `user.type:admin,stockist` |
| DELETE | `/items/{name}` | `ItemsController::DeleteItems` | `jwt.auth`, `user.type:admin,stockist` |
| GET | `/items` | `ItemsController::GetItems` | `jwt.auth`, `user.type:user,admin` |
| POST | `/orders` | `OrderController::RegisterOrder` | `jwt.auth`, `user.type:user,admin` |
| PUT | `/orders/{id}` | `OrderController::UpdateQtdOrder` | `jwt.auth`, `user.type:user,admin` |
| GET | `/orders` | `OrderController::GetOrder` | `jwt.auth`, `user.type:user,admin` |
| DELETE | `/orders/{id}` | `OrderController::DeleteOrder` | `jwt.auth`, `user.type:user,admin` |
| POST | `/orders/customer` | `OrderController::PaymentOrder` | `jwt.auth`, `user.type:user,admin` |

---

## ⚙️ Como rodar o projeto

```bash
# Clone o repositório
git clone https://github.com/Kcarlos-dev/MyLoc.git
cd MyLoc

# Instale as dependências
composer install

# Copie e edite o .env
cp .env.example .env
```

No `.env` configure o banco:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=myloc
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

```bash
# Gere a chave
php artisan key:generate

# Rode as migrações
php artisan migrate

# Inicie o servidor
php artisan serve
```

---

## 💻 Como usar

- Acesse: [http://localhost:8000](http://localhost:8000)
- Interaja com os endpoints via API.
- Use o token JWT no header: `Authorization: Bearer <seu_token>`.

---

## 🤝 Contribua

Contribuições são bem-vindas! Abra uma issue ou um PR em  
👉 [https://github.com/Kcarlos-dev/MyLoc](https://github.com/Kcarlos-dev/MyLoc)
