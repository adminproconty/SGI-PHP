<?php
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
		exit;
  }
	
	/* Connect To Database*/
	require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
	
	$active_facturas="";
	$active_productos="";
	$active_clientes="active";
	$active_usuarios="";	
	$title="SGI | Clientes";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include("head.php");?>
  </head>
  <body>
	<?php
	include("navbar.php");
	?>
	
  <div class="container">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="btn-group pull-right">
                <button type='button' class="btn btn-info" data-toggle="modal" data-target="#nuevoCliente" onclick="nuevoCliente()">
                    <span class="glyphicon glyphicon-plus"></span> Nuevo Cliente
                </button>
                <button class="btn btn-info" style="margin-left: 10px;" onclick="exportar()">
                    <span class="glyphicon glyphicon-cloud-download"></span>
                </button>
            </div>
            <h4><i class='glyphicon glyphicon-search'></i> Buscar Clientes</h4>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" role="form" id="datos_cliente">
                <div class="form-group row">
                    <label for="q" class="col-md-2 control-label">Nombre o Documento:</label>
                    <div class="col-md-5">
                        <input type="text" class="form-control" id="q" placeholder="Nombre o Documento" onkeyup='load();'>
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn btn-default" onclick='load();' style="display: none;">
                            <span class="glyphicon glyphicon-search"></span> Buscar
                        </button>
                        <span id="loader"></span>
                    </div>
                </div>
            </form>
            <div id="resultados">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr class="info">
                                <th class='text-left' style="width: 12%;">Tipo Documento</th>
                                <th class='text-left' style="width: 10%;">Documento</th>
                                <th class='text-left'>Nombre</th>
                                <th class='text-left' style="width: 8%;">Teléfono</th>
                                <th class='text-left' width='auto'>Email</th>
                                <th class='text-left' style="width: 8%;">Calificación</th>
                                <th class='text-right' style="width: 8%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div id="pager" style="float: right;">
                    <ul id="pagination" class="pagination-sm"></ul>
                </div>
            </div><!-- Carga los datos ajax -->
        </div>
    </div>
</div>
<hr>

<!-- Modal Nuevo Cliente-->
<div class="modal fade" id="nuevoCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Agregar nuevo cliente</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="guardar_cliente" name="guardar_cliente">
                    <div id="resultados_ajax"></div>
                    <div class="form-group producto">
                        <div class="form-group">
                            <label for="tipo_documento" class="col-sm-3 control-label" style="text-align:left; margin-left:15px;">Tipo Documento<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <select style="width: 95%; margin-left: -8px;" name="tipo_documento" id="tipo_documento" class="form-control" required>
                                    <option value="null">Seleccione...</option>
                                    <option value="Cedula">Cédula</option>
                                    <option value="RUC">R.U.C</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="documento" class="col-sm-3 control-label" style="text-align:left; margin-left:15px;">Documento<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <input style="width: 95%; margin-left: -8px;" type="number" class="form-control" id="documento" name="documento" min="1" step="1" max="9999999999" onkeyup="getByRUC()" placeholder="Documento">
                                <input style="width: 95%; margin-left: -8px;" type="number" class="form-control" id="ruc" name="ruc" min="1" step="1" max=9999999999999 onkeyup="getByRUC()" placeholder="Documento">
                                <input style="width: 95%; margin-left: -8px;" type="text" class="form-control" id="pasaporte" name="pasaporte" onkeyup="getByRUC()" placeholder="Documento">
                                <span style="color: red;" id="span_documento">¡Cliente ya registrado!</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nombre" class="col-sm-3 control-label" style="text-align:left;">Nombre<span class="obligatorio">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required>
                        </div>
                    </div>

                    <div class="form-group" style="display: none;">
                        <label for="razon_social" class="col-sm-3 control-label">Razón Social</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Razón Social">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="direccion" class="col-sm-3 control-label" style="text-align:left;">Dirección<span class="obligatorio">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección" required>
                        </div>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label for="referencia" class="col-sm-3 control-label">Referencia<span class="obligatorio">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="referencia" name="referencia" placeholder="Referencia">
                        </div>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label for="sector" class="col-sm-3 control-label">Sector<span class="obligatorio">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-control" name="sector" id="sector" class="sector"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label" style="text-align:left;">Email<span class="obligatorio">*</span></label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="telefono" class="col-sm-3 control-label" style="text-align:left;">Teléfono</label>
                        <div class="col-sm-8">
                            <input type="tel" class="form-control" id="telefono" maxlength="9" onkeypress="return validar(event)" name="telefono" placeholder="Teléfono">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="celular" class="col-sm-3 control-label" style="text-align:left;">Celular</label>
                        <div class="col-sm-8">
                            <input type="tel" class="form-control" id="celular" maxlength="10" onkeypress="return validar(event)" name="celular" placeholder="Celular">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="calificacion" class="col-sm-3 control-label" style="text-align:left;">Calificación</label>
                        <div class="col-sm-8">
                            <select name="calificacion" id="calificacion" class="form-control">
                                <option value="null">Seleccione...</option>
                                <option value="Clientes A">Clientes A</option>
                                <option value="Clientes B">Clientes B</option>
                                <option value="Clientes C">Clientes C</option>
                                <option value="Clientes D">Clientes D</option>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="guardar">Guardar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Cliente-->
