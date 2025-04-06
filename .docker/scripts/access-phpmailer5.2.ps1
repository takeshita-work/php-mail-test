docker-compose `
  --env-file=.env `
  -f .docker/docker-compose.yml `
  exec apache-php-phpmailer5.2 /bin/bash