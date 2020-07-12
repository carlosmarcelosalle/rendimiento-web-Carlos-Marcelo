#Levantar el repositorio

make build: Para generar las imagenes del repo.

make start: Para levantar todos los contenedores

make stop: Para parar los contenedores.

make console: Para poder entrar dentro de la consola de phpn y poder ejecutar comandos de symfony.


Al levantar los contenedores, mysql generará la tabla necesaria para la base de datos. La va a buscar en 
/var/mysql/db/pictures.sql . En caso de fallar, ejecutar el Create Table que hay en ese archivo. 

Una vez se hayan levantado los contenedores, hay que hacer un make console y una vez dentro del terminal ejecutar un 
composer install.

En Rabbit, hay que Crear un Exchange llamado: TransformImages y bindearlo a una cola llamada transformations


##Funcionamiento aplicación

Primero, vamos a activar el consumer para que procese las imagenes en el momento en que se almacenen en la cola. Para
ello hay que hace un make console y desde ahi ejecutar el siguiente comando:

###### php bin/console rabbitmq:consumer transform_and_save_images

Al usarlo, vemos que se quedará pensando, ya que estará esperando a que le lleguen elementos que consumir de la cola. 

Para empezar a usar la web, hay que ir a http://localhost:8080/. Ahí veremos un formulario donde hay que introducir los 
tags, descripción y añadir alguna fotografia. Al darle al botón de upload, se mandarán las indicaciones para hacer
las transformaciones a rabbit y lo almacenará en la cola.

Como ahora ya tenemos el consumer abierto y esperando, automaticamente se pondrá a consumir elementos de la cola de rabbit
y se pondrá a transformar las imagenes, por lo que en pocos segundos ya las tendremos listas.

Para ver las imagenes y sus transformaciones, hay que pulsar el botón de My pictures y te llevará a http://localhost:8080/gallery. 
La primera vez que entremos en esta página, se hará una consulta a mysql para extraer la información para pintar estas 
imagenes y lo guardará en cache. De esta manera, la segunda vez lo consumirá de ahi y se cargará mucho mas rápido. En esta 
sección se pueden ver tanto la foto original como las transformaciones.

Para ir al buscador, hay que darle al botón de search, que te llevará a la ruta: http://localhost:8080/search.
Desde aqui, tenemos un input que va comunicandose con Elastic Search mediante peticiones AJAX. Cada vez que se escribe 
en el input, se lanza una petición y devuelve unos resultados. De esta manera, si buscamos por ejemplo sepia, nos apareceran
todas las imagenes que tienen el tag sepia. En esta parte, no he llegado a implementar fuzzy search, ya que no me ha dado
tiempo.

 
 
##Conclusiones

La verdad es que ha sido una práctica larga y dura pero me ha servido mucho para conocer estas herramientas. Si hubiera 
tenido más tiempo, hubiera podido implementar la parte de elastic con fuzzy search y search-as-you-type, y me hubiera 
gustado darle un lavado de cara a la parte de front y ponerlo más decente, con estilos. 

La parte de blackfire y de ligthhouse me ha parecido muy útil, ya que hay muchas cosas que cuando vamos programando no 
caemos y pueden hacer que nuestra web vaya más lenta.
