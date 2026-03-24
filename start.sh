docker build -t toolx:1.0 . \
    && docker run --rm \
        -v ./estatein:/app \
        -w /app \
        toolx:1.0 composer install

if [ "$1" != "--dev" ]; then
    docker run --rm \
        -v ./estatein:/app \
        -w /app \
        toolx:1.0 bash -c "yarn install && yarn build"
fi

docker compose up -d --wait

docker compose exec -T wordpress bash -c "\
    curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp"

docker compose exec -T wordpress bash -c "\
    wp core install \
        --url=localhost:8080 \
        --title=Estatein \
        --admin_user=admin \
        --admin_password=admin \
        --admin_email=admin@example.com \
        --allow-root"

docker compose exec -T wordpress bash -c "wp theme activate twentytwentyfour --allow-root"
docker compose exec -T wordpress bash -c "wp theme activate estatein --allow-root"

if [ "$1" = "--dev" ]; then
    cd estatein/
    yarn install && yarn dev
    cd ..
fi
