# Student Management System

A Laravel 13 application being developed as an academic student management project.
Authentication and a basic dashboard are present. Student and department CRUD,
search, and the student-department relationship are not yet fully implemented.

## Technology stack

- PHP 8.3
- Laravel 13
- MySQL (or SQLite for a local development setup)
- Laravel Blade
- Vite, Tailwind CSS, and Bootstrap currently used by existing pages

## Requirements

- PHP 8.3 or later with the required Laravel PHP extensions
- Composer
- Node.js and npm for frontend asset builds
- MySQL, or SQLite if configured for local development

## Installation

1. Install PHP dependencies:

   ```bash
   composer install
   ```

2. Create the local environment file and application key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   On Windows Command Prompt, copy `.env.example` to `.env` using `copy`.

3. Configure `.env` for the database you intend to use. For MySQL, set
   `DB_CONNECTION=mysql` and provide the host, port, database name, username,
   and password for a database you control. Never commit `.env` or credentials.

4. Install frontend dependencies and build assets:

   ```bash
   npm install
   npm run build
   ```

## Database setup

Review the existing migration files and back up any database containing data
before applying migrations. The historical migrations include tables for
students, departments, employees, categories, and products. Removing unrelated
application code does not remove those tables or records.

When you are ready to initialize or update a development database, use:

```bash
php artisan migrate
```

Do not use this command against a database containing important data without
reviewing the migrations and making a verified backup. No migrations are run as
part of this README's setup instructions.

## Run the application

Start Laravel's development server:

```bash
php artisan serve
```

For frontend development with Vite, run this in another terminal:

```bash
npm run dev
```

Visit the URL printed by `php artisan serve`. Login and registration are
available; the dashboard links to student and department management routes.
CRUD actions, search, and department selection are still under development.

## Suggested GitHub repository

`student-management-system`
