<div align="center">
    <img src="https://humblebrains.ru/favicon.png" alt="HumbleBrains Logo" style="width:75px; height: 75px" />
    <h1>HumbleBrains [backend]</h1>
</div>

## Overview
HumbleBrains [backend] is the API for the HumbleBrains application, built on Laravel 11. The application is designed to enhance cognitive abilities including attention, concentration, memory, and logical reasoning. It achieves this through personalized training plans that are generated based on periodic performance checkpoints.

## Project Structure
- **Application Code**: Follows standard Laravel project structure.
- **Environment Configuration**: Uses environment variables managed via the `.env` file.
- **Docker Configuration**:
    - `docker-compose.dev.yml`: For development environment setup.
    - `docker-compose.prod.yml`: For production environment setup.

## Key Concepts

### Anonymous and Non-anonymous Users
The application assumes the existence of two types of users.

**Non-anonymous** users are those who have gone through the registration process, whereas **anonymous** users are all client-side users who can be identified by their cookies.

This approach is used to provide client-side users with full functionality for exercises or checkpoints.

### Adding/Editing Games
Games contain key properties that determine its difficulty at different levels. Properties are presented in the configuration file `/config/properties/index.php`, to add new properties to the database, use the `php artisan app:import-properties` command.

Property values depending on the levels for a particular game are set in the `/config/games/[game].php` file. Basic properties of the game are also set there, such as name, description or category. To add a game to the database, use the `php artisan app:import-games` command.

In the future, it is planned to implement an admin panel to manage games.

## Development Environment Setup

### Prerequisites
- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

### Clone the Repository & Setup Environment Variables

Copy the `.env.example` file to `.env` and modify it according to your development needs.

```bash
git clone https://github.com/humblera1/humblebrains-backend.git
cp .env.example .env
```

### Start Docker Containers
Build and start the development containers using the development Docker Compose file:

```bash
docker compose -f docker-compose.dev.yml up --d
```

### Run Migrations
Execute Laravel migrations to set up your database schema:

```bash
docker compose -f docker-compose.dev.yml exec app php artisan migrate
```

### Initialize the Project

The following command will perform all the steps to initialize the project, populating the database with the necessary entries:

```bash
docker compose -f docker-compose.dev.yml exec app php artisan app:init
```

### Access the API
The API will be available at the configured host and port (typically `http://localhost:<port>`).

## Production Environment Setup

### Prerequisites
- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

Ensure your production server is equipped with Docker and Docker Compose. Copy and adjust the .env file for production settings:

```bash
cp .env.example .env
```

### Deploy Containers
Build and run the containers in detached mode using the production Docker Compose configuration:

```bash
docker-compose -f docker-compose.prod.yml up --build -d
```

### Run Migrations
Apply migrations in production. The --force flag ensures migrations run in the production environment:

```bash
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

### Initialize the Project

The following command will perform all the steps to initialize the project, populating the database with the necessary entries:

```bash
docker compose -f docker-compose.dev.yml exec app php artisan app:init
```

### Configure Web Server
If applicable, configure your web server (e.g., Nginx) to proxy requests to the appropriate container port.


## Deployment with GitHub Actions

This section outlines the CI/CD pipeline configured with GitHub Actions for automated production deployments.

The workflow is triggered on push events to tags that follow the semantic versioning pattern `v*.*.*`, and it performs the following stages: Testing, Building & Pushing, and Deployment.

### Testing Stage
Purpose: Validate your application by running an isolated test environment.

The environment is started in detached mode using `docker-compose.test.yml`, which spins up all necessary containers (such as the application, database, etc.) configured for testing.

The test stage clears the configuration cache, runs database migrations, initializes the application, and executes the Laravel test suite. Any failure terminates the workflow.

### Building & Pushing Stage
Purpose: Package the production-ready Docker images.

After successful tests, the workflow checks out the code again and ensures proper environment setup. Docker images are built and then pushed to Docker Hub using the production Docker Compose file (`docker-compose.prod.yml`).

### Deployment Stage
Purpose: Update the production server with the new version of application.

The pipeline deploys files to `/home/projects/humblebrains-backend/`.

### Secrets Required
- `DOCKER_USERNAME` & `DOCKER_PASSWORD`: Used for authenticating with Docker Hub.
- `TIMEWEB_HOST`: The hostname or IP address of production server.
- `TIMEWEB_USER`: The server username for SSH access.
- `SSH_PRIVATE_KEY`: The server username for SSH access.
