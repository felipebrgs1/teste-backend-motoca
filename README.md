# Concessionária API

API REST desenvolvida em Laravel para gerenciamento de veículos e leads de uma concessionária.

## Tecnologias

- PHP 8.4
- Laravel 13
- PostgreSQL 16
- Docker & Docker Compose
- Nginx
- Laravel Sanctum (autenticação)
- Pest PHP (testes)
- Swagger (documentação L5-Swagger)

## Como rodar o projeto

### 1. Clonar e entrar no diretório

```bash
git clone <repo>
cd <repo>
```

### 2. Subir os containers

```bash
docker compose up -d --build
```

A aplicação ficará disponível em: `http://localhost:8000`

### 3. Rodar as migrations

```bash
docker compose exec app php artisan migrate
```

### 4. (Opcional) Rodar os seeders

```bash
docker compose exec app php artisan db:seed
```

Isso criará:
- 1 usuário de teste (`test@example.com` / `password`)
- 30 veículos
- 85 leads

### 5. Rodar os testes

```bash
php artisan test --compact
```

## Documentação (Swagger)

A documentação completa da API (OpenAPI 3.0) está disponível através do Swagger UI.

Para acessar a interface interativa:
- URL: `http://localhost:8000/api/documentation`

Para gerar/atualizar a documentação após alterações nas anotações:
```bash
docker compose exec app php artisan l5-swagger:generate
```

## Endpoints da API

### Autenticação

| Método | Endpoint | Descrição | Auth |
|--------|----------|-----------|------|
| POST | `/api/login` | Login e geração de token | Pública |

### Veículos

| Método | Endpoint | Descrição | Auth |
|--------|----------|-----------|------|
| GET | `/api/vehicles` | Listar veículos (com filtros) | Pública |
| GET | `/api/vehicles/{id}` | Detalhes do veículo | Pública |
| POST | `/api/vehicles` | Criar veículo | Sanctum |
| PUT | `/api/vehicles/{id}` | Atualizar veículo | Sanctum |
| DELETE | `/api/vehicles/{id}` | Remover veículo | Sanctum |

**Filtros disponíveis:**
- `?type=car` ou `?type=motorcycle`
- `?max_price=80000`

### Leads

| Método | Endpoint | Descrição | Auth |
|--------|----------|-----------|------|
| POST | `/api/leads` | Criar lead | Pública |
| GET | `/api/leads` | Listar leads | Sanctum |
| GET | `/api/vehicles/{id}/leads` | Leads por veículo | Sanctum |

### Dashboard

| Método | Endpoint | Descrição | Auth |
|--------|----------|-----------|------|
| GET | `/api/dashboard` | Estatísticas | Sanctum |

## Exemplos de uso

### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'
```

### Criar veículo (autenticado)
```bash
curl -X POST http://localhost:8000/api/vehicles \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <TOKEN>" \
  -d '{"type":"car","model":"Civic","year":2024,"price":150000,"color":"Prata","mileage":0}'
```

### Criar lead (público)
```bash
curl -X POST http://localhost:8000/api/leads \
  -H "Content-Type: application/json" \
  -d '{"name":"João Silva","email":"joao@teste.com","phone":"(11) 99999-9999","vehicle_id":1}'
```

## Estrutura Docker

- `app` → PHP-FPM + Laravel
- `nginx` → Servidor web (porta 8000)
- `postgres` → Banco de dados PostgreSQL
