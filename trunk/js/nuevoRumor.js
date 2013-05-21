function nuevoRumor(){
	$.post("php/nuevoRumor.php",{grupo:$("#grupos").val(),contenido:$("#contenido").val(),lugar:$("#lugar").val(),enlace:$("#enlace").val()});
}