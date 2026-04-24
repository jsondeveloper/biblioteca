# Biblioteca Universitaria

## Requisitos

- XAMPP instalado en Windows
- Apache iniciado desde el panel de XAMPP
- MySQL iniciado desde el panel de XAMPP
- Proyecto ubicado en `C:\xampp\htdocs\biblioteca`

## Configuracion de la base de datos

1. Abre `http://localhost/phpmyadmin`.
2. Crea una base de datos nueva llamada `biblioteca` si aun no existe.
3. Entra a la base `biblioteca`.
4. Abre la pestaña `Importar`.
5. Selecciona el archivo [`database/schema.sql`](/c:/xampp/htdocs/biblioteca/database/schema.sql).
6. Ejecuta la importacion.

## Configuracion de conexion

El sistema usa esta configuracion por defecto en [`config/database.php`](/c:/xampp/htdocs/biblioteca/config/database.php):

- host: `localhost`
- dbname: `biblioteca`
- user: `root`
- password: vacia

Si tu MySQL usa otra clave, actualiza ese archivo antes de abrir el sistema.

## Como ejecutar en XAMPP

1. Inicia Apache y MySQL en XAMPP.
2. Asegurate de que la carpeta del proyecto este en `C:\xampp\htdocs\biblioteca`.
3. Abre una de estas URLs en el navegador:
   - `http://localhost/biblioteca/`
   - `http://localhost/biblioteca/public/`
4. Para iniciar sesion, usa una cuenta de prueba importada desde `schema.sql`.

## Credenciales de prueba

- Estudiante:
  - usuario: `estudiante01`
  - contrasena: `estudiante123`
- Bibliotecario:
  - usuario: `bibliotecario01`
  - contrasena: `biblioteca123`

## Rutas principales

- Inicio: `/` o `/public/`
- Login: `/login` o `/public/login`
- Registro: `/registro` o `/public/registro`
- Libros: `/libros` o `/public/libros`
- Prestamos: `/prestamos` o `/public/prestamos`
- Reservas: `/reservas` o `/public/reservas`

## Verificacion rapida

Si algo falla:

1. Verifica que Apache y MySQL esten iniciados.
2. Verifica que `biblioteca` exista en phpMyAdmin.
3. Verifica que importaste de nuevo `database/schema.sql`.
4. Verifica que `config/database.php` tenga las credenciales correctas.
5. Si cambiaste archivos y Apache tenia cache, reinicia Apache desde XAMPP.
