FROM php:8.2

RUN apt-get update \
    && apt-get install -y \
        curl \
        unzip \
        libzip-dev \
        libxml2-dev \
    \
    # PHP extensions
    && docker-php-ext-install zip xml \
    \
    # Install Node.js 22
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    \
    # Yarn
    && npm install -g yarn \
    \
    # Cleanup
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2.9 /usr/bin/composer /usr/bin/composer