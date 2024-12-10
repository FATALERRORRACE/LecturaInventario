# to run docker container
# docker compose -f deploy/docker.compose.yml --env-file ./.env up --build --detach
se carga la base de datos ya sea en creando un servidor linux o windows, se agrega la base de datos correspondiente y apuntamos desde el .env al host de base de datos host.docker.internal + el puerto host.docker.internal:3306 para generar la conexión, no se recomienda dentro del contenedor ya que los datos dentro de un contenedor no son persistentes.

ejecutar php artisan migrate:install solo de ser necesario