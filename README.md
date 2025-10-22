# Test task for Effective Mobile

This project implements a RESTful API for task management using Laravel 12. It features typical CRUD operations including task creation, retrieval, update, and deletion, secured with Sanctum authentication. The controller uses command and query buses for clean separation of concerns and returns structured JSON responses through API resources.

## Quick Start

### Prerequisites

- Docker & Docker Compose installed
- Bash (for cert script)
- Optional: g++ (if not available, an alternative method is provided)

### 1. Generate SSL Certificates

```
$ bash ./certgen.sh
```

### 2. Build and Start the Project

Use the provided `Makefile` to orchestrate containers:

```
$ make build
```

If you **don't have g++** installed, use these Docker commands manually:

```
$ docker network create app-network
$ docker compose \
    -f docker-compose.yml \
    -f vendor/docker-compose.nginx.yml \
    -f vendor/docker-compose.redis.yml \
    up --build -d --remove-orphans
```

### 3. Install Composer Dependencies

```
$ docker compose exec app composer install --prefer-dist --optimize-autoloader
```

### 4. Prepare Environment File

```
$ docker compose exec app cp .env.example .env
```

### 5. Generate App Key

```
$ docker compose exec app php artisan key:generate
```

### 6. Run Database Migrations and Seeders

```
$ docker compose exec app php artisan migrate:fresh --seed
```

### 7. Helper Commands

For help with available Makefile commands:

```
$ make help
```

## Useful Makefile Commands

| Command             | Description                                      |
| ------------------- | ------------------------------------------------ |
| make build          | Build and start containers                       |
| make start          | Start containers                                 |
| make stop           | Stop and remove containers and volumes           |
| make restart        | Restart all containers                           |

## License

This project is licensed under the [MIT License](https://opensource.org/licenses/MIT).
