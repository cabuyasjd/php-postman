API en PHP con pruebas en Postman
📌 Descripción del proyecto
Este repositorio contiene una API REST desarrollada en PHP para demostrar operaciones CRUD (Crear, Leer, Actualizar y Eliminar) con fines educativos.
La API fue probada utilizando Postman, asegurando que todos los endpoints devuelvan respuestas consistentes en formato JSON.

🚀 Funcionalidades
CRUD de usuarios y operaciones.

Endpoints con respuestas en JSON.

Manejo de errores con códigos de estado HTTP.

Pruebas realizadas con colecciones de Postman.

🛠️ Tecnologías utilizadas
PHP 8.x

MySQL (opcional para persistencia)

Postman (para pruebas)

Composer (gestión de dependencias)

📂 Estructura del proyecto
Código
php-postman/
│── api/
│   ├── index.php        # Punto de entrada principal
│   ├── users.php        # CRUD de usuarios
│   └── operations.php   # CRUD de operaciones
│── tests/
│   └── postman_collection.json
│── README.md
⚙️ Instalación
Clonar el repositorio:

bash
git clone https://github.com/cabuyasjd/php-postman.git
Entrar en la carpeta del proyecto:

bash
cd php-postman
Iniciar un servidor local de PHP:

bash
php -S localhost:8000 -t api
📡 Endpoints de la API
Usuarios
GET /users → Listar todos los usuarios

POST /users → Crear un nuevo usuario

PUT /users/{id} → Actualizar usuario por ID

DELETE /users/{id} → Eliminar usuario por ID

Operaciones
GET /operations → Listar todas las operaciones

POST /operations → Crear una nueva operación

PUT /operations/{id} → Actualizar operación por ID

DELETE /operations/{id} → Eliminar operación por ID

🧪 Pruebas con Postman
Se puede importar la colección de Postman (tests/postman_collection.json) para probar todos los endpoints.

(colocar imagen de Postman mostrando una petición GET aquí)
(colocar imagen de Postman mostrando respuesta JSON aquí)

📖 Documentación
Cada endpoint devuelve respuestas en formato JSON con códigos HTTP adecuados.

Los mensajes de error están estandarizados para facilitar la depuración.

Las pruebas en Postman validan escenarios de éxito y de error.

📌 Mejoras futuras
Implementar autenticación con tokens JWT.

Integrar documentación con Swagger/OpenAPI.

Añadir pruebas automatizadas con Newman.

📜 Licencia
Este proyecto está bajo la licencia MIT. Se puede usar, modificar y distribuir libremente con fines educativos.
