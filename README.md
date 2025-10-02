# VendingV1 — Symfony 7 + Docker (Apache) + MySQL 8 · DDD + SOLID

Este README explica **requisitos mínimos** y **pasos para ejecutar** el proyecto con Docker. Está orientado a desarrollo local en Windows/macOS/Linux.

---

## Requisitos mínimos

- **Docker Desktop** (o Docker Engine) reciente
  - Windows: **WSL2 habilitado**
  - macOS: Apple Silicon/Intel soportado
- **Docker Compose v2** (incluido en Docker Desktop recientes)
- **Git** (para clonar el repo)
- **Administrador de base de datos (si quieres comprobar o editar la base de datos manualmente)**
  - Workbench, etc...
- **Memoria** disponible \~2 GB (contenedores PHP+Apache y MySQL)

> No necesitas tener **PHP** ni **Composer** instalados en tu host: el proyecto usa un servicio `composer` dentro de Docker.

---

## Servicios del proyecto

- **web**: PHP 8.3 + Apache, monta `./app` en `/var/www/html`.
- **composer**: contenedor temporal para ejecutar Composer sobre `./app`.
- **mysql** *(si está configurado)*: MySQL 8 con datos en volumen.

Puertos por defecto:

- **Web**: [http://localhost:8080](http://localhost:8080)

---

## Primer arranque (Desarrollo)

1. **Clonar** el repositorio

```bash
git clone https://github.com/mcninu23/VendingV1.git vendingv1

# Acceder a la carpeta donde has clonado el repositorio
cd vendingv1
```

2. **Abrir aplicacion Docker**

3. **Levantar contenedores**

```env
docker compose up -d --build
```

4. **Generar archivos autoload**

```bash
docker compose run --rm composer dump-autoload
```

5. **Instalar dependencias PHP** (dentro de Docker)

```bash
docker compose run --rm composer install
```

6. **Ejecutar la limpieza de cache**

```bash
docker compose run --rm composer install
```

7. **Preparar base de datos**

```bash
docker compose exec web php bin/console doctrine:migrations:migrate -n
```

8. **Comprobaciones rápidas**

- Salud de la app: `http://localhost:8080/health` → **OK**
- Salud BD: `http://localhost:8080/db/health` → **DB OK**
- Home (frontend): `http://localhost:8080/`

---

## Estructura (resumen DDD)

```
.
├── app/                          # Código Symfony
│   ├── bin/
│   ├── config/
│   ├── migrations/
│   ├── public/
│   ├── src/
│   │   └── System/               # Bounded Context sencillo para utilidades del sistema
│   │       ├── Domain/
│   │       ├── Application/
│   │       └── Infrastructure/
│   │           └── Http/         # Adaptador HTTP (controladores)
│   ├── templates/                # Twig (frontend)
│   └── var/
├── docker/
│   └── php-apache/
│       ├── Dockerfile
│       ├── vhost.conf
│       └── php.ini
├── .dockerignore
└── docker-compose.yml                  
```

- **Domain**: entidades, VOs, interfaces de repositorio.
- **Application**: casos de uso/servicios de aplicación.
- **Infrastructure**: adaptadores (HTTP, DB con Doctrine, etc.).

---

## Endpoints principales

- `GET /health` — Salud de la aplicación.
- `GET /db/health` — Salud de la conexión a BD.
- `GET /` — Página principal (vending).
- `POST /api/items/purchase` — Compra de bebida.
- `GET /api/service/config` — Configuración de Service (admin).
- `POST /api/service/set` — Set de cantidades (admin).

---


## Tests (si están configurados)

```bash
docker compose exec web php bin/phpunit
```
---


