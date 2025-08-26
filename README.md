# Viagens Corporativas – Laravel + Vue + Docker (MySQL)

## Como rodar
1) Tenha Docker e Docker Compose instalados.
2) Suba os serviços:
```bash
docker compose up -d --build
```
3) Rode migrações e seed (cria admin `admin@example.com` / `secret`):
```bash
docker exec -it travel_api php artisan migrate --force
docker exec -it travel_api php artisan db:seed --class=Database\\Seeders\\AdminUserSeeder --force
```
4) Acesse:
- API: http://localhost:8080/api
- Front-end (Vite dev): http://localhost:5173

## Login
- Email: `admin@example.com`
- Senha: `secret`

## Observações
- O Dockerfile do backend cria um projeto Laravel fresco no build, instala JWT e copia os arquivos de domínio (controllers, models, migrations, rotas, notifications, policy).
- O guard `api` usa `jwt`. Em produção, use HTTPS e configure variáveis de ambiente adequadas.
- Banco: MySQL (container `travel_db`, porta local 3307).

## Endpoints principais
- `POST /auth/login` { email, password }
- `GET /auth/me` (JWT)
- `GET /travel-requests` (filtros: status, destination, date_field=created|travel, from, to)
- `GET /travel-requests/{id}`
- `POST /travel-requests`
- `PATCH /travel-requests/{id}/status` (admin)

## Front-end
- Vue 3 + Pinia + Vue Router + Axios
- Defina `VITE_API_URL` em `frontend/.env` se necessário.

## Dicas
- Para ver emails no dev, `MAIL_MAILER=log` (default); as notificações são registradas no log.
- Se quiser trocar para Postgres, altere `db` no `docker-compose.yml` e `DB_CONNECTION`/porta no serviço `app`.
