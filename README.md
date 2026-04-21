# Concesionaria PHP App

Aplicación web básica de gestión de concesionaria creada en PHP con soporte para autos, usuarios y autenticación.

## Descripción

La app permite gestionar autos y usuarios a través de interfaces web. Incluye:

- Inicio de sesión seguro con roles `admin` y `empleado`
- Gestión de autos: listado, búsqueda, paginación, creación y edición
- Gestión de usuarios: listado, búsqueda, paginación y edición
- Autorización por rol para proteger rutas administrativas

## Requisitos

- PHP 7.4+ con PDO habilitado
- MySQL / MariaDB
- XAMPP o servidor local similar
- Navegador web

## Estructura del proyecto

```
src/
  classes/
    Auto.php          # Lógica CRUD de autos
    Usuario.php       # Lógica de usuarios, login y permisos
    Administrador.php # Registro de nuevo usuario por admin
    Empleado.php      # Clase de usuario empleado
    Marca.php         # Modelo de marcas
    Autenticable.php  # Interface de autenticación
    Gestionable.php   # Interface CRUD
  database/
    DB.php            # Conexión PDO a MySQL
  middleware/
    Autorizacion.php # Control de acceso por rol
  public/
    styles/
      login.css       # Estilos para la pantalla de login
    views/
      login.php       # Formulario de inicio de sesión
      index.php       # Listado y búsqueda de autos
      crearAuto.php   # Formulario de registro de auto
      autoEdit.php    # Edición de auto
      listaUsuarios.php # Listado de usuarios
      crearUsuario.php # Formulario de registro de usuario
      usuarioEdit.php   # Edición de usuario
      includes/
        header.php    # Menú de navegación y logout
sql/
  seed_autos.sql     # Seed de autos
```

## Configuración de la base de datos

1. Crea la base de datos `concesionaria` en MySQL.
2. Importa el archivo `concesionaria.sql` si está disponible o crea las tablas manualmente.
3. Asegúrate de que `src/database/DB.php` tenga los datos correctos:

```php
private static $host = "localhost";
private static $db_name = "concesionaria";
private static $username = "root";
private static $password = "";
```

4. Si quieres cargar autos de ejemplo, importa `src/sql/seed_autos.sql`.

## Rutas principales y pantallas

| Página | Ruta | Función |
|---|---|---|
| Login | `src/public/views/login.php` | Autentica usuario y redirige a listado de autos |
| Listado de autos | `src/public/views/index.php` | Muestra autos, búsqueda y paginación |
| Crear auto | `src/public/views/crearAuto.php` | Formulario de registro de auto (admin) |
| Editar auto | `src/public/views/autoEdit.php` | Edita un auto existente |
| Listado de usuarios | `src/public/views/listaUsuarios.php` | Muestra usuarios con búsqueda y paginación |
| Crear usuario | `src/public/views/crearUsuario.php` | Formulario de registro de usuario (admin) |
| Editar usuario | `src/public/views/usuarioEdit.php` | Edita un usuario existente |

## Funcionalidades principales

### Autenticación

- `Usuario::login()` valida email y contraseña
- `Usuario::logout()` destruye la sesión
- `Autorizacion::permisosUsuario()` redirige a login si no hay sesión activa
- `Autorizacion::permisosAdmin()` restringe acceso a roles distintos de `admin`

### Gestión de autos

- `Auto::crear()` inserta un auto en la tabla `autos`
- `Auto::buscarTodos($search, $limit, $offset)` lista autos con filtro y paginación
- `Auto::contarAutos($search)` cuenta autos para calcular páginas
- `Auto::actualizar()` actualiza marca, modelo y año

### Gestión de usuarios

- `Usuario::buscarTodos($search, $limit, $offset)` lista usuarios con filtro y paginación
- `Usuario::contarUsuarios($search)` cuenta usuarios
- `Usuario::actualizar()` actualiza los datos del usuario

## Uso

1. Inicia el servidor local (XAMPP Apache y MySQL).
2. Accede a la app desde el navegador, por ejemplo:

```
http://localhost/tp1/src/public/views/login.php
```

3. Inicia sesión con un usuario existente.
4. Si el usuario es `admin`, verá opciones para listar y crear usuarios/autos.

## Consideraciones y mejoras futuras

- Las contraseñas se guardan en texto plano; se recomienda usar hashing (`password_hash`).
- La validación del lado servidor es básica; conviene agregar validación adicional.
- El flujo de eliminación de usuarios/ autos está preparado pero no expuesto en algunas pantallas.
- Puede mejorarse la experiencia con mensajes flash y validación de formulario más robusta.

## Notas de desarrollo

- `src/public/views/includes/header.php` controla la navegación y el botón de logout.
- `src/classes/Marca.php` contiene la lista de marcas disponibles.
- `src/public/views/crearAuto.php` y `crearUsuario.php` usan controles admin-only.
- `src/public/views/index.php` y `listaUsuarios.php` ya soportan búsqueda y paginación.
