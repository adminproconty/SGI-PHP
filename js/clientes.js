$(document).ready(function() {
	$('#ruc').hide();
	$('#pasaporte').hide();
	load(1);
});

function load(page) {
	var q = $("#q").val();
	$("#loader").fadeIn('slow');
	$.ajax({
			url: './ajax/buscar_clientes.php?action=ajax&page=' + page + '&q=' + q,
			beforeSend: function(objeto) {
					$('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
			},
			success: function(data) {
					$(".outer_div").html(data).fadeIn('slow');
					$('#loader').html('');

			}
	})
}

function eliminar(id) {
	var q = $("#q").val();
	if (confirm("Realmente deseas eliminar el cliente")) {
			$.ajax({
					type: "GET",
					url: "./ajax/buscar_clientes.php",
					data: "id=" + id,
					"q": q,
					beforeSend: function(objeto) {
							$("#resultados").html("Mensaje: Cargando...");
					},
					success: function(datos) {
							$("#resultados").html(datos);
							load(1);
					}
			});
	}
}

function limpiar() {
	$('#tipo_documento').val('');
	$('#documento').val('');
	$('#ruc').val('');
	$('#pasaporte').val('');
	$('#nombre').val('');
	$('#razon_social').val('');
	$('#direccion').val('');
	$('#email').val('');
	$('#celular').val('');
	$('#calificacion').val('null');
	$('#nuevoCliente');
	$('#nuevoCliente').modal('hide');
}

function getByRUC() {
			var urlCompleta = url + 'Ingresos/clienteByDocumento';
			if ($('#tipo_documento').val() == 'Cedula') {
					$.get(urlCompleta, {
							documento: $('#documento').val()
					}, function(response) {
							if (response.length > 0) {
									$('#span_documento').show('slow');
									$('#guardar').prop('disabled', true);
							} else {
									$('#span_documento').hide('slow');
									$('#guardar').prop('disabled', false);
							}
					});
			} else if ($('#tipo_documento').val() == 'RUC') {
					$.get(urlCompleta, {
							documento: $('#ruc').val()
					}, function(response) {
							if (response.length > 0) {
									$('#span_documento').show('slow');
									$('#guardar').prop('disabled', true);
							} else {
									$('#span_documento').hide('slow');
									$('#guardar').prop('disabled', false);
							}
					});
			} else if ($('#tipo_documento').val() == 'Pasaporte') {
					$.get(urlCompleta, {
							documento: $('#pasaporte').val()
					}, function(response) {
							if (response.length > 0) {
									$('#span_documento').show('slow');
									$('#guardar').prop('disabled', true);
							} else {
									$('#span_documento').hide('slow');
									$('#guardar').prop('disabled', false);
							}
					});
			}
}

$('#tipo_documento').change(function() {
	switch ($('#tipo_documento').val()) {
			case 'null':
					$('#documento').show();
					$('#ruc').hide();
					$('#pasaporte').hide();
					break;

			case 'Cedula':
					$('#documento').show();
					$('#ruc').hide();
					$('#pasaporte').hide();
					break;

			case 'RUC':
					$('#documento').hide();
					$('#ruc').show();
					$('#pasaporte').hide();
					break;

			case 'Pasaporte':
					$('#documento').hide();
					$('#ruc').hide();
					$('#pasaporte').show();
					break;
	}
});

$('#editTipo_documento').change(function() {
	var valor = $('#editTipo_documento').val();
	if (valor == 'null') {
			$('#editDocumento').show();
			$('#editRuc').hide();
			$('#editPasaporte').hide();
	} else if (valor = 'Cedula') {
			$('#editDocumento').show();
			$('#editRuc').hide();
			$('#editPasaporte').hide();
	} else if (valor == 'Pasaporte') {
			$('#editDocumento').hide();
			$('#editRuc').hide();
			$('#editPasaporte').show();
	} else if (valor == 'RUC') {
			$('#editDocumento').hide();
			$('#editRuc').show();
			$('#editPasaporte').hide();
	}
});

$("#guardar_cliente").submit(function(event) {
	$('#guardar_datos').attr("disabled", true);

	var parametros = $(this).serialize();
	$.ajax({
			type: "POST",
			url: "ajax/nuevo_cliente.php",
			data: parametros,
			beforeSend: function(objeto) {
					$('#resultados_ajax').html('<img src="./img/ajax-loader.gif"> Cargando...');

			},
			success: function(datos) {
					$("#resultados_ajax").html(datos);
					$('#guardar_datos').attr("disabled", false);
					limpiar();
					load(1);
			}
	});
	event.preventDefault();
})

$("#editar_cliente").submit(function(event) {
	$('#actualizar_datos').attr("disabled", true);

	var parametros = $(this).serialize();
	$.ajax({
			type: "POST",
			url: "ajax/editar_cliente.php",
			data: parametros,
			beforeSend: function(objeto) {
					$("#resultados_ajax2").html("Mensaje: Cargando...");
			},
			success: function(datos) {
					$("#resultados_ajax2").html(datos);
					$('#actualizar_datos').attr("disabled", false);
					load(1);
			}
	});
	event.preventDefault();
})

function obtener_datos(id) {
	var nombre_cliente = $("#nombre_cliente" + id).val();
	var telefono_cliente = $("#telefono_cliente" + id).val();
	var email_cliente = $("#email_cliente" + id).val();
	var direccion_cliente = $("#direccion_cliente" + id).val();
	var status_cliente = $("#status_cliente" + id).val();

	$("#mod_nombre").val(nombre_cliente);
	$("#mod_telefono").val(telefono_cliente);
	$("#mod_email").val(email_cliente);
	$("#mod_direccion").val(direccion_cliente);
	$("#mod_estado").val(status_cliente);
	$("#mod_id").val(id);

}