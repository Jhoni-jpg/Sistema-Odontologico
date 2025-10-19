# Sistema Odontológico

Un proyecto elaborado en PHP (backend y estructura de la web), CSS (estilización en conjunto con JS), JS (manejo de AJAX para la comunicación del front con el back) y PostgreSQL como gestor de bases de datos. Utiliza Composer para la gestión de dependencias y Docker / Docker Compose para orquestar el entorno.

---

## Requisitos previos

- Docker instalado y funcionando: https://docs.docker.com/get-docker/
- Docker Compose instalado: https://docs.docker.com/compose/install/
- Composer (opcional localmente si usarás Composer dentro del contenedor)

---

## Instalación y levantamiento del proyecto

1. Clonar el repositorio

```bash
git clone https://github.com/Jhoni-jpg/Sistema-Odontologico.git
cd Sistema-Odontologico
```

2. Levantar contenedores Docker

```bash
docker-compose up -d
```

Esto levantará los servicios definidos en docker-compose (por ejemplo: app, postgres, etc.).

3. Instalar dependencias con Composer dentro del contenedor

```bash
docker-compose exec app composer install
```

- Instalar composer en local

```bash
composer install
```

---

## Comandos útiles

- Levantar contenedores en primer plano (útil para ver logs):
```bash
docker-compose up
```

- Reiniciar un servicio:
```bash
docker-compose restart app
```

- Ejecutar shell dentro del contenedor de la app:
```bash
docker-compose exec app sh
# o
docker-compose exec app bash
```

- Regenerar el autoload optimizado de Composer:
```bash
docker-compose exec app composer dump-autoload -o
```

- Ver logs de un servicio (ej. app o postgres):
```bash
docker-compose logs -f app
docker-compose logs -f postgres
```

---

## Uso

Accede a la aplicación desde tu navegador en la URL y puerto configurados en `.env` (por ejemplo `http://localhost` o `http://localhost:8080` según la configuración). La base de datos PostgreSQL estará disponible en el puerto que hayas configurado en `.env`.

---

## Git

IMPORTANTE: la carpeta `vendor/` está incluida en `.gitignore`. No subas esa carpeta al repositorio para evitar problemas con dependencias y tamaño del repositorio.

Si la subiste por accidente, elimina la carpeta y crea un commit que la borre, luego añade `vendor/` a `.gitignore`.

---

## Troubleshooting (problemas comunes)

- Clases o dependencias faltantes:
```bash
docker-compose exec app composer install
docker-compose exec app composer dump-autoload -o
```

- Problemas de permisos en mounts:
  - Asegúrate de que los UID/GID del volumen sean compatibles entre host y contenedor o ajusta permisos apropiadamente.
  - En Linux, puede ayudar ejecutar `chown -R $(id -u):$(id -g) ./ruta` en el host para ajustar permisos (con cuidado).

- PostgreSQL no conecta:
  - Verifica las variables de conexión en `.env` (host, puerto, usuario, contraseña, nombre de DB).
  - Revisa los logs del servicio postgres: `docker-compose logs -f postgres`.

---
