function desplegar(id) {
	if (document.getElementById('submenu').style.display = 'none') {
		document.getElementById('submenu').style.display = 'block';
	}
	seleccionado(id);
}

function ocultar(id) {
	if (document.getElementById('submenu').style.display = 'block') {
		document.getElementById('submenu').style.display = 'none';
	}
	desseleccionado(id);
}

function seleccionado(id) {
	if (document.getElementById(id).style.backgroundColor != '#a2b84b') {
		document.getElementById(id).style.backgroundColor = '#a2b84b';
	}
}

function desseleccionado(id) {
	if (document.getElementById(id).style.backgroundColor != '#809629') {
		document.getElementById(id).style.backgroundColor = '#809629';
	}
}

function cargaranimales(idraza,pagina) {
	$.ajax({
		url: "index.php?accion=listado&id=1&pagina=" + pagina + "&idraza=" + idraza,
		cache: false,
		dataType: "json",
		success: function(result) {
			$("#menu").html(result[2]);
			$("#animales").html(result[1]);
			$("#paginacion").html(result[0]);
		},
		error: function (request, status, error) {
	        alert(request.responseText);
	    }
	});
}
