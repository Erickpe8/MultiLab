# LOCAL_SETUP.md

## Flujo recomendado para ejecutar MultiLab en localhost

1. Ejecuta `composer install` y `npm install` desde la raiz del proyecto.
2. Copia `.env.example` a `.env`. El archivo ya carga `APP_URL=http://127.0.0.1:8000`, `DB_HOST=127.0.0.1`, `DB_DATABASE=multilab`, `DB_USERNAME=root` y `DB_PASSWORD=` (Laragon usa root sin contrasena). Ajusta las variables de correo y claves si necesitas otros servicios.
3. Genera la clave de aplicacion: `php artisan key:generate --ansi`.
4. Crea la base de datos `multilab` en MySQL local (Laragon > Database > phpMyAdmin o `mysql -u root`). Asegura que el servicio MySQL este activo y que el usuario y password coincidan con `.env`, o actualiza el archivo antes de migrar.
5. Ejecuta `php artisan migrate --seed` para aplicar migraciones y datos iniciales.
6. Arranca la aplicacion con `php artisan serve --host=127.0.0.1 --port=8000` o configura Laragon para que el host apunte al directorio `public/`.

## Tips de Laragon

- Abre Laragon y activa Apache/Nginx + MySQL; el icono en la bandeja indica los servicios activos.
- Cambia la version de PHP desde el menu de Laragon si necesitas otra distribucion para este proyecto.
- Usa el menu Database > phpMyAdmin para crear la base `multilab` sin escribir comandos adicionales.
- Configura el documento raiz (DocumentRoot) o el virtual host en Laragon para que sirva `public/` y no otro directorio.
- Si el directorio `storage/` o `bootstrap/cache/` falta, crealo manualmente; Windows no requiere `chmod`, basta con que el usuario actual tenga permisos de escritura.

## Verificaciones adicionales

- Si el proyecto se sirve con Laragon, apunta un navegador a `http://127.0.0.1:8000` o al dominio que definas en el virtual host.
- Para trabajar con activos y estilos, puedes ejecutar `npm run dev` mientras desarrollas.
- Mantén el servicio MySQL activo mientras migras o pruebas, y usa `php artisan migrate:fresh --seed` si necesitas resetear datos.
