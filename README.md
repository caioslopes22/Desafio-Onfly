
# 🚀 Viagens Corporativas – Laravel + Vue + Docker (MySQL)

Aplicação fullstack para gerenciamento de solicitações de viagens corporativas, construída com **Laravel (API)**, **Vue 3 (Front-end)** e **MySQL** dentro de containers **Docker**.

---

## 📋 Pré-requisitos

- [Docker](https://www.docker.com/get-started/) instalado
- [Docker Compose](https://docs.docker.com/compose/install/) instalado

---

## ▶️ Subindo o ambiente

1. Clone o projeto e entre na pasta:

   ```bash
   git clone https://github.com/SEU_REPOSITORIO/travel-app.git
   cd travel-app
   ```

2. Suba os containers (API, banco de dados, Nginx e front-end):

   ```bash
   docker compose up -d --build
   ```

3. Execute as migrações e seeds (cria o usuário admin padrão):

   ```bash
   docker exec -it travel_api php artisan migrate --force
   docker exec -it travel_api php artisan db:seed --class=Database\Seeders\AdminUserSeeder --force
   ```

---

## 🌐 Acesso às aplicações

- **API (Laravel):**  
  👉 [http://localhost:8080/api](http://localhost:8080/api)

- **Front-end (Vue 3 + Vite):**  
  👉 [http://localhost:5173](http://localhost:5173)

---

## 🔑 Login padrão

- **Email:** `admin@example.com`  
- **Senha:** `secret`

Caso precise redefinir a senha do admin:

```bash
docker exec -it travel_api php artisan tinker
```

E dentro do Tinker:

```php
$user = \App\Models\User::where('email', 'admin@example.com')->first();
$user->password = bcrypt('nova_senha');
$user->save();
```

Agora você pode logar com `nova_senha`.

---

## ⚙️ Configuração do Front-end

1. Copie o arquivo `.env.example` para `.env` dentro da pasta `frontend`:

   ```bash
   cp frontend/.env.example frontend/.env
   ```

2. Edite a variável **VITE_API_URL** para apontar para a API no Nginx:

   ```env
   VITE_API_URL=http://localhost:8080/api
   ```

---

## 🛠️ Comandos úteis

- Ver logs dos containers:
  ```bash
  docker compose logs -f
  ```

- Acessar o container da API:
  ```bash
  docker exec -it travel_api sh
  ```

- Listar rotas disponíveis da API:
  ```bash
  docker compose exec app php artisan route:list
  ```

- Derrubar containers:
  ```bash
  docker compose down
  ```

---

## 📚 Estrutura do projeto

```
.
├── backend/laravel      # API em Laravel
│   ├── app/Http/...
│   ├── database/seeders
│   ├── routes/api.php
│   └── ...
├── frontend             # Front-end Vue 3 + Vite
│   ├── src/
│   ├── public/
│   └── ...
├── docker-compose.yml   # Orquestração com Docker
└── backend/docker       # Configuração do Nginx
```

---

## ✅ Checklist para funcionar

- [x] Docker e Docker Compose instalados  
- [x] Containers sobem com `docker compose up -d --build`  
- [x] Migrações e seed rodados  
- [x] API disponível em [http://localhost:8080/api](http://localhost:8080/api)  
- [x] Front-end disponível em [http://localhost:5173](http://localhost:5173)  
- [x] Login funciona com admin padrão  


