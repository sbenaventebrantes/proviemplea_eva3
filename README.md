# ProviEmplea EVA 3

Team:

- Andrea Carreño
- Mixiu Perez
- Sofia Benavente

This project is a basic backend for ProviEmplea, built for the U3 evaluation. The idea was to keep it simple, clear, and easy to review, with Swagger documentation and a MySQL database running with Docker.

## Stack

- PHP
- Laravel
- MySQL
- Docker

## Requirements

- Docker Desktop
- Git

## How to run

1. Clone the repository.
2. Open a terminal in the project folder.
3. Start the containers:

```bash
docker compose up -d --build
```

4. Run migrations using the credentials already defined in `docker-compose.yaml`:

```bash
docker compose exec app php artisan migrate
```

Database values inside the container:

- DB_DATABASE: `proviemplea`
- DB_USERNAME: `proviuser`
- DB_PASSWORD: `provipass`
- DB_HOST: `db`

## Swagger UI

Open this URL in your browser:

```txt
http://localhost:8080/api/documentation
```

You should see the `ProviEmplea API` documentation there.

The OpenAPI file is in the project root:

```txt
swagger.yaml
```

## Endpoints

| Method | Endpoint | Description |
| --- | --- | --- |
| GET | `/api/health` | Check API status |
| GET | `/api/persons` | List persons |
| POST | `/api/persons` | Create a person |
| GET | `/api/persons/{id}` | Show one person |
| PATCH | `/api/persons/{id}` | Update a person |
| DELETE | `/api/persons/{id}` | Delete a person |
| GET | `/api/companies` | List companies |
| POST | `/api/companies` | Create a company |
| GET | `/api/companies/{id}` | Show one company |
| PATCH | `/api/companies/{id}` | Update a company |
| DELETE | `/api/companies/{id}` | Delete a company |
| GET | `/api/admin/contacts` | List contacts |
| POST | `/api/admin/contacts` | Create a contact |
| PATCH | `/api/admin/contacts/{id}/status` | Update contact status |
| GET | `/api/admin/statistics` | General statistics |

## What was developed

- A basic Laravel API for persons, companies, and contacts.
- Simple migrations to store data in MySQL.
- Swagger UI to review the documentation in the browser.
- An OpenAPI `swagger.yaml` file with examples and responses.
- Docker so the project can run without extra local setup.

## ZIP file

```bash
zip -r Eval_U3A_proviemplea_eva3.zip . -x "vendor/*" ".git/*" ".env" "storage/logs/*"
```
