sudo echo "cleaning..." &&
    docker compose down &&
    docker volume rm -f estatein-theme_mysql estatein-theme_wordpress &&
    sudo rm -rf ./estatein/{node_modules/,vendor/,composer.lock,yarn.lock}