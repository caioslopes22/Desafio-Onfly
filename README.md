# Viagens Corporativas – Laravel + Vue + Docker (MySQL) 
# tenha o docker e o docker compose (https://www.docker.com/get-started/)
1) Suba os serviços:

docker compose up -d --build

2) Rode migrações e seed (cria admin `admin@example.com` / `secret`):

docker exec -it travel_api php artisan migrate --force
docker exec -it travel_api php artisan db:seed --class=Database\\Seeders\\AdminUserSeeder --force

3) Acesse:
- API: http://localhost:8080/api
- Front-end (Vite dev): http://localhost:5173

## Login para acesso
- Email: `admin@example.com`
- Senha: `secret`

## Front-end
- Defina `VITE_API_URL` em `frontend/.env`.

