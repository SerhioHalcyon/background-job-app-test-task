# Test Task Application

## Purpose

The purpose of this test task is to implement a service 
that integrates with an external geographical API (Nominatim) 
to retrieve polygons of Ukraine's regions. 
It also provides the ability to update and clear the polygon data 
for these regions in the database.

## Technologies

The project uses the following technologies:

- **PHP 8.3** with **Laravel 11.9** framework.
- **Docker** for containerizing the application.
- **MySQL** as the database.
- **Redis** for queues.
- **Nominatim API** for fetching geographical data (polygons).
- **Memcached** for caching data.

## How to Run

### 1. Clone the Repository

Clone the project repository:

```bash
git clone https://github.com/SerhioHalcyon/background-job-app-test-task
cd background-job-app-test-task
```
### 2. Set Up the Environment

Create the .env file by copying the .env.example:

```bash
cp .env.example .env
```

The parameters for accessing containers are already prescribed 
in the example file

### 3. Launch with Docker

The project uses Docker Compose to manage services. 
Use the following commands to build and start the containers:

```bash
docker-compose up -d --build
```

### 4. Install Dependencies

Once the containers are running, install Laravel dependencies:

```bash
docker-compose exec app composer install
```

### 5. Run Migrations and Seeders

To create the necessary database tables 
and seed them with state data:

```bash
docker-compose exec app php artisan migrate --seed
```

### 6. Queues and Cache

The project uses Laravel Queues for handling background jobs. 
Make sure to start the queue worker:

```bash
docker-compose exec app php artisan queue:work
```

## API

### 1. Update Region Polygon Data

This endpoint updates the polygons of all states
using the Nominatim API and saves the updated data in the database.

**Method:** `GET`

**URL:** `/data?delaySeconds=123`

**Description:**
- Starts the process of fetching and updating polygon data
  for all states of Ukraine.
- Data is fetched via Nominatim API with a 1-second delay
  between requests to comply with the API rate limits.

### 2. Clear Region Polygon Data

This endpoint clears the polygon data for each state
so that it can be refreshed without recreating records.

**Method:** `DELETE`

**URL:** `/data`

**Description:**
- Clears the `coordinates` column for all states in the database.
