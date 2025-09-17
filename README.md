# Brand Toplist CRUD API (Symfony 7)

This project is a Symfony 7 CRUD application for managing a toplist of brands.
It provides a RESTful API and a simple frontend with Twig.
Features:
  -CRUD operations for brands;
  -Geolocation-based toplist (using CF-IPCountry header);
  -Basic Authentication for admin-only routes;
  -Docker setup with PHP-FPM + MySQL;
Requirements:
  -PHP 8.3+
  -Composer;
  -Docker & Docker Compose;
  -Symfony CLI (optional, for local dev);

Setup Instructions:
1. Clone repository:
  git clone https://github.com/yourusername/brands-crud.git
  cd brands-crud
2. Configure environment:
   Copy .env file and set DB + admin credentials:
   cp .env .env.local
3. Start Docker containers:
   docker-compose up -d --build
4. Install dependencies:
   docker exec -it brands-crud composer install
5. Run database migrations:
   docker exec -it brands-crud php bin/console doctrine:migrations:migrate




