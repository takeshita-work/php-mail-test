docker-compose `
  --env-file=.env `
  -f .docker/docker-compose.yml `
  exec apache-php /bin/bash