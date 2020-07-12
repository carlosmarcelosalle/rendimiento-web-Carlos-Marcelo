######Todas las capturas de pantalla se encuentran en la carpeta profiling.

#Blackfire

El primer analisis hecho ha detectado que el tiempo de carga es de 2.44 segundos. Salian varias recomendaciones para 
poder mejorar el rendimiento.
Siguiendo estas recomendaciones, he optimizado el Composer autoloader para que PHP lo pudiera cargar de manera mas eficiente.
Tambien me recomendaba desactivar el modo de debug, por lo que he pasado el entonrno de mi aplicación de dev a prod.

También, habia varias recomendaciones acerca de Doctrine y los annotations. Me decia que en producción los annotations 
deben de estar cacheados. Yo no estoy utilizando annotations en mi aplicación, así que imagino que debe ser alguna 
libreria o incluso el propio symfony, por lo que no le he hecho mucho caso a esa.

Aplicando estas mejoras he conseguido que el tiempo de carga pase de 2.44 segundos a 774 milisegundos. Como se puede ver
en la imagen de la comparativa, el tiempo de carga se ha reducido en un 68% y los demas recursos tambien se han visto
reducidos, por lo que se puede ver que ha sido una mejora importante.

#Lighthouse

Con Lighthouse, el primer analisis nos ha dado un resultado de Performance de 78. Viendo las metricas, he podido ver
que especialmente se quejaba de javascript con contenido bloqueante. El primer contenido de la página tardaba unos 3.9
segundos en renderizarse. Siguiendo las recomendaciones, he modificado la carga de los ficheros de javascript para que 
el navegador no se pare a descargarlos y a ejecutarlos y que asi el contenido no se bloquee y se renderize la página mas
rápido. Para hacer esto, he puesto el atributo deferer en la carga de los scripts. Aún y así, seguia sin ser todo lo 
rápido que podia ser. 

La siguiente mejora que he hecho ha sido minificar los ficheros de javascript para que el navegador pueda interpretarlo
mas rápido. Esto, junto a la anterior mejora ha ayudado mucho a mejorar el tiempo de renderizado de la web. 

Con estas mejoras aplicadas, he conseguido que el tiempo de carga pase de 3.9 segundo a 0.8 segundos, consiguiendo pasar
de un nivel de performance de 74 a 100. 
