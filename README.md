# Project Management System Backend

This is a Laravel backend for managing projects, tasks, comments, and user roles.

## Requirements

- PHP >= 8.1 (8.2 recommended)
- Composer
- SQLite / MySQL / PostgreSQL (for database)
- Node.js & npm (optional if using frontend scaffolding)
- Git

---

## Installation

1. **Clone the repository**

```bash
git clone https://github.com/EiEiLwinDev/project-management-system-backend.git
cd project-management-system-backend 
```

2. **Composer install**
```bash
composer install
```

3. **Copy the environment file**
```bash
cp .env.example .env
```

4. **Generate application key**
```bash
php artisan key:generate
```
---

## Database Setup
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=project_management_system
DB_USERNAME=username
DB_PASSWORD=password

## Database Migration 
php artisan migrate --seed

## Run locally
php artisan serve

## Running Tests

1. **Create testing database file**
```bash
touch database/database.sqlite
```

2. **Create .env.testing file
```bash
cp .env.example .env.testing
```

3. **testing database set up**
```bash
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/your/project/database/database.sqlite
```

4. **testing database migration**
```bash
php artisan migrate --seed
```

5. **Run all tests**
```bash
php artisan test
```

6. **Run specific test**
```bash
php artisan test --filter=RegisterTest
```

## Caching

1. **Clear Cache**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## Mail Configuration (for notifications)

1. get app password from mail setting

2. set up env variables
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Project Management System"
```

## API Endpoints

```bash
POST            api/v1/auth/login
POST            api/v1/auth/logout
POST            api/v1/auth/register 
GET             api/v1/me 
GET             api/v1/project/tasks/{projectId}
GET             api/v1/projects 
POST            api/v1/projects 
GET             api/v1/projects/{project}
PUT             api/v1/projects/{project}
DELETE          api/v1/projects/{project}
POST            api/v1/tasks 
POST            api/v1/tasks/comments
GET             api/v1/tasks/comments/{taskId}
DELETE          api/v1/tasks/{id}
PATCH           api/v1/tasks/{id}
GET             api/v1/tasks/{id}
PUT             api/v1/tasks/{id}
```