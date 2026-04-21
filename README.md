
# Demo
https://github.com/user-attachments/assets/e51a1cc3-3fbf-472a-a3eb-cc0e2fc14000

# Concesionaria PHP App

Aplicación web básica de gestión de concesionaria creada en PHP con soporte para autos, usuarios y autenticación.

# Usuarios de Prueba

- Usuario Admin
    - Email: matias@gmail.com
- Usuario Empleado
    - Email: pedro@gmail.com
- Contraseña para ambos: 12345

## Descripción

La app permite gestionar autos y usuarios a través de interfaces web. Incluye:

- Inicio de sesión seguro con roles `admin` y `empleado`
- Gestión de autos: listado, búsqueda, paginación, creación y edición
- Gestión de usuarios: listado, búsqueda, paginación y edición
- Autorización por rol para proteger rutas administrativas

## Tecnologías utilizadas

- PHP 7.4+ con PDO
- MySQL / MariaDB
- HTML, CSS
- XAMPP (para desarrollo local)

## Requisitos

- PHP 7.4+ con PDO habilitado
- MySQL / MariaDB
- XAMPP o servidor local similar
- Navegador web

## Instalación y configuración

1. Clona o descarga el proyecto en el directorio `htdocs` de XAMPP (por ejemplo, `C:\xampp\htdocs\tp1`).

2. Inicia XAMPP y asegúrate de que Apache y MySQL estén ejecutándose.

3. Configura la base de datos:
   - Abre phpMyAdmin (http://localhost/phpmyadmin).
   - Crea una base de datos llamada `concesionaria`.
   - Importa el archivo `src/sql/concesionaria.sql` para crear las tablas.
   - Opcionalmente, importa `src/sql/seed_autos.sql` para cargar datos de ejemplo de autos.

4. Verifica la configuración de la base de datos en `src/database/DB.php`:
   ```php
   private static $host = "localhost";
   private static $db_name = "concesionaria";
   private static $username = "root";
   private static $password = "";
   ```
   Ajusta los valores según tu configuración de MySQL.

## Estructura del proyecto

```
src/
  classes/
    Administrador.php     # Registro de nuevo usuario por admin
    Autenticable.php      # Interface de autenticación
    Auto.php              # Lógica CRUD de autos
    Autorizacion.php      # Control de acceso por rol
    Empleado.php          # Clase de usuario empleado
    Gestionable.php       # Interface CRUD
    Marca.php             # Modelo de marcas
    Paginador.php         # Utilidad para paginación
    Usuario.php           # Lógica de usuarios, login y permisos
  database/
    DB.php                # Conexión PDO a MySQL
  public/
    assets/               # Recursos estáticos (imágenes, etc.)
    styles/
      login.css           # Estilos para la pantalla de login
      style.css           # Estilos generales
    views/
      autoEdit.php        # Edición de auto
      crearAuto.php       # Formulario de registro de auto
      crearUsuario.php    # Formulario de registro de usuario
      index.php           # Listado y búsqueda de autos
      listaUsuarios.php   # Listado de usuarios
      login.php           # Formulario de inicio de sesión
      usuarioEdit.php     # Edición de usuario
      includes/
        header.php        # Menú de navegación y logout
        styles.php        # Inclusión de estilos
  sql/
    concesionaria.sql     # Esquema de la base de datos
    seed_autos.sql        # Datos de ejemplo para autos
    seed_autos.sql.backup # Backup de seed
README.md
```

## Ejecutar la aplicación

1. Asegúrate de que XAMPP esté ejecutándose.
2. Abre tu navegador y ve a: `http://localhost/tp1/src/public/views/login.php`
3. Inicia sesión con credenciales válidas (por defecto, puede haber un usuario admin creado en la base de datos).

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

## Notas adicionales

- Asegúrate de que las rutas en los archivos PHP apunten correctamente al directorio del proyecto.
- Para producción, considera usar un servidor web dedicado y configurar variables de entorno para la base de datos.
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
