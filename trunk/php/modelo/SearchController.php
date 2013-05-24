<?php

/**
 *  Controlador para controlar la busqueda de elementos
 * @author nico
 */
/*
SELECT nombre,descripcion FROM grupos WHERE nombre LIKE '%u%' AND idgrupos IN 
	(SELECT idgrupos FROM  anillos WHERE usuarios_personas_idpersonas =1);
	
SELECT u.nombreUser, p.nombre, p.apellido, p.apellido2 FROM usuarios u, personas p 
	WHERE u.personas_idpersonas=p.idpersonas AND u.privacidad=FALSE AND (u.nombreUser LIKE '%i%' OR u.email LIKE '%i%');
 
SELECT f.mote, p.nombre, p.apellido, p.apellido2, g.nombre FROM personajes f, personas p, grupos g
	WHERE f.personas_idpersonas = p.idpersonas AND f.anillos_grupos_idgrupos=idgrupos
	AND f.mote LIKE '%' AND f.anillos_grupos_idgrupos = 1;
*/



?>
