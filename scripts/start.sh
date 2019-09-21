echo "[1/7] Building docker containers..."
cp .env.dist .env
docker-compose up -d --build
echo "[2/7] Installing composer dependencies..."
docker exec user-management-php-fpm composer install
echo "[3/7] Installing frontend dependencies..."
docker exec user-management-php-fpm yarn install
echo "[4/7] Building frontend assets..."
docker exec user-management-php-fpm yarn encore dev
echo "[5/7] Creating database..."
docker exec user-management-php-fpm bin/console d:d:c
echo "[6/7] Loading database migrations..."
docker exec user-management-php-fpm bin/console d:m:m -n
echo "[7/7] Loading database fixtures..."
docker exec user-management-php-fpm bin/console d:f:l -n
echo "All steps completed"
echo "App is running at localhost:8000"
