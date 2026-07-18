FROM php:8.4-cli

RUN apt-get update \
    && apt-get install -y git unzip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Dev dependencies included: config/scribe.php references Scribe classes,
# which Laravel loads at boot — a --no-dev install would crash the app.
RUN composer install --optimize-autoloader --no-interaction

# SQLite database file (recreated and reseeded on every container start)
RUN touch database/database.sqlite

EXPOSE 8080

CMD ["sh", "docker/start.sh"]
