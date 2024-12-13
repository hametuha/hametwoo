# 1. .wp_install_path からハッシュ値を読み込む、なければ終了。
if [ ! -f .wp_install_path ]; then
  echo ".wp_install_path File not found. Skip installation mailhog"
  exit 0
fi
hash_value=$(cat .wp_install_path)

# 2. Replace %network% in compose.template.yaml with hash
sed "s/%NETWORK_NAME%/${hash_value}/g" compose.template.yaml > compose.yaml

# 3. Display status
echo "Hash: ${hash_value} in compose.yml"

docker compose up -d

echo "mailhog http://localhost:8025"
