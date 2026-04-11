Aqui está um **README completo** pronto para colocar no repositório do teste 👇

---

# 🧪 Teste Técnico — Backend Laravel (Concessionária)

Olá! 👋
Este é o teste técnico para a vaga de **Desenvolvedor Backend Júnior**.

O objetivo é avaliar organização de código, conhecimento em API REST, Laravel, PostgreSQL e documentação de APIs com Postman.

---

# 🚗 Desafio

Desenvolver uma **API REST** para gerenciamento de:

* Veículos disponíveis para venda
* Leads (clientes interessados nos veículos)

Cenário: sistema interno de uma concessionária.

---

# ⚙️ Tecnologias obrigatórias

* PHP 8+
* Laravel
* PostgreSQL
* Git
* Postman (coleção exportada obrigatória)

---

# 📦 Entidades do sistema

## Vehicles

Representa os veículos disponíveis para venda.

Campos esperados:

* id
* type → `car` ou `motorcycle`
* brand → default "Honda"
* model
* year
* price
* color
* mileage
* created_at / updated_at

---

## Leads

Representa clientes interessados em veículos.

Campos esperados:

* id
* name
* email
* phone
* vehicle_id
* message (opcional)
* created_at / updated_at

Relacionamento:

* Um veículo possui vários leads.

---

# 🔐 Autenticação

Criar autenticação simples (Sanctum ou JWT).

Rota obrigatória:

```
POST /api/login
```

Somente usuários autenticados podem:

* Criar, editar e remover veículos
* Listar leads

A criação de lead deve ser pública.

---

# 🚀 Funcionalidades obrigatórias

## CRUD de Vehicles (autenticado)

```
GET    /api/vehicles
POST   /api/vehicles
GET    /api/vehicles/{id}
PUT    /api/vehicles/{id}
DELETE /api/vehicles/{id}
```

### Regras de validação

* model obrigatório
* year ≥ 2000
* price > 0
* type deve ser car ou motorcycle

---

## Leads

### Criar lead (público)

```
POST /api/leads
```

### Listar leads (autenticado)

```
GET /api/leads
GET /api/vehicles/{id}/leads
```

### Regras

* email válido
* telefone obrigatório
* vehicle_id deve existir

---

# ⭐ Diferenciais (opcional – vale pontos)

Implemente 1 ou mais:

### Filtros de veículos

```
GET /api/vehicles?type=car
GET /api/vehicles?max_price=80000
```

### Endpoint de dashboard

```
GET /api/dashboard
```

Exemplo de resposta:

```json
{
  "total_vehicles": 30,
  "total_leads": 85,
  "most_requested_vehicle": "Honda Civic"
}
```

### Outros diferenciais

* Seeders
* Paginação
* Testes automatizados
* Docker

---

# 📮 Entrega do teste

## 1️⃣ Faça um FORK deste repositório

Clique em **Fork** e desenvolva o projeto no seu repositório.

---

## 2️⃣ Crie uma branch

```
git checkout -b feature/teste-backend
```

---

## 3️⃣ Abra um Pull Request

Ao finalizar, abra um PR para este repositório contendo:

Informar na descrição do PR:

* Nome completo
* E-mail
* Tempo gasto para concluir
* Observações (opcional)

---

# 📬 Entregáveis obrigatórios

Seu repositório deve conter:

### ✔ Projeto Laravel funcionando

Com `.env.example` configurado para PostgreSQL.

### ✔ README com:

* Passo a passo de instalação
* Como configurar banco PostgreSQL
* Como rodar migrations
* Como rodar o projeto

### ✔ Coleção Postman exportada

Arquivo `.json` contendo:

* Login
* CRUD Vehicles
* Criar Lead
* Listar Leads
* Dashboard (se implementado)

Boa sorte! 🚀

