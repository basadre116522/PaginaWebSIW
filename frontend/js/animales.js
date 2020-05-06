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