<?php
defined('BASEPATH') or exit('No direct script access allowed');
$input_imagen = array(
    'name' => 'mi_imagen',
    'id' => 'mi_imagen',
    'type' => 'file',
    'class' => 'form-control-file'
);
?>
<style>
body {
	position: relative;
}

ul.nav-pills {
	position: fixed;
}
</style>
<script type="text/javascript">
var procesos = <?php echo $jsonProcesos; ?>;
var indiceProcesos = 0;
var contadorProcesos = 0;
function eliminarRenglonPoceso(id) {
  $('#renglonNuevoLavado'+id).remove();
  contadorProcesos--;
}
<?php if ($lavadosCorte != 0): //Funciones para los lavados ?>
function editarLavado(carga){
  if (confirm("¿Está seguro de cambiar el lavado? Afectará cambios en: la autorización del corte y la producción de los operarios.")) {
    $.ajax({
      type: "POST",
      url: "editarLavadoCorte",
      data: {
        lavado_id: $('#lavado_'+carga).val(),
        folio:  <?php echo $this->input->get()['folio'] ?>,
        id_carga: carga,
      },
      success: function(res) {
        if (res.respuesta) location.reload();
      },
      dataType: "json",
      error: function (request, status, error) {
        console.log(request.responseText);
      }
    });
  }
}
function eliminarLavado(carga){
  if (confirm("¿Está seguro de eliminar el lavado? Eliminará en la autorización del corte, salida interna y la producción de los operarios.")) {
    $.ajax({
      type: "POST",
      url: "eliminarLavadoCorte",
      data: {
        folio:  <?php echo $this->input->get()['folio'] ?>,
        id_carga: carga,
      },
      success: function(res) {
        if (res.respuesta) location.reload();
      },
      dataType: "json",
      error: function (request, status, error) {
        console.log(request.responseText);
      }
    });
  }
}
<?php endif; ?>
<?php if ($autorizadoDatos != 0): //funciones para los datos del corte autorizado ?>
function editarAutorizadoDatos(id){
  $('#id_autorizado_datos').val(id);
  $('#id_carga_autorizado_datos').val($('#id_carga_autorizado_datos_anterior_'+id).val());
  $('#lavado_id_autorizado_datos').val($('#id_lavado_autorizado_datos_anterior_'+id).val());
  $('#proceso_id_autorizado_datos').val($('#id_proceso_seco_id_autorizado_datos_anterior_'+id).val());
  $('#autorizado_datos_costo').val($('#costo_autorizado_datos_'+id).val());
  $('#autorizado_datos_piezas_trabajadas').val($('#piezas_trabajadas_autorizado_datos_'+id).val());
  $('#autorizado_datos_defectos').val($('#defectos_autorizado_datos_'+id).val());
  $('#autorizado_datos_estatus').val($('#status_autorizado_datos_'+id).val());
  $('#autorizado_datos_orden').val($('#orden_autorizado_datos_'+id).val());
  $('#autorizado_datos_fecha_registro_').val($('#fecha_registro_autorizado_datos_'+id).val());
  $('#autorizado_datos_usuario_id').val($('#usuario_id_autorizado_datos_'+id).val());
  $('#modalCorteAutorizadoDatos').modal('show');
}
function eliminarAutorizadoDatos(id){
  if (confirm("¿Está seguro de eliminar este registro?")) {
    $.ajax({
      type: "POST",
      url: "eliminarAutorizacionDatos",
      data: {
        id: id,
      },
      success: function(res) {
        if (res.respuesta) location.reload();
      },
      dataType: "json",
      error: function (request, status, error) {
        console.log(request.responseText);
      }
    });
  }
}
<?php endif ?>
<?php if ($salidaInternaDatos != 0): ?>
function editarSalidaInternaDatos(id_carga){
  if ($('#piezasSalidaInternaDatos'+id_carga).val() == '') alert("Existen campos en blanco, favor de revisar.");
  else {
    if (confirm("¿Está seguro de querer editar los datos de este proceso? Se actualizarán datos de salida interna, autorización de corte y de la producción de los operarios")) {
      $.ajax({
        type: "POST",
        url: "editarSalidaInternaDatos",
        data: {
          folio: "<?php echo $this->input->get()['folio']; ?>",
          id_carga: id_carga,
          piezas: $('#piezasSalidaInternaDatos'+id_carga).val(),
        },
        success: function(res) {
          if (res.respuesta) location.reload();
        },
        dataType: "json",
        error: function (request, status, error) {
          console.log(request.responseText);
        }
      });
    }
  }
}
<?php endif; ?>
<?php if ($produccionProcesoSeco != 0): ?>
function editarProduccion(id) {
  if ($('#piezasProduccion'+id).val() == '' || $('#defectosProduccion'+id).val() == '') alert("Existen campos vacíos, favor de revisar");
  else {
    if (confirm("¿Está seguro de cambiar los datos de este registro?, se verá reflejado en la nómina de los operarios.")) {
      $.ajax({
        type: "POST",
        url: "editarProduccion",
        data: {
          id: id,
          piezas: $('#piezasProduccion'+id).val(),
          defectos: $('#defectosProduccion'+id).val(),
          estado_nomina: $('#estadoNomina'+id).val(),
          razon_pagar: $('#razonProduccion'+id).val(),
        },
        success: function(res) {
          if (res.respuesta) location.reload();
        },
        dataType: "json",
        error: function (request, status, error) {
          console.log(request.responseText);
        }
      });
    }
  }
}
function eliminarProduccion(id) {
  if (confirm("¿Está seguro de eliminar este registro?, se verá reflejado en la nómina de los operarios.")) {
    $.ajax({
      type: "POST",
      url: "eliminarProduccion",
      data: {
        id: id,
      },
      success: function(res) {
        if (res.respuesta) location.reload();
      },
      dataType: "json",
      error: function (request, status, error) {
        console.log(request.responseText);
      }
    });
  }
}
<?php endif; ?>
<?php if ($reprocesos != 0): ?>
function editarReproceso(id) {
  if ($('#lavadoReproceso'+id).val() != '' && $('#procesoReproceso'+id).val() != '' && $('#estatusReproceso'+id).val() != '' && $('#costoReproceso'+id).val() != '' && $('#piezasReproceso'+id).val() != '' && $('#defectosReproceso'+id).val() != '') {
    if (confirm('¿Está seguro de editar el reproceso?')) {
      $.ajax({
        type: "POST",
        url: "editarReproceso",
        data: {
          id: id,
          lavado_id: $('#lavadoReproceso'+id).val(),
          proceso_seco_id: $('#procesoReproceso'+id).val(),
          status: $('#estatusReproceso'+id).val(),
          costo: $('#costoReproceso'+id).val(),
          piezas_trabajadas: $('#piezasReproceso'+id).val(),
          defectos: $('#defectosReproceso'+id).val(),
        },
        success: function(res) {
          if (res.respuesta) location.reload();
        },
        dataType: "json",
        error: function (request, status, error) {
          console.log(request.responseText);
        }
      });
    }
  }
  else alert("Existen campos vacíos, favor de revisar.")
}
function eliminarReproceso(id) {
  if (confirm('¿Está seguro de eliminar el reproceso? Se afectarán a datos de reprocesos y de la producción de reprocesos')) {
    $.ajax({
      type: "POST",
      url: "eliminarReproceso",
      data: {
        id: id,
      },
      success: function(res) {
        if (res.respuesta) location.reload();
      },
      dataType: "json",
      error: function (request, status, error) {
        console.log(request.responseText);
      }
    });
  }
}
<?php endif; ?>
<?php if ($produccionReprocesos != 0): ?>
function editarProduccionReproceso(id)
{
  if ($('#piezasProduccionReproceso'+id).val() == "" || $('#defectosProduccionReproceso'+id).val() == "" || $('#estadoNominaReproceso'+id).val() == "") alert("Existen campos vacíos, favor de revisar por favor");
  else {
    if (confirm("¿Está seguro de querer ediar este renglón de producción?")) {
      $.ajax({
        type: "POST",
        url: "editarProduccionReproceso",
        data: {
          id: id,
          piezas: $('#piezasProduccionReproceso'+id).val(),
          defectos: $('#defectosProduccionReproceso'+id).val(),
          estado_nomina: $('#estadoNominaReproceso'+id).val(),
          razon_pagar: $('#razonProduccionReproceso'+id).val(),
        },
        success: function(res) {
          if (res.respuesta) location.reload();
        },
        dataType: "json",
        error: function (request, status, error) {
          console.log(request.responseText);
        }
      });
    }
  }
}
function eliminarProduccionReproceso(id)
{
  if (confirm("¿Está seguro de querer eliminar este renglón de producción?")) {
    $.ajax({
      type: "POST",
      url: "eliminarProduccionReproceso",
      data: {
        id: id,
      },
      success: function(res) {
        if (res.respuesta) location.reload();
      },
      dataType: "json",
      error: function (request, status, error) {
        console.log(request.responseText);
      }
    });
  }
}
<?php endif; ?>
function reSize()
{
  var secciones =['#seccion1','#seccion2','#seccion3','#seccion4','#seccion5','#seccion6','#seccion7','#seccion8','#seccion9'];
  $.each(secciones, function( index, value ) {
    $(value).css('min-height',$(window).height());
  });
}
$(document).ready(function() {
  reSize();
  $(window).resize(function(){
    reSize();
  });
  $('#botonGenerales').click(function(){
    if ($('#folio').val()=='' || $('#corte').val() == '' || $('#marca_id').val() == '' || $('#maquilero_id').val() == '' || $('#cliente_id').val() == '' || $('#tipo_pantalon_id').val() == '' || $('#fecha_entrada').val() == '' || $('#piezas').val() == '' || $('#ojales').val() == '')
    alert("Existen campos vacíos, favor de revisar");
    else {
      if (confirm('¿Está seguro que desea cambiar los datos del corte?')) {
        $.ajax({
          type: "POST",
          url: "modificarGenerales",
          data: {
            folio: $('#folio').val(),
            fecha_entrada : $('#fecha_entrada').val(),
            corte: $('#corte').val(),
            marca_id: $('#marca_id').val(),
            maquilero_id: $('#maquilero_id').val(),
            cliente_id: $('#cliente_id').val(),
            tipo_pantalon_id: $('#tipo_pantalon_id').val(),
            piezas: $('#piezas').val(),
            ojales: $('#ojales').val(),
          },
          success: function(res) {
            if (res.respuesta) location.reload();
          },
          dataType: "json",
          error: function (request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    }
  });
  $('#botonModalImagen').click(function() {
    if ($('#folio').val() == '' || $('#mi_imagen').val() == '') alert("Existen campos vacíos, favor de revisar.");
    else {
      if (confirm('¿Está seguro que desea cambiar la imágen del corte?')) $('#cambiarImagen').submit();
    }
  });
  //Funciones para nuevo lavado
  $('#botonAgregarProcesos').click(function() {
    $('#tablaProcesos tbody').append('<tr name="renglonNuevoLavado'+indiceProcesos+'" id="renglonNuevoLavado'+indiceProcesos+'"></tr>');
    var cadenaSelect = '<select class="form-control" name="procesoNuevo['+indiceProcesos+']" id="procesoNuevo['+indiceProcesos+']">';
    $.each(procesos, function(i, item) {
      cadenaSelect += '<option value="'+item.id+'">'+item.nombre+'</option>';
    });
    cadenaSelect += '</select>';
    $('#renglonNuevoLavado'+indiceProcesos).append('<td>'+cadenaSelect+'</td><td><button type="button" class="btn btn-danger" onclick="eliminarRenglonPoceso('+indiceProcesos+')"><i class="far fa-trash-alt"></i></button></td>');
    indiceProcesos ++;
    contadorProcesos ++;
  });
  $('#formAgregarLavado').submit(function() {
    return confirm("¿Está seguro de querer agregar el lavado?");
  });
  <?php if ($autorizado != 0): //Funciones para el corte autorizado?>
  $('#botonAutorizacion').click(function() {
    if ($('#folio').val() == '' || $('#fecha_autorizado').val() == '' || $('#usuarioAutorizo').val() == '') alert("Existen campos vacíos, favor de revisar.");
    else {
      if (confirm('¿Está seguro que desea cambiar la autorización del corte?')) {
        $.ajax({
          type: "POST",
          url: "editarAutorizacion",
          data: {
            corte_folio: $('#folio').val(),
            fecha_autorizado : $('#fecha_autorizado').val(),
            usuario_id: $('#usuarioAutorizo').val(),
          },
          success: function(res) {
            if (res.respuesta) location.reload();
          },
          dataType: "json",
          error: function (request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    }
  });
  $('#botonAutorizacionEliminar').click(function() {
    if (confirm("¿Está seguro de querer eliminar los datos de autorización del corte con folio <?php echo $this->input->get()['folio']; ?>? Se eliminarán: datos de autorización, datos de salida interna y datos de producción de los operarios.")) {
      $.ajax({
        type: "POST",
        url: "eliminarAutorizacion",
        data: {
          folio: $('#folio').val(),
        },
        success: function(res) {
          if (res.respuesta) location.reload();
        },
        dataType: "json",
        error: function (request, status, error) {
          console.log(request.responseText);
        }
      });
    }
  });
  <?php endif; ?>
  <?php if ($autorizadoDatos != 0): //Funciones para los datos del corte autorizado?>
  $('#editarDatosCorteAutorizado').click(function() {
    if ($('#proceso_id_autorizado_datos').val() == '' || $('#autorizado_datos_costo').val() == '' || $('#autorizado_datos_piezas_trabajadas').val() == '' || $('#autorizado_datos_defectos').val() == '' || $('#autorizado_datos_estatus').val() == '' || $('#autorizado_datos_orden').val() == '' || $('#autorizado_datos_fecha_registro_').val() == '' || $('#autorizado_datos_usuario_id').val() == '') alert("Existen campos vacíos, favor de revisar.")
    else {
      $.ajax({
        type: "POST",
        url: "editarAutorizacionDatos",
        data: {
          id: $('#id_autorizado_datos').val(),
          proceso_seco_id: $('#proceso_id_autorizado_datos').val(),
          costo: $('#autorizado_datos_costo').val(),
          piezas_trabajadas: $('#autorizado_datos_piezas_trabajadas').val(),
          defectos: $('#autorizado_datos_defectos').val(),
          status: $('#autorizado_datos_estatus').val(),
          orden: $('#autorizado_datos_orden').val(),
          fecha_registro: $('#autorizado_datos_fecha_registro_').val(),
          usuario_id: $('#autorizado_datos_usuario_id').val(),
        },
        success: function(res) {
          if (res.respuesta) location.reload();
        },
        dataType: "json",
        error: function (request, status, error) {
          console.log(request.responseText);
        }
      });
    }
  });
  <?php endif; ?>
  <?php if ($salidaInterna != 0): //Funciones de salida interna ?>
  $('#botonSalidaInterna').click(function() {
    if ($('#fechaSalidaInterna').val() == '' || $('#muestrasSalidaInterna').val() == '' || $('#usuarioSalidaInterna').val() == '') alert("Existen campos vacíos, favor de revisar.")
    else {
      if (confirm("¿Está seguro que desea cambiar los datos de salida interna?")) {
        $.ajax({
          type: "POST",
          url: "editarSalidaInterna",
          data: {
            corte_folio: $('#folio').val(),
            fecha: $('#fechaSalidaInterna').val(),
            muestras: $('#muestrasSalidaInterna').val(),
            usuario_id: $('#usuarioSalidaInterna').val(),
          },
          success: function(res) {
            if (res.respuesta) location.reload();
          },
          dataType: "json",
          error: function (request, status, error) {
            console.log(request.responseText);
          }
        });
      }
    }
  });
  $('#botonSalidaInternaEliminar').click(function() {
    if (confirm("¿Está seguro que desea eliminar los datos de salida interna? Se eliminarán datos de salida interna y de producción de los operarios.")) {
      $.ajax({
        type: "POST",
        url: "eliminarSalidaInterna",
        data: {
          folio: $('#folio').val(),
        },
        success: function(res) {
          if (res.respuesta) location.reload();
        },
        dataType: "json",
        error: function (request, status, error) {
          console.log(request.responseText);
        }
      });
    }
  });
  <?php endif; ?>
  var tablas = [];
  <?php if ($produccionProcesoSeco != 0): ?> tablas.push('#tablaProduccion'); <?php endif;

if ($autorizadoDatos != 0)
:
    ?> tablas.push('#tblespecificosAutorizacion'); <?php endif;

if ($reprocesos != 0)
:
    ?> tablas.push('#tablaReprocesos'); <?php endif;

if ($produccionReprocesos != 0)
:
    ?> tablas.push('#tablaProduccionReprocesos'); <?php endif; ?>
  $.each(tablas, function( index, value ) {
    $(value).DataTable({
      language: {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningún dato disponible en esta tabla",
        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
          "sFirst": "Primero",
          "sLast": "Último",
          "sNext": "Siguiente",
          "sPrevious": "Anterior"
        },
        "oAria": {
          "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
          "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
      },
      "lengthMenu": [ 5, 10, 20, 50, 100 ],
    });
  });
});
</script>
<input type="hidden" name="folio" id="folio"
  value="<?php echo $this->input->get()['folio']; ?>">
<div class="container-fluid">
  <div class="row">
    <div class="hidden-lg-down col-2"
      style="background: rgba(255, 255, 255, 0.7)">
      <ul class="nav flex-column nav-pills" role="tablist"
        aria-orientation="vertical">
        <li class="nav-item"><a class="nav-link" href="#seccion1"
          aria-controls="seccion1" aria-selected="true">Datos generales</a></li>
        <li class="nav-item"><a class="nav-link" href="#seccion2"
          aria-controls="seccion2" aria-selected="false">Lavados</a></li>
        <li class="nav-item"><a class="nav-link" href="#seccion3"
          aria-controls="seccion3" aria-selected="false">Autorización</a></li>
        <li class="nav-item"><a class="nav-link" href="#seccion4"
          aria-controls="seccion4" aria-selected="false">Datos de
            autorización</a></li>
        <li class="nav-item"><a class="nav-link" href="#seccion5"
          aria-controls="seccion5" aria-selected="false">Salida interna</a></li>
        <li class="nav-item"><a class="nav-link" href="#seccion6"
          aria-controls="seccion6" aria-selected="false">Datos de salida
            interna</a></li>
        <li class="nav-item"><a class="nav-link" href="#seccion7"
          aria-controls="seccion7" aria-selected="false">Producción de
            p. s.</a></li>
        <li class="nav-item"><a class="nav-link" href="#seccion8"
          aria-controls="seccion8" aria-selected="false">Reprocesos</a></li>
        <li class="nav-item"><a class="nav-link" href="#seccion9"
          aria-controls="seccion9" aria-selected="false">Producción
            reprocesos</a></li>
      </ul>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-10">
      <div id="seccion1" style="background: rgba(255, 255, 255, .7)">
        <div class="card">
          <div class="card-header">
            <div class="mx-auto">
              <h3>Datos generales del corte.</h3>
            </div>
          </div>
          <div class="card-body" id="generales">
            <div class="table-responsive">
              <table class="table table-striped">
                <tbody>
                  <tr>
                    <td>Imágen</td>
                    <td><a href="#" data-toggle="modal"
                      data-target="#modalImagen"><?php echo $generales['imagen']; ?>
                      </a></td>
                  </tr>
                  <tr>
                    <td>Folio</td>
                    <td><?php echo $generales['folio']; ?></td>
                  </tr>
                  <tr>
                    <td>Corte</td>
                    <td><input placeholder="Escribe el corte"
                      name="corte" id="corte" type="text"
                      value="<?php echo $generales['corte']; ?>"
                      class="form-control" /></td>
                  </tr>
                  <tr>
                    <td>Marca</td>
                    <td><select class="form-control" name="marca_id"
                      id="marca_id">
                        <?php foreach ($marcas as $key => $value): ?><option
                          <?php echo ($value['id'] == $generales['marca_id'])? "selected" : "" ?>
                          value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php endforeach; ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td>Maquilero</td>
                    <td><select class="form-control" name="maquilero_id"
                      id="maquilero_id">
                        <?php foreach ($maquileros as $key => $value): ?><option
                          <?php echo ($value['id'] == $generales['maquilero_id'])? "selected" : "" ?>
                          value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php endforeach; ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td>Cliente</td>
                    <td><select class="form-control" name="cliente_id"
                      id="cliente_id">
                        <?php foreach ($clientes as $key => $value): ?><option
                          <?php echo ($value['id'] == $generales['cliente_id'])? "selected" : "" ?>
                          value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php endforeach; ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td>Tipo</td>
                    <td><select class="form-control"
                      name="tipo_pantalon_id" id="tipo_pantalon_id">
                        <?php foreach ($tipo as $key => $value): ?><option
                          <?php echo ($value['id'] == $generales['tipo_pantalon_id'])? "selected" : "" ?>
                          value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php endforeach; ?>
                      </select></td>
                  </tr>
                  <tr>
                    <td>Fecha de entrada</td>
                    <td><input placeholder="Escribe la fecha de entrada"
                      class="form-control" type="date"
                      value="<?php echo $generales['fecha_entrada'] ?>"
                      id="fecha_entrada" name="fecha_entrada"></td>
                  </tr>
                  <tr>
                    <td>Piezas</td>
                    <td><input placeholder="Escribe el número de piezas"
                      type="number" name="piezas" id="piezas"
                      class="form-control"
                      value="<?php echo $generales['piezas']; ?>"></td>
                  </tr>
                  <tr>
                    <td>Ojales</td>
                    <td><input placeholder="Escribe el número de ojales"
                      type="number" name="ojales" id="ojales"
                      class="form-control"
                      value="<?php echo $generales['ojales']; ?>"></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="ml-auto">
              <button type="button" name="botonGenerales"
                id="botonGenerales" class="btn btn-primary">
                <i class="fas fa-check"></i> Aceptar
              </button>
            </div>
          </div>
          <div class="card-footer text-muted"></div>
        </div>
      </div>
      <div id="seccion2" style="background: rgba(255, 255, 255, .7)">
        <div class="card">
          <div class="card-header">
            <div class="mx-auto">
              <h3>Lavados del corte.</h3>
            </div>
          </div>
          <div class="card-body" id="lavados">
            <div class="table-responsive">
              <?php if ($lavadosCorte != 0): ?>
                <table class="table table-striped">
                <thead>
                  <tr>
                    <th># Carga</th>
                    <th>Nombre del lavado</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                  </tr>
                </thead>
                <tbody><?php foreach ($lavadosCorte as $key => $value): ?>
                    <tr>
                    <td><?php echo $value['id_carga']; ?></td>
                    <td><select class="form-control"
                      id="lavado_<?php echo $value['id_carga']; ?>"
                      name="lavado_<?php echo $value['id_carga']; ?>">
                          <?php foreach ($lavados as $key2 => $value2): ?><option
                          <?php echo ($value2['id'] == $value['lavado_id'])? "selected": "" ?>
                          value="<?php echo $value2['id'] ?>"><?php echo $value2['nombre'] ?></option><?php endforeach; ?>
                        </select></td>
                    <td><button type="button" class="btn btn-warning"
                        onclick="editarLavado(<?php echo $value['id_carga'] ?>)">
                        <i class="far fa-edit"></i>
                      </button></td>
                    <td><button type="button" class="btn btn-danger"
                        onclick="eliminarLavado(<?php echo $value['id_carga'] ?>)">
                        <i class="far fa-trash-alt"></i>
                      </button></td>
                  </tr><?php endforeach; ?>
                  </tbody>
              </table>
              <?php else: ?>
                <div class="alert alert-danger" role="alert">No hay
                lavados para este corte.</div>
              <?php endif; ?>
              <div class="ml-auto">
                <button type="button" name="botonAgregarLavado"
                  id="botonAgregarLavado" class="btn btn-success"
                  data-toggle="modal" data-target="#agregarLavado">
                  <i class="fas fa-plus"></i> Nuevo
                </button>
              </div>
            </div>
          </div>
          <div class="card-footer text-muted"></div>
        </div>
      </div>
      <div id="seccion3" style="background: rgba(255, 255, 255, .7)">
        <div class="card">
          <div class="card-header">
            <div class="mx-auto">
              <h3>Datos generales de autorización de corte.</h3>
            </div>
          </div>
          <div class="card-body" id="generalesAutorizacion">
            <?php if ($autorizado != 0): ?>
              <div class="table-responsive">
              <table class="table table-striped">
                <tbody>
                  <tr>
                    <td>Fecha de autorización</td>
                    <td><input placeholder="Escribe la fecha"
                      class="form-control" type="date"
                      value="<?php echo $autorizado['fecha_autorizado'] ?>"
                      id="fecha_autorizado" name="fecha_autorizado"></td>
                  </tr>
                  <tr>
                    <td>Cargas</td>
                    <td><?php echo $autorizado['cargas'] ?></td>
                  </tr>
                  <tr>
                    <td>Usuario que autorizó</td>
                    <td><select class="form-control"
                      name="usuarioAutorizo" id="usuarioAutorizo">
                          <?php foreach ($usuarios as $key => $value): ?><option
                          <?php echo ($value['id'] == $autorizado['usuario_id'])? "selected" : "" ?>
                          value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php endforeach; ?>
                        </select></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="ml-auto">
              <button type="button" class="btn btn-primary"
                name="botonAutorizacion" id="botonAutorizacion">
                <i class="fas fa-check"></i> Aceptar
              </button>
              <button type="button" class="btn btn-danger"
                name="botonAutorizacionEliminar"
                id="botonAutorizacionEliminar">
                <i class="far fa-trash-alt"></i> Eliminar
              </button>
            </div>
            <?php else: ?>
              <div class="alert alert-danger" role="alert">No hay datos
              de autorización de este corte.</div>
            <?php endif; ?>
          </div>
          <div class="card-footer text-muted"></div>
        </div>
      </div>
      <div id="seccion4" style="background: rgba(255, 255, 255, .7)">
        <div class="card">
          <div class="card-header">
            <div class="mx-auto">
              <h3>Datos específicos de autorización de corte.</h3>
            </div>
          </div>
          <div class="card-body">
            <?php if ($autorizadoDatos != 0): ?>
              <div class="table-responsive">
              <table class="table table-striped"
                id="tblespecificosAutorizacion">
                <thead>
                  <tr>
                    <th># Carga</th>
                    <th>Lavado</th>
                    <th>Proceso Seco</th>
                    <th>Estatus</th>
                    <th>Fecha de registro</th>
                    <th>Usuario que registró</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                  </tr>
                </thead>
                <tbody><?php foreach ($autorizadoDatos as $key => $value): ?>
                    <tr>
                    <td><input type="hidden"
                      name="id_carga_autorizado_datos_anterior_<?php echo $value['id'] ?>"
                      id="id_carga_autorizado_datos_anterior_<?php echo $value['id'] ?>"
                      value="<?php echo $value['id_carga'] ?>"> <input
                      type="hidden"
                      name="id_lavado_autorizado_datos_anterior_<?php echo $value['id'] ?>"
                      id="id_lavado_autorizado_datos_anterior_<?php echo $value['id'] ?>"
                      value="<?php echo $value['lavado_id'] ?>"> <input
                      type="hidden"
                      name="id_proceso_seco_id_autorizado_datos_anterior_<?php echo $value['id'] ?>"
                      id="id_proceso_seco_id_autorizado_datos_anterior_<?php echo $value['id'] ?>"
                      value="<?php echo $value['proceso_seco_id'] ?>"> <input
                      type="hidden"
                      name="costo_autorizado_datos_<?php echo $value['id']; ?>"
                      id="costo_autorizado_datos_<?php echo $value['id']; ?>"
                      value="<?php echo $value['costo']; ?>"> <input
                      type="hidden"
                      name="piezas_trabajadas_autorizado_datos_<?php echo $value['id']; ?>"
                      id="piezas_trabajadas_autorizado_datos_<?php echo $value['id']; ?>"
                      value="<?php echo $value['piezas_trabajadas']; ?>">
                      <input type="hidden"
                      name="defectos_autorizado_datos_<?php echo $value['id']; ?>"
                      id="defectos_autorizado_datos_<?php echo $value['id']; ?>"
                      value="<?php echo $value['defectos']; ?>"> <input
                      type="hidden"
                      name="status_autorizado_datos_<?php echo $value['id']; ?>"
                      id="status_autorizado_datos_<?php echo $value['id']; ?>"
                      value="<?php echo $value['status']; ?>"> <input
                      type="hidden"
                      name="fecha_registro_autorizado_datos_<?php echo $value['id']; ?>"
                      id="fecha_registro_autorizado_datos_<?php echo $value['id']; ?>"
                      value="<?php echo $value['fecha_registro']; ?>"> <input
                      type="hidden"
                      name="orden_autorizado_datos_<?php echo $value['id']; ?>"
                      id="orden_autorizado_datos_<?php echo $value['id']; ?>"
                      value="<?php echo $value['orden']; ?>"> <input
                      type="hidden"
                      name="usuario_id_autorizado_datos_<?php echo $value['id']; ?>"
                      id="usuario_id_autorizado_datos_<?php echo $value['id']; ?>"
                      value="<?php echo $value['usuario_id']; ?>">
                        <?php echo $value['id_carga'] ?>
                      </td>
                    <td>
                        <?php foreach ($lavados as $key2 => $value2):  if ($value2['id'] == $value['lavado_id']): echo $value2['nombre']; break; endif;  endforeach; ?>
                      </td>
                    <td>
                        <?php foreach ($procesosecos as $key2 => $value2):  if ($value2['id'] == $value['proceso_seco_id']): echo $value2['nombre']; break; endif; endforeach; ?>
                      </td>
                    <td>
                        <?php
                    
                    switch ($value['status'])
                    {
                        case 0:
                            echo "No registrado";
                            break;
                        case 1:
                            echo "Para registrar";
                            break;
                        case 2:
                            echo "Registrado";
                            break;
                    }
                    ?>
                      </td>
                    <td><?php echo $value['fecha_registro'] ?></td>
                    <td>
                        <?php foreach ($usuarios as $key2 => $value2):  if ($value2['id'] == $value['usuario_id']): echo $value2['nombre']; break;  endif; endforeach; ?>
                      </td>
                    <td><button type="button" class="btn btn-warning"
                        onclick="editarAutorizadoDatos(<?php echo $value['id']; ?>);">
                        <i class="far fa-edit"></i>
                      </button></td>
                    <td><button type="button" class="btn btn-danger"
                        onclick="eliminarAutorizadoDatos(<?php echo $value['id']; ?>);">
                        <i class="far fa-trash-alt"></i>
                      </button></td>
                  </tr><?php endforeach; ?>
                  </tbody>
              </table>
            </div>
            <?php else: ?>
              <div class="alert alert-danger" role="alert">No hay datos
              de autorización de este corte.</div>
            <?php endif; ?>
          </div>
          <div class="card-footer text-muted"></div>
        </div>
      </div>
      <div id="seccion5" style="background: rgba(255, 255, 255, .7)">
        <div class="card">
          <div class="card-header">
            <div class="mx-auto">
              <h3>Datos generales de salida interna.</h3>
            </div>
          </div>
          <div class="card-body">
            <?php if ($salidaInterna != 0): ?>
              <div class="table-responsive">
              <table class="table table-striped">
                <tbody>
                  <tr>
                    <td>Fecha de salida interna</td>
                    <td><input placeholder="Escribe la fecha"
                      class="form-control" type="date"
                      value="<?php echo $salidaInterna['fecha'] ?>"
                      id="fechaSalidaInterna" name="fechaSalidaInterna"></td>
                  </tr>
                  <tr>
                    <td>Muestras</td>
                    <td><input
                      placeholder="Escribe el número de muestras"
                      type="number" name="muestrasSalidaInterna"
                      id="muestrasSalidaInterna" class="form-control"
                      value="<?php echo $salidaInterna['muestras'] ?>"></td>
                  </tr>
                  <tr>
                    <td>Usuario que dio la salida interna</td>
                    <td><select class="form-control"
                      name="usuarioSalidaInterna"
                      id="usuarioSalidaInterna">
                          <?php foreach ($usuarios as $key => $value): ?><option
                          <?php echo ($value['id'] == $salidaInterna['usuario_id'])? "selected" : "" ?>
                          value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php endforeach; ?>
                        </select></td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="ml-auto">
              <button type="button" class="btn btn-primary"
                name="botonSalidaInterna" id="botonSalidaInterna">
                <i class="fas fa-check"></i> Aceptar
              </button>
              <button type="button" class="btn btn-danger"
                name="botonSalidaInternaEliminar"
                id="botonSalidaInternaEliminar">
                <i class="far fa-trash-alt"></i> Eliminar
              </button>
            </div>
            <?php else: ?>
              <div class="alert alert-danger" role="alert">No hay datos
              de salida interna de este corte.</div>
            <?php endif; ?>
          </div>
          <div class="card-footer text-muted"></div>
        </div>
      </div>
      <div id="seccion6" style="background: rgba(255, 255, 255, .7)">
        <div class="card">
          <div class="card-header">
            <div class="mx-auto">
              <h3>Datos específicos de salida interna.</h3>
            </div>
          </div>
          <div class="card-body">
            <?php if ($salidaInternaDatos != 0): ?>
              <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th># Carga</th>
                    <th>Proceso</th>
                    <th>Piezas</th>
                    <th>Editar</th>
                  </tr>
                </thead>
                <tbody><?php foreach ($salidaInternaDatos as $key => $value): ?>
                    <tr>
                    <td><input type="hidden"
                      name="IdLavadoSalidaInternaDatos<?php echo $value['id_carga']; ?>"
                      id="IdLavadoSalidaInternaDatos<?php echo $value['id_carga']; ?>"
                      value="<?php echo $value['lavado_id']; ?>">
                        <?php echo $value['id_carga']; ?>
                      </td>
                    <td>
                        <?php
                    foreach ($lavados as $key2 => $value2)
                    {
                        if ($value2['id'] == $value['lavado_id'])
                            echo $value2['nombre'];
                    }
                    ?>
                      </td>
                    <td><input placeholder="Inserta número de piezas"
                      class="form-control" type="number"
                      id="piezasSalidaInternaDatos<?php echo $value['id_carga']; ?>"
                      name="piezasSalidaInternaDatos<?php echo $value['id_carga']; ?>"
                      value="<?php echo $value['piezas'] ?>"></td>
                    <td><button type="button" class="btn btn-warning"
                        onclick="editarSalidaInternaDatos(<?php echo $value['id_carga']; ?>)">
                        <i class="far fa-edit"></i>
                      </button></td>
                  </tr><?php endforeach; ?>
                  </tbody>
              </table>
            </div>
            <?php else: ?>
              <div class="alert alert-danger" role="alert">No hay datos
              de salida interna de este corte.</div>
            <?php endif; ?>
          </div>
          <div class="card-footer text-muted"></div>
        </div>
      </div>
      <div id="seccion7" style="background: rgba(255, 255, 255, .7)">
        <div class="card">
          <div class="card-header">
            <div class="mx-auto">
              <h3>Datos de producción de proceso seco.</h3>
            </div>
          </div>
          <div class="card-body" id="produccion">
            <?php if ($produccionProcesoSeco != 0): ?>
              <div class="table-responsive">
              <table class="table table-striped" id="tablaProduccion">
                <thead>
                  <tr>
                    <th>Operario</th>
                    <th>Lavado</th>
                    <th>Proceso</th>
                    <th>Piezas trabajadas</th>
                    <th>Costo</th>
                    <th>Total</th>
                    <th>Defectos</th>
                    <th>Fecha</th>
                    <th>¿Se pagó?</th>
                    <th>Razón</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                  </tr>
                </thead>
                <tbody><?php foreach ($produccionProcesoSeco as $key => $value): ?>
                    <tr>
                    <td><?php echo $value['nombre_completo'] ?></td>
                    <td><?php echo $value['lavado'] ?></td>
                    <td><?php echo $value['proceso'] ?></td>
                    <td><input class="form-control"
                      placeholder="Escribe el número de piezas"
                      type="number"
                      id="piezasProduccion<?php echo $value['id']; ?>"
                      name="piezasProduccion<?php echo $value['id']; ?>"
                      value="<?php echo $value['piezas'] ?>"></td>
                    <td>$<?php echo $value['costo'] ?></td>
                    <td>$<?php echo $value['total'] ?></td>
                    <td><input class="form-control" type="number"
                      id="defectosProduccion<?php echo $value['id'] ?>"
                      name="defectosProduccion<?php echo $value['id'] ?>"
                      value="<?php echo $value['defectos'] ?>"></td>
                    <td><?php echo $value['fecha'] ?></td>
                    <td><select class="form-control"
                      name="estadoNomina<?php echo $value['id']; ?>"
                      id="estadoNomina<?php echo $value['id']; ?>">
                        <option value="0"
                          <?php echo ($value['estado_nomina'] == 0)? "selected":"" ?>>No
                          se ha pagado</option>
                        <option value="1"
                          <?php echo ($value['estado_nomina'] == 1)? "selected":"" ?>>Se
                          pagó</option>
                        <option value="2"
                          <?php echo ($value['estado_nomina'] == 2)? "selected":"" ?>>Se
                          pagará después</option>
                        <option value="3"
                          <?php echo ($value['estado_nomina'] == 3)? "selected":"" ?>>No
                          se pagará nunca</option>
                    </select></td>
                    <td><textarea class="form-control"
                        id="razonProduccion<?php echo $value['id']; ?>"
                        name="razonProduccion<?php echo $value['id']; ?>"><?php echo $value['razon_pagar']; ?></textarea>
                    </td>
                    <td><button type="button" class="btn btn-warning"
                        onclick="editarProduccion(<?php echo $value['id']; ?>);">
                        <i class="far fa-edit"></i>
                      </button></td>
                    <td><button type="button" class="btn btn-danger"
                        onclick="eliminarProduccion(<?php echo $value['id']; ?>);">
                        <i class="far fa-trash-alt"></i>
                      </button></td>
                  </tr><?php endforeach; ?>
                  </tbody>
              </table>
            </div>
            <?php else: ?>
              <div class="alert alert-danger" role="alert">No hay datos
              de producción de reprocesos de este corte.</div>
            <?php endif; ?>
          </div>
          <div class="card-footer text-muted"></div>
        </div>
      </div>
      <div id="seccion8" style="background: rgba(255, 255, 255, .7)">
        <div class="card">
          <div class="card-header">
            <div class="mx-auto">
              <h3>Reprocesos.</h3>
            </div>
          </div>
          <div class="card-body">
            <?php if ($reprocesos != 0): ?>
              <div class="table-responsive">
              <table class="table table-striped" id="tablaReprocesos">
                <thead>
                  <tr>
                    <th>Lavado</th>
                    <th>Reproceso</th>
                    <th>Estatus</th>
                    <th>Fecha de registro</th>
                    <th>Usuario que registró</th>
                    <th>Costo</th>
                    <th>Piezas trabajadas o por trabajar</th>
                    <th>Defectos Registrados</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                  </tr>
                </thead>
                <tbody><?php foreach ($reprocesos as $key => $value): ?>
                    <tr>
                    <td><select class="form-control"
                      name="lavadoReproceso<?php echo $value['id']; ?>"
                      id="lavadoReproceso<?php echo $value['id']; ?>">
                          <?php foreach ($lavados as $key2 => $value2): ?><option
                          <?php echo ($value['lavado_id'] == $value2['id'])? "selected" : "" ?>
                          value="<?php echo $value2['id'] ?>"><?php echo $value2['nombre'] ?></option><?php endforeach; ?>
                        </select></td>
                    <td><select class="form-control"
                      name="procesoReproceso<?php echo $value['id']; ?>"
                      id="procesoReproceso<?php echo $value['id']; ?>">
                          <?php foreach ($procesosecos as $key2 => $value2): ?><option
                          <?php echo ($value2['id'] == $value['proceso_seco_id'])? "selected" : ""; ?>
                          value="<?php echo $value2['id'] ?>"><?php echo $value2['nombre'] ?></option><?php endforeach; ?>
                        </select></td>
                    <td><select class="form-control"
                      name="estatusReproceso<?php echo $value['id']; ?>"
                      id="estatusReproceso<?php echo $value['id']; ?>">
                        <option
                          <?php echo ($value['status'] == 0)? "selected":"" ?>
                          value="0">No registrado</option>
                        <option
                          <?php echo ($value['status'] == 2)? "selected":"" ?>
                          value="2">Registrado</option>
                    </select></td>
                    <td>
                        <?php echo $value['fecha_registro'] ?>
                      </td>
                    <td><?php echo $value['usuario_nombre'] ?></td>
                    <td><input class="form-control" type="number"
                      step="any"
                      name="costoReproceso<?php echo $value['id']; ?>"
                      id="costoReproceso<?php echo $value['id']; ?>"
                      value="<?php echo $value['costo'] ?>"></td>
                    <td><input class="form-control" type="number"
                      name="piezasReproceso<?php echo $value['id']; ?>"
                      id="piezasReproceso<?php echo $value['id']; ?>"
                      value="<?php echo $value['piezas_trabajadas'] ?>"></td>
                    <td><input class="form-control" type="number"
                      name="defectosReproceso<?php echo $value['id']; ?>"
                      id="defectosReproceso<?php echo $value['id']; ?>"
                      value="<?php echo $value['defectos'] ?>"></td>
                    <td>
                      <button type="button"
                        onclick="editarReproceso(<?php echo $value['id'] ?>)"
                        class="btn btn-warning">
                        <i class="far fa-edit"></i>
                      </button>
                    </td>
                    <td>
                      <button type="button"
                        onclick="eliminarReproceso(<?php echo $value['id'] ?>)"
                        class="btn btn-danger">
                        <i class="far fa-trash-alt"></i>
                      </button>
                    </td>
                  </tr><?php endforeach; ?>
                  </tbody>
              </table>
            </div>
            <?php else: ?>
              <div class="alert alert-danger" role="alert">No hay
              reprocesos de este corte.</div>
            <?php endif; ?>
          </div>
          <div class="card-footer text-muted"></div>
        </div>
      </div>
      <div id="seccion9" style="background: rgba(255, 255, 255, .7)">
        <div class="card">
          <div class="card-header">
            <div class="mx-auto">
              <h3>Datos de producción de reprocesos.</h3>
            </div>
          </div>
          <div class="card-body">
            <?php if ($produccionReprocesos != 0): ?>
              <div class="table-responsive">
              <table class="table table-striped"
                id="tablaProduccionReprocesos">
                <thead>
                  <tr>
                    <th>Operario</th>
                    <th>Lavado</th>
                    <th>Proceso</th>
                    <th>Piezas trabajadas</th>
                    <th>Costo</th>
                    <th>Total</th>
                    <th>Defectos</th>
                    <th>Fecha</th>
                    <th>¿Se pagó?</th>
                    <th>Razón</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                  </tr>
                </thead>
                <tbody><?php foreach ($produccionReprocesos as $key => $value): ?>
                    <tr>
                    <td><?php echo $value['usuario_nombre'] ?></td>
                    <td><?php echo $value['lavado_nombre'] ?></td>
                    <td><?php echo $value['proceso'] ?></td>
                    <td><input class="form-control"
                      placeholder="Escribe el número de piezas"
                      type="number"
                      id="piezasProduccionReproceso<?php echo $value['id']; ?>"
                      name="piezasProduccionReproceso<?php echo $value['id']; ?>"
                      value="<?php echo $value['piezas'] ?>"></td>
                    <td>$<?php echo $value['costo'] ?></td>
                    <td>$<?php echo $value['total'] ?></td>
                    <td><input class="form-control" type="number"
                      id="defectosProduccionReproceso<?php echo $value['id'] ?>"
                      name="defectosProduccionReproceso<?php echo $value['id'] ?>"
                      value="<?php echo $value['defectos'] ?>"></td>
                    <td><?php echo $value['fecha'] ?></td>
                    <td><select class="form-control"
                      name="estadoNominaReproceso<?php echo $value['id']; ?>"
                      id="estadoNominaReproceso<?php echo $value['id']; ?>">
                        <option value="0"
                          <?php echo ($value['estado_nomina'] == 0)? "selected":"" ?>>No
                          se ha pagado</option>
                        <option value="1"
                          <?php echo ($value['estado_nomina'] == 1)? "selected":"" ?>>Se
                          pagó</option>
                        <option value="2"
                          <?php echo ($value['estado_nomina'] == 2)? "selected":"" ?>>Se
                          pagará después</option>
                        <option value="3"
                          <?php echo ($value['estado_nomina'] == 3)? "selected":"" ?>>No
                          se pagará nunca</option>
                    </select></td>
                    <td><textarea class="form-control"
                        id="razonProduccionReproceso<?php echo $value['id']; ?>"
                        name="razonProduccionReproceso<?php echo $value['id']; ?>"><?php echo $value['razon_pagar']; ?></textarea>
                    </td>
                    <td><button type="button" class="btn btn-warning"
                        onclick="editarProduccionReproceso(<?php echo $value['id']; ?>);">
                        <i class="far fa-edit"></i>
                      </button></td>
                    <td><button type="button" class="btn btn-danger"
                        onclick="eliminarProduccionReproceso(<?php echo $value['id']; ?>);">
                        <i class="far fa-trash-alt"></i>
                      </button></td>
                  </tr><?php endforeach; ?>
                  </tbody>
              </table>
            </div>
            <?php else: ?>
              <div class="alert alert-danger" role="alert">No hay datos
              de producción de reprocesos de este corte.</div>
            <?php endif; ?>
          </div>
          <div class="card-footer text-muted"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalImagen" tabindex="-1" role="dialog"
  aria-labelledby="modalImagen" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modificar imágen
          actual</h5>
        <button type="button" class="close" data-dismiss="modal"
          aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form name="cambiarImagen" id="cambiarImagen"
        action="<?php echo base_url('index.php/administracion/modificarImagen') ?>"
        method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <ul>
            <li class="list-group-item"><?php echo $generales['imagen'] ?></li>
            <li class="list-group-item">
              <h6>Imágen nueva</h6>
            <?php echo form_input($input_imagen); ?>
            <input type="hidden" name="folioCambiarImagen"
              id="folioCambiarImagen"
              value="<?php echo $this->input->get()['folio'] ?>">
            </li>
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"
            data-dismiss="modal">
            <i class="fas fa-window-close"></i> Cerrar
          </button>
          <button type="button" id="botonModalImagen"
            name="botonModalImagen" class="btn btn-primary">
            <i class="fas fa-check"></i> Aceptar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php if ($autorizadoDatos != 0): ?>
<div class="modal fade" id="modalCorteAutorizadoDatos" tabindex="-1"
  role="dialog" aria-labelledby="exampleModalLongTitle"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modificar
          datos del corte autorizado</h5>
        <button type="button" class="close" data-dismiss="modal"
          aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id_autorizado_datos"
          id="id_autorizado_datos">
        <div class="table-responsive">
          <table class="table table-striped">
            <tbody>
              <tr>
                <td>Número de carga</td>
                <td><input readonly placeholder="Escribe la id de carga"
                  type="number" class="form-control"
                  name="id_carga_autorizado_datos"
                  id="id_carga_autorizado_datos"></td>
              </tr>
              <tr>
                <td>Lavado</td>
                <td><select disabled class="form-control"
                  name="lavado_id_autorizado_datos"
                  id="lavado_id_autorizado_datos">
                      <?php foreach ($lavados as $key2 => $value2): ?><option
                      value="<?php echo $value2['id'] ?>"><?php echo $value2['nombre'] ?></option> <?php endforeach; ?>
                    </select></td>
              </tr>
              <tr>
                <td>Proceso Seco</td>
                <td><select class="form-control"
                  name="proceso_id_autorizado_datos"
                  id="proceso_id_autorizado_datos">
                      <?php foreach ($procesosecos as $key2 => $value2): ?><option
                      value="<?php echo $value2['id'] ?>"><?php echo $value2['nombre'] ?></option><?php endforeach; ?>
                    </select></td>
              </tr>
              <tr>
                <td>Costo</td>
                <td><input placeholder="Escribe el costo" type="number"
                  class="form-control" step="any"
                  name="autorizado_datos_costo"
                  id="autorizado_datos_costo"></td>
              </tr>
              <tr>
                <td>Piezas trabajadas o por trabajar</td>
                <td><input placeholder="Escribe el número de piezas"
                  type="number" class="form-control"
                  name="autorizado_datos_piezas_trabajadas"
                  id="autorizado_datos_piezas_trabajadas"></td>
              </tr>
              <tr>
                <td>Defectos</td>
                <td><input placeholder="Escribe el número de defectos"
                  type="number" class="form-control"
                  name="autorizado_datos_defectos"
                  id="autorizado_datos_defectos"></td>
              </tr>
              <tr>
                <td>Estatus</td>
                <td><select class="form-control"
                  name="autorizado_datos_estatus"
                  id="autorizado_datos_estatus">
                    <option value="0">No registrado</option>
                    <option value="1">Para registrar</option>
                    <option value="2">Registrado</option>
                </select></td>
              </tr>
              <tr>
                <td>Orden</td>
                <td><input placeholder="Escribe el orden numérico"
                  class="form-control" type="number"
                  name="autorizado_datos_orden"
                  id="autorizado_datos_orden"></td>
              </tr>
              <tr>
                <td>Fecha de registro</td>
                <td><input placeholder="Escribe la fecha"
                  class="form-control" type="date"
                  value="<?php echo date("Y-m-d") ?>"
                  name="autorizado_datos_fecha_registro_"
                  id="autorizado_datos_fecha_registro_"></td>
              </tr>
              <tr>
                <td>Usuario que registró</td>
                <td><select class="form-control"
                  name="autorizado_datos_usuario_id"
                  id="autorizado_datos_usuario_id">
                      <?php foreach ($usuarios as $key2 => $value2): ?><option
                      value="<?php echo $value2['id'] ?>"><?php echo $value2['nombre'] ?></option><?php endforeach; ?>
                    </select></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary"
          data-dismiss="modal">
          <i class="fas fa-window-close"></i> Cerrar
        </button>
        <button type="button" class="btn btn-primary"
          name="editarDatosCorteAutorizado"
          id="editarDatosCorteAutorizado">
          <i class="fas fa-check"></i> Aceptar
        </button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<div class="modal fade" id="agregarLavado" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Agregar
          lavado nuevo.</h5>
        <button type="button" class="close" data-dismiss="modal"
          aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formAgregarLavado" name="formAgregarLavado"
        action="agregarLavado" method="post">
        <input type="hidden" id="corteFolioNuevoLavado"
          name="corteFolioNuevoLavado"
          value="<?php echo $this->input->get()['folio']; ?>">
        <div class="modal-body">
          <div role="tabpanel">
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="nav-item"><a
                class="nav-link active" href="#seleccionarLavado"
                aria-controls="seleccionarLavado" role="tab"
                data-toggle="tab">Nuevo lavado</a></li>
              <li role="presentation" class="nav-item"><a
                class="nav-link" href="#autorizarLavado"
                aria-controls="autorizarLavado" role="tab"
                data-toggle="tab">Autorizar lavado</a></li>
              <?php if ($salidaInterna != 0): ?>
                <li role="presentation" class="nav-item"><a
                class="nav-link" href="#salidaInterna"
                aria-controls="salidaInterna" role="tab"
                data-toggle="tab" id="botonSalidaInternaNuevoLavado">Salida
                  Interna</a></li>
              <?php endif; ?>
            </ul>
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active"
                id="seleccionarLavado">
                <div class="card">
                  <div class="card-header">Seleccionar el lavado</div>
                  <div class="card-body">
                    <select class="form-control" id="lavadoProcesoNuevo"
                      name="lavadoProcesoNuevo">
                      <?php foreach ($lavados as $key => $value): ?><option
                        value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
              <div role="tabpanel" class="tab-pane" id="autorizarLavado">
                <div class="card">
                  <div class="card-header">Agrega los procesos.</div>
                  <div class="card-body" id="cuerpoAutorizarLavado">
                    <div class="form-group row">
                      <div class="col-12">
                        <table id="tablaProcesos" class="table">
                          <thead>
                            <tr>
                              <th>Proceso Seco</th>
                              <th>Eliminar</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="mx-auto">
                      <button type="button" name="botonAgregarProcesos"
                        id="botonAgregarProcesos"
                        class="btn btn-success">
                        <i class="fas fa-plus"></i> Agregar Proceso
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <?php if ($salidaInterna != 0): ?>
                <div role="tabpanel" class="tab-pane" id="salidaInterna">
                <div class="card">
                  <div class="card-header">Salida interna al lavado.</div>
                  <div class="card-body" id="cuerpoSalidaInterna">
                    <div class="form-group row">
                      <div class="col-12">
                        <table class="table">
                          <tbody>
                            <tr>
                              <td># Piezas para el lavado</td>
                              <td><input value="0" class="form-control"
                                required
                                placeholder="Escribe el número de piezas"
                                type="number" name="piezasLavadoNuevo"
                                id="piezasLavadoNuevo"></td>
                            </tr>
                            <tr>
                              <td>Abrir con el proceso</td>
                              <td><select class="form-control"
                                name="abrirConProceso"
                                id="abrirConProceso">
                                    <?php foreach ($procesosecos as $key => $value): ?><option
                                    value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option><?php endforeach; ?>
                                  </select></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary"
            data-dismiss="modal">
            <i class="fas fa-window-close"></i> Cerrar
          </button>
          <button type="submit" class="btn btn-primary"
            name="editarDatosCorteAutorizado"
            id="editarDatosCorteAutorizado">
            <i class="fas fa-check"></i> Aceptar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
