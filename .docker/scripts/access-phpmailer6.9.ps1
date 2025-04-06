docker-compose `
  --env-file=.env `
  -f .docker/docker-compose.yml `
  exec apache-php-phpmailer6.9 /bin/bash