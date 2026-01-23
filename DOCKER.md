# Notas de Docker para MultiLab

## 1. Montaje y arranque del stack
1. `docker compose up -d --build`
2. `docker compose exec app composer install`

## 2. Inicialización del proyecto (máquina nueva)
1. Copiar `.env.example` a `.env` y actualizar valores clave:
   - `APP_KEY=` (generar luego con artisan)
   - `DB_HOST=mysql`, `DB_DATABASE=Multilabfesc`, `DB_USERNAME=root`, `DB_PASSWORD=` (ajustar según entorno)
   - `CACHE_DRIVER=file`, `SESSION_DRIVER=file`, `QUEUE_CONNECTION=sync`
2. `docker compose exec app php artisan key:generate`
3. `docker compose exec app php artisan migrate`
4. `docker compose exec app php artisan db:seed` (para roles, usuarios, materiales, etc.)

> Nota: el flujo evita `php artisan config:cache`, `route:cache` y `view:cache` durante el desarrollo para que los cambios en `.env` surjan efecto inmediatamente. Si se modifica `.env`, ejecutar `docker compose exec app php artisan optimize:clear` antes de reiniciar el contenedor.

## 3. Permisos
- Garantizar que `storage/` y `bootstrap/cache/` sean grabables por el usuario del contenedor (PHP corre como `${UID:-1000}:${GID:-1000}`).
- En entornos Windows (Laragon) puede ser necesario `docker compose exec app chown -R 1000:1000 storage bootstrap/cache` o adaptar las variables de UID/GID para evitar usar `chmod 777`.

## 4. Sesiones y cachés
- Se mantiene `SESSION_DRIVER=file` y `CACHE_DRIVER=file` dentro del contenedor para evitar valores obsoletos al no usar caching de configuración. Esto mantiene consistencia mientras se desarrolla.

## 5. Colas (opcionales)
- `QUEUE_CONNECTION=sync` ejecuta los jobs inmediatamente.  
- Si se cambia a `database`, ejecutar `docker compose exec app php artisan queue:table` + `php artisan migrate` y lanzar los workers manualmente con `docker compose exec app php artisan queue:work --tries=3`.  
- No iniciar workers en el comando `docker compose up` por defecto; solo arrancarlos cuando se requiere procesamiento asíncrono.
