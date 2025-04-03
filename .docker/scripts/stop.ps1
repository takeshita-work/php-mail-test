# コンテナを停止
docker-compose `
  --env-file=.env `
  -f .docker/docker-compose.yml `
  stop