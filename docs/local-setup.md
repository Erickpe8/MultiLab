# Local setup (Laragon / Windows)

## Requisitos previos
Se requiere PHP >= 8.1, Composer, Node.js >= 16, MySQL y acceso a Git. En entornos Windows se recomienda utilizar Laragon para administrar Apache/Nginx y MySQL desde su bandeja de sistema y cambiar la versión de PHP si es necesario.

## Pasos de instalación
1. Clonar el repositorio y ponerlo en el directorio de trabajo deseado.  
2. Ejecutar `composer install` y `npm install` desde la raíz del proyecto para instalar dependencias PHP y JavaScript.  
3. Copiar `.env.example` a `.env` y verificar valores como `APP_URL=http://127.0.0.1:8000`, `DB_HOST=127.0.0.1`, `DB_DATABASE=multilab`, `DB_USERNAME=root` y `DB_PASSWORD=` (Laragon usa root sin contraseña); ajustar las variables de correo, claves y servicios externos según el entorno.  
4. Generar la clave de aplicación con `php artisan key:generate --ansi`.  
5. Crear la base de datos `multilab` desde Laragon > Database > phpMyAdmin o con `mysql -u root` y asegurarse de que el servicio esté activo.  
6. Ejecutar `php artisan migrate --seed` para aplicar migraciones y poblar datos de prueba.  
7. Levantar el servidor con `php artisan serve --host=127.0.0.1 --port=8000` o configurar Laragon para que el virtual host apunte a `public/`.

## Tips operativos en Laragon
Mantener Apache/Nginx y MySQL activos, usar el menú Database > phpMyAdmin para crear la base `multilab` sin comandos adicionales y garantizar que `storage/` y `bootstrap/cache/` existan (Windows no requiere `chmod`). Cambiar la versión de PHP desde la interfaz de Laragon si el proyecto requiere otra distribución.

## Verificaciones y mantenimiento
Durante el desarrollo conviene ejecutar `npm run dev` para compilar activos y mantener MySQL activo cuando se migra o prueba, además de usar `php artisan migrate:fresh --seed` cuando se necesita resetear datos de prueba. Para validar la instalación, navegar a `http://127.0.0.1:8000` o al dominio definido en el virtual host.
