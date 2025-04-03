# 稼働中のすべてのコンテナを停止
docker stop $(docker ps -q)

# 起動
docker-compose `
  --env-file=.env `
  -f .docker/docker-compose.yml `
  up -d