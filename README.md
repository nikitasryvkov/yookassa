$(app-npm) = npm-crm (взял из файла docker-compose.yml)
npm-install:
docker-compose run --rm --service-ports $(app-npm) install
npm-update:
docker-compose run --rm --service-ports $(app-npm) update
npm-build:
docker-compose run --rm --service-ports $(app-npm) run build
npm-host:
docker-compose run --rm --service-ports $(app-npm) run dev --host
