# Contact Manager – Symfony 5.4 + Docker

Web application built with Symfony 5.4 LTS, Doctrine ORM, and Docker, allowing authenticated users to manage their contacts through a full CRUD (Create, Read, Update, Delete).
The UI is based on SB Admin 2 with DataTables for a modern and user-friendly experience.

### Project Structure

.
├── docker/
│   ├── nginx/
│   └── php/
├── public/
│   └── vendor/ (SB Admin 2, DataTables)
├── src/
│   ├── Controller/
│   ├── Entity/
│   ├── Form/
│   ├── Repository/
│   └── Service/
├── templates/
│   ├── contact/
│   ├── partials/
│   └── security/
├── docker-compose.yml
├── composer.json
└── README.md

### Prerequisites

Docker >= 20.x
Docker Compose >= 2.x
Git
You do not need PHP or Composer installed locally. Everything runs inside Docker.

### Setup from Scratch

1. Clone the repository
- `git clone https://github.com/daflores9096/contact-manager.git`
- `cd contact-manager`

2. Start the Docker environment
- `docker compose up -d --build`

This will start: PHP 7.4, Nginx, MySQL

3. Install PHP dependencies
- `docker compose exec php composer install`

4. Environment configuration
Example .env configuration:
`DATABASE_URL="mysql://app:app@database:3306/contact_manager?serverVersion=8.0"`

5. Create the database
- `docker compose exec php php bin/console doctrine:database:create`

6. Run database migrations
- `docker compose exec php php bin/console doctrine:migrations:migrate`

### User Creation

The application uses Symfony Security (form_login).
Users are created via a custom Symfony Command.

### Create a user

`docker compose exec php php bin/console app:create-user admin@test.com 123456`
This command:
Creates the user
Hashes the password
Persists the user using Doctrine ORM

### Accessing the Application

Open in your browser: http://localhost
You will be redirected to: /login

### Login using

- Email: admin@test.com
- Password: 123456