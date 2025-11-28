📘 AACOP – Sistema de Gestión de Capacitaciones — UTN FSA

Sistema web desarrollado como Trabajo Final Integrador (TFI) para la Tecnicatura Universitaria en Programación – UTN FSA.
Permite gestionar capacitaciones, participantes, docentes, asistencias, notas finales y certificados, integrando flujos completos para la administración interna.

El proyecto fue implementado utilizando Laravel 12, Livewire 3, TailwindCSS, MySQL/SQLite, y buenas prácticas de arquitectura MVC.

✏️ Descripción general

AACOP permite administrar de forma centralizada procesos académicos internos relacionados con cursos y capacitaciones.

Funcionalidades principales:

Registro e inicio de sesión.

Roles diferenciados:

Administrador

Docente

Participante

CRUD completo de capacitaciones.

Límite de cupos y control de inscripciones.

Gestión de asistencias.

Carga de notas finales.

Emisión de certificados.

Panel administrativo con estadísticas.

Interfaz responsive con Tailwind.

Componentes dinámicos con Livewire (validaciones + acciones en tiempo real).

Migraciones, Seeders y estructura escalable.

📂 Módulos principales
Rol	Permisos
Administrador	Crear/editar/eliminar capacitaciones, gestionar docentes, ver inscripciones, administrar notas, asistencia, certificados.
Docente	Gestionar asistencia, subir notas finales, visualizar alumnos inscriptos.
Participante	Ver capacitaciones, inscribirse, descargar certificados aprobados.
🛠️ Tecnologías usadas
Backend

PHP 8.2+

Laravel 12

Livewire 3

Laravel Breeze (autenticación)

Composer

Frontend

TailwindCSS

Blade Templates

Livewire Components

Vite

Base de datos

MySQL (producción / desarrollo)

SQLite (modo testing)

Otros

Git + GitHub

MVC

Migraciones y Seeders

Artisan CLI

🧱 Modelo de datos (simplificado)
Tabla: users
id
name
email
password
role (admin, docente, participante)
timestamps

Tabla: capacitaciones
id
titulo
descripcion
fecha_inicio
fecha_fin
cupos_maximos
docente_id (FK → users)
timestamps

Tabla: inscripciones
id
user_id (FK)
capacitaciones_id (FK)
estado (pendiente/aceptado/rechazado)
comentario
timestamps
UNIQUE (user_id, capacitaciones_id)

Tabla: asistencias
id
inscripcion_id (FK)
fecha
asistio (boolean)
timestamps

Tabla: notas_finales
id
inscripcion_id (FK)
nota
estado (aprobado/desaprobado)
timestamps

Relaciones:

Un docente puede tener varias capacitaciones.

Una capacitacion posee muchos inscriptos.

Un participante puede inscribirse sólo una vez por capacitación.

La asistencia y la nota final pertenecen a cada inscripción.

Los certificados se generan únicamente si la nota final es aprobada.

🔄 Flujo completo de una capacitación

Administrador crea una capacitación y asigna un docente.

Participantes pueden ver la lista de capacitaciones y inscribirse.

El sistema valida cupos y duplicados.

Una vez iniciada la capacitación:

El docente registra asistencia por clase.

El docente carga nota final.

Si el alumno aprueba:
→ El sistema habilita la descarga del certificado.

El administrador puede ver métricas, inscripciones y reportes generales.

💻 Instalación y configuración
# Clonar repositorio
git clone https://github.com/FabioArias23/AACOP.git
cd AACOP

# Instalar dependencias
composer install
npm install

# Configurar archivo .env
cp .env.example .env
php artisan key:generate

# Configurar la base de datos en .env

# Migrar tablas
php artisan migrate --seed

# Ejecutar servidor
php artisan serve

# Compilar assets
npm run dev

👨‍💻 Equipo de desarrollo

María Teresa Zamboni — Frontend · Livewire · UI/UX

Fabio Arias — Backend · Arquitectura

Leonardo Arce — Base de datos · Integraciones

📄 Licencia

MIT – Uso académico.