<div class="modal fade" id="editarCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Modificar cliente</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="modificar_cliente" name="modificar_cliente">
                    <div id="resultados_ajax"></div>
                    <div class="form-group producto">
                        <div class="form-group">
                            <label for="editCodigo" class="col-sm-3 control-label" style="text-align:left; margin-left:15px;">Código</label>
                            <div class="col-sm-8">
                                <input style="width: 95%; margin-left: -8px;" type="text" class="form-control" id="editCodigo" name="editCodigo" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="editTipo_documento" class="col-sm-3 control-label" style="text-align:left; margin-left:15px;">Tipo Documento<span class="obligatorio">*</span></label>
                            <div class="col-sm-8">
                                <select style="width: 95%; margin-left: -8px;" name="editTipo_documento" id="editTipo_documento" class="form-control" required>
                                    <option value="null">Seleccione...</option>
                                    <option value="Cedula">Cédula</option>
                                    <option value="RUC">R.U.C</option>
                                    <option value="Pasaporte">Pasaporte</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editDocumento" class="col-sm-3 control-label" style="text-align:left;">Documento<span class="obligatorio">*</span></label>
                        <div class="col-sm-8">
                            <input style="width: 95%; margin-left: -8px;" type="number" class="form-control" id="editDocumento" name="editDocumento" min="1" step="1" placeholder="Documento">
                            <input style="width: 95%; margin-left: -8px;" type="number" class="form-control" id="editRuc" name="editRuc" min="1" step="1" placeholder="Documento">
                            <input style="width: 95%; margin-left: -8px;" type="number" class="form-control" id="editPasaporte" name="editPasaporte" placceholder="Documento">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editNombre" class="col-sm-3 control-label" style="text-align:left;">Nombre<span class="obligatorio">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editNombre" name="editNombre" placeholder="Nombre" required>
                        </div>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label for="editRazon_social" class="col-sm-3 control-label">Razón Social</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editRazon_social" name="editRazon_social" placeholder="Razón Social">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editDireccion" class="col-sm-3 control-label" style="text-align:left;">Dirección<span class="obligatorio">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editDireccion" name="editDireccion" placeholder="Dirección" required>
                        </div>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label for="editReferencia" class="col-sm-3 control-label">Referencia<span class="obligatorio">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editReferencia" name="editReferencia" placeholder="Referencia">
                        </div>
                    </div>
                    <div class="form-group" style="display: none;">
                        <label for="editSector" class="col-sm-3 control-label">Sector<span class="obligatorio">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-control" name="editSector" id="editSector"></select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editEmail" class="col-sm-3 control-label" style="text-align:left;">Email<span class="obligatorio">*</span></label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="editEmail" name="editEmail" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editTelefono" class="col-sm-3 control-label" style="text-align:left;">Teléfono</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editTelefono" name="editTelefono" placeholder="Teléfono">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editCelular" class="col-sm-3 control-label" style="text-align:left;">Celular</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editCelular" name="editCelular" placeholder="Celular">
                            <input type="hidden" class="form-control" id="id">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="editCalificacion" class="col-sm-3 control-label" style="text-align:left;">Calificación</label>
                        <div class="col-sm-8">
                            <select name="editCalificacion" id="editCalificacion" class="form-control">
                                <option value="null">Seleccione...</option>
                                <option value="Clientes A">Clientes A</option>
                                <option value="Clientes B">Clientes B</option>
                                <option value="Clientes C">Clientes C</option>
                                <option value="Clientes D">Clientes D</option>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="modificar">Guardar</button>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Load Modal -->
<div class="modal fade" id="loadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-refresh'></i> Procesando</h4>
            </div>
            <div class="modal-body">
                <p style="text-align: center;"><img src="~/img/ajax-loader.gif"> Cargando...</p>
            </div>
        </div>
    </div>
</div>

	<hr>
	<?php
	include("footer.php");
	?>
	<script type="text/javascript" src="js/clientes.js"></script>
	<link rel="stylesheet" href="css/cliente.css">
  </body>
</html>
