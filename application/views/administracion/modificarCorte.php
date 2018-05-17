<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$input_imagen = array(
  'name' => 'mi_imagen',
  'id' => 'mi_imagen',
  'type' => 'file',
  'class' => 'form-control-file',
);
?>
<script type="text/javascript">
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
        if (res.respuesta)
        location.reload();
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
        if (res.respuesta)
        location.reload();
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
        if (res.respuesta)
        location.reload();
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
  if ($('#piezasSalidaInternaDatos'+id_carga).val() == '')
  alert("Existen campos en blanco, favor de revisar.");
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
          if (res.respuesta)
          location.reload();
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
  if ($('#piezasProduccion'+id).val() == '' || $('#defectosProduccion'+id).val() == '')
    alert("Existen campos vacíos, favor de revisar");
    else {
      if (confirm("¿Está seguro de cambiar los datos de este registro?, se verá reflejado en la nómina de los operarios.")) {
        $.ajax({
          type: "POST",
          url: "editarProduccion",
          data: {
            id: id,
            piezas: $('#piezasProduccion'+id).val(),
            defectos: $('#defectosProduccion'+id).val(),
            fecha: $('#fechaProduccion'+id).val(),
          },
          success: function(res) {
            if (res.respuesta)
              location.reload();
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
        if (res.respuesta)
          location.reload();
      },
      dataType: "json",
      error: function (request, status, error) {
        console.log(request.responseText);
      }
    });
  }
}
<?php endif; ?>
$(document).ready(function() {
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
            if (res.respuesta)
            location.reload();
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
    if ($('#folio').val() == '' || $('#mi_imagen').val() == '')
    alert("Existen campos vacíos, favor de revisar.");
    else {
      if (confirm('¿Está seguro que desea cambiar la imágen del corte?'))
      $('#cambiarImagen').submit();
    }
  });
  <?php if ($autorizado != 0): //Funciones para el corte autorizado?>
  $('#botonAutorizacion').click(function() {
    if ($('#folio').val() == '' || $('#fecha_autorizado').val() == '' || $('#usuarioAutorizo').val() == '')
    alert("Existen campos vacíos, favor de revisar.");
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
            if (res.respuesta)
            location.reload();
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
          if (res.respuesta)
          location.reload();
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
    if ($('#proceso_id_autorizado_datos').val() == '' || $('#autorizado_datos_costo').val() == '' || $('#autorizado_datos_piezas_trabajadas').val() == '' || $('#autorizado_datos_defectos').val() == '' || $('#autorizado_datos_estatus').val() == '' || $('#autorizado_datos_orden').val() == '' || $('#autorizado_datos_fecha_registro_').val() == '' || $('#autorizado_datos_usuario_id').val() == '')
    alert("Existen campos vacíos, favor de revisar.")
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
          if (res.respuesta)
          location.reload();
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
    if ($('#fechaSalidaInterna').val() == '' || $('#muestrasSalidaInterna').val() == '' || $('#usuarioSalidaInterna').val() == '')
    alert("Existen campos vacíos, favor de revisar.")
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
            if (res.respuesta)
            location.reload();
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
          if (res.respuesta)
          location.reload();
        },
        dataType: "json",
        error: function (request, status, error) {
          console.log(request.responseText);
        }
      });
    }
  });
  <?php endif; ?>
});
</script>
<input type="hidden" name="folio" id="folio" value="<?php echo $this->input->get()['folio']; ?>">
<div class="container">
  <div class="row">
    <div class="col-12">
      <h3 class="white">Modificar el corte con folio <?php echo $this->input->get()['folio']; ?></h3>
      <div class="card">
        <a data-toggle="collapse" role="button" href="#generales" aria-expanded="true" aria-controls="generales">
          <div class="card-header">
            Datos generales del corte.
          </div>
        </a>
        <div class="card-body" id="generales" name="generales">
          <div class="table-responsive">
            <table class="table table-striped">
              <tbody>
                <tr>
                  <td>Imágen</td>
                  <td>
                    <a href="#" data-toggle="modal" data-target="#modalImagen"><?php echo $generales['imagen']; ?>
                    </a>
                  </td>
                </tr>
                <tr>
                  <td>Folio</td>
                  <td><?php echo $generales['folio']; ?></td>
                </tr>
                <tr>
                  <td>Corte</td>
                  <td>
                    <input placeholder="Escribe el corte" name="corte" id="corte" type="text" value="<?php echo $generales['corte']; ?>" class="form-control" />
                  </td>
                </tr>
                <tr>
                  <td>Marca</td>
                  <td>
                    <select class="form-control" name="marca_id" id="marca_id">
                      <?php foreach ($marcas as $key => $value): ?>
                        <?php if ($value['id'] == $generales['marca_id']): ?>
                          <option selected value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                        <?php else: ?>
                          <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Maquilero</td>
                  <td>
                    <select class="form-control" name="maquilero_id" id="maquilero_id">
                      <?php foreach ($maquileros as $key => $value): ?>
                        <?php if ($value['id'] == $generales['maquilero_id']): ?>
                          <option selected value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                        <?php else: ?>
                          <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Cliente</td>
                  <td>
                    <select class="form-control" name="cliente_id" id="cliente_id">
                      <?php foreach ($clientes as $key => $value): ?>
                        <?php if ($value['id'] == $generales['cliente_id']): ?>
                          <option selected value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                        <?php else: ?>
                          <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Tipo</td>
                  <td>
                    <select class="form-control" name="tipo_pantalon_id" id="tipo_pantalon_id">
                      <?php foreach ($tipo as $key => $value): ?>
                        <?php if ($value['id'] == $generales['tipo_pantalon_id']): ?>
                          <option selected value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                        <?php else: ?>
                          <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Fecha de entrada</td>
                  <td>
                    <input placeholder="Escribe la fecha de entrada" class="form-control" type="date" value="<?php echo $generales['fecha_entrada'] ?>" id="fecha_entrada" name="fecha_entrada">
                  </td>
                </tr>
                <tr>
                  <td>Piezas</td>
                  <td>
                    <input placeholder="Escribe el número de piezas" type="number" name="piezas" id="piezas" class="form-control" value="<?php echo $generales['piezas']; ?>">
                  </td>
                </tr>
                <tr>
                  <td>Ojales</td>
                  <td>
                    <input placeholder="Escribe el número de ojales" type="number" name="ojales" id="ojales" class="form-control" value="<?php echo $generales['ojales']; ?>">
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <button type="button" name="botonGenerales" id="botonGenerales" class="btn btn-primary"><i class="fas fa-check"></i> Aceptar</button>
        </div>
        <div class="card-footer text-muted">
        </div>
      </div>
      <div class="card">
        <a data-toggle="collapse" role="button" href="#lavados" aria-expanded="true" aria-controls="lavados">
          <div class="card-header">
            Lavados del corte.
          </div>
        </a>
        <div class="card-body" id="lavados" name="generales">
          <div class="table-responsive">
            <?php if ($lavadosCorte !=0): ?>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th># Carga</th>
                    <th>Nombre del lavado</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($lavadosCorte as $key => $value): ?>
                    <tr>
                      <td><?php echo $value['id_carga']; ?></td>
                      <td>
                        <select class="form-control" id="lavado_<?php echo $value['id_carga']; ?>" name="lavado_<?php echo $value['id_carga']; ?>">
                          <?php foreach ($lavados as $key2 => $value2): ?>
                            <?php if ($value2['id'] == $value['lavado_id']): ?>
                              <option selected value="<?php echo $value2['id'] ?>"><?php echo $value2['nombre'] ?></option>
                            <?php else: ?>
                              <option value="<?php echo $value2['id'] ?>"><?php echo $value2['nombre'] ?></option>
                            <?php endif; ?>
                          <?php endforeach; ?>
                        </select>
                      </td>
                      <td><button type="button" class="btn btn-warning" onclick="editarLavado(<?php echo $value['id_carga'] ?>)"><i class="far fa-edit"></i></button></td>
                      <td><button type="button" class="btn btn-danger" onclick="eliminarLavado(<?php echo $value['id_carga'] ?>)"><i class="far fa-trash-alt"></i></button></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              <center>
                <button type="button" name="botonGenerales" id="botonGenerales" class="btn btn-success"><i class="fas fa-plus"></i> Nuevo</button>
              </center>
            <?php else: ?>
              <div class="alert alert-danger" role="alert">
                No hay lavados para este corte.
              </div>
              <div class="card-footer text-muted">
              </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="card-footer text-muted">
        </div>
      </div>
      <div class="card">
        <a data-toggle="collapse" role="button" href="#generalesAutorizacion" aria-expanded="true" aria-controls="generalesAutorizacion">
          <div class="card-header">
            Datos generales de autorización de corte.
          </div>
        </a>
        <div class="card-body" id="generalesAutorizacion" name="generalesAutorizacion">
          <?php if ($autorizado != 0): ?>
            <div class="table-responsive">
              <table class="table table-striped">
                <tbody>
                  <tr>
                    <td>Fecha de autorización</td>
                    <td><input placeholder="Escribe la fecha" class="form-control" type="date" value="<?php echo $autorizado['fecha_autorizado'] ?>" id="fecha_autorizado" name="fecha_autorizado"></td>
                  </tr>
                  <tr>
                    <td>Cargas</td>
                    <td><?php echo $autorizado['cargas'] ?></td>
                  </tr>
                  <tr>
                    <td>Usuario que autorizó</td>
                    <td>
                      <select class="form-control" name="usuarioAutorizo" id="usuarioAutorizo">
                        <?php foreach ($usuarios as $key => $value): ?>
                          <?php if ($value['id'] == $autorizado['usuario_id']): ?>
                            <option selected value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                          <?php else: ?>
                            <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                          <?php endif; ?>
                        <?php endforeach; ?>
                      </select>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="card-footer text-muted">
              <button type="button" class="btn btn-primary" name="botonAutorizacion" id="botonAutorizacion"><i class="fas fa-check"></i> Aceptar</button>
              <button type="button" class="btn btn-danger" name="botonAutorizacionEliminar" id="botonAutorizacionEliminar"><i class="far fa-trash-alt"></i> Eliminar</button>
            </div>
          <?php else: ?>
            <div class="alert alert-danger" role="alert">
              No hay datos de autorización de este corte.
            </div>
            <div class="card-footer text-muted">
            </div>
          <?php endif; ?>
        </div>
      </div>
      <div class="card">
        <a data-toggle="collapse" role="button" href="#especificosAutorizacion" aria-expanded="true" aria-controls="especificosAutorizacion">
          <div class="card-header">
            Datos específicos de autorización de corte.
          </div>
        </a>
        <div class="card-body" id="especificosAutorizacion" name="especificosAutorizacion">
          <?php if ($autorizadoDatos != 0): ?>
            <div class="table-responsive">
              <table class="table table-striped">
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
                <tbody>
                  <?php foreach ($autorizadoDatos as $key => $value): ?>
                    <input type="hidden" name="id_carga_autorizado_datos_anterior_<?php echo $value['id'] ?>" id="id_carga_autorizado_datos_anterior_<?php echo $value['id'] ?>" value="<?php echo $value['id_carga'] ?>">
                    <input type="hidden" name="id_lavado_autorizado_datos_anterior_<?php echo $value['id'] ?>" id="id_lavado_autorizado_datos_anterior_<?php echo $value['id'] ?>" value="<?php echo $value['lavado_id'] ?>">
                    <input type="hidden" name="id_proceso_seco_id_autorizado_datos_anterior_<?php echo $value['id'] ?>" id="id_proceso_seco_id_autorizado_datos_anterior_<?php echo $value['id'] ?>" value="<?php echo $value['proceso_seco_id'] ?>">
                    <input type="hidden" name="costo_autorizado_datos_<?php echo $value['id']; ?>" id="costo_autorizado_datos_<?php echo $value['id']; ?>" value="<?php echo $value['costo']; ?>">
                    <input type="hidden" name="piezas_trabajadas_autorizado_datos_<?php echo $value['id']; ?>" id="piezas_trabajadas_autorizado_datos_<?php echo $value['id']; ?>" value="<?php echo $value['piezas_trabajadas']; ?>">
                    <input type="hidden" name="defectos_autorizado_datos_<?php echo $value['id']; ?>" id="defectos_autorizado_datos_<?php echo $value['id']; ?>" value="<?php echo $value['defectos']; ?>">
                    <input type="hidden" name="status_autorizado_datos_<?php echo $value['id']; ?>" id="status_autorizado_datos_<?php echo $value['id']; ?>" value="<?php echo $value['status']; ?>">
                    <input type="hidden" name="fecha_registro_autorizado_datos_<?php echo $value['id']; ?>" id="fecha_registro_autorizado_datos_<?php echo $value['id']; ?>" value="<?php echo $value['fecha_registro']; ?>">
                    <input type="hidden" name="orden_autorizado_datos_<?php echo $value['id']; ?>" id="orden_autorizado_datos_<?php echo $value['id']; ?>" value="<?php echo $value['orden']; ?>">
                    <input type="hidden" name="usuario_id_autorizado_datos_<?php echo $value['id']; ?>" id="usuario_id_autorizado_datos_<?php echo $value['id']; ?>" value="<?php echo $value['usuario_id']; ?>">
                    <tr>
                      <td><?php echo $value['id_carga'] ?></td>
                      <td>
                        <?php foreach ($lavados as $key2 => $value2): ?>
                          <?php if ($value2['id'] == $value['lavado_id']): ?>
                            <?php echo $value2['nombre']; break; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>
                      </td>
                      <td>
                        <?php foreach ($procesosecos as $key2 => $value2): ?>
                          <?php if ($value2['id'] == $value['proceso_seco_id']): ?>
                            <?php echo $value2['nombre']; break; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>
                      </td>
                      <td>
                        <?php switch ($value['status'])
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
                        } ?>
                      </td>
                      <td><?php echo $value['fecha_registro'] ?></td>
                      <td>
                        <?php foreach ($usuarios as $key2 => $value2): ?>
                          <?php if ($value2['id'] == $value['usuario_id']): ?>
                            <?php echo $value2['nombre']; break; ?>
                          <?php endif; ?>
                        <?php endforeach; ?>
                      </td>
                      <td><button type="button" class="btn btn-warning" onclick="editarAutorizadoDatos(<?php echo $value['id']; ?>);"><i class="far fa-edit"></i></button></td>
                      <td><button type="button" class="btn btn-danger" onclick="eliminarAutorizadoDatos(<?php echo $value['id']; ?>);"><i class="far fa-trash-alt"></i></button></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="alert alert-danger" role="alert">
              No hay datos de autorización de este corte.
            </div>
          <?php endif; ?>
        </div>
        <div class="card-footer text-muted">
        </div>
      </div>
      <div class="card">
        <a data-toggle="collapse" role="button" href="#generalesSalidaInterna" aria-expanded="true" aria-controls="generalesSalidaInterna">
          <div class="card-header">
            Datos generales de salida interna.
          </div>
        </a>
        <div class="card-body" id="generalesSalidaInterna" name="generalesSalidaInterna">
          <?php if ($salidaInterna != 0): ?>
            <div class="table-responsive">
              <table class="table table-striped">
                <tbody>
                  <tr>
                    <td>Fecha de salida interna</td>
                    <td><input placeholder="Escribe la fecha" class="form-control" type="date" value="<?php echo $salidaInterna['fecha'] ?>" id="fechaSalidaInterna" name="fechaSalidaInterna"></td>
                  </tr>
                  <tr>
                    <td>Muestras</td>
                    <td>
                      <input placeholder="Escribe el número de muestras" type="number" name="muestrasSalidaInterna" id="muestrasSalidaInterna" class="form-control" value="<?php echo $salidaInterna['muestras'] ?>">
                    </td>
                  </tr>
                  <tr>
                    <td>Usuario que dio la salida interna</td>
                    <td>
                      <select class="form-control" name="usuarioSalidaInterna" id="usuarioSalidaInterna">
                        <?php foreach ($usuarios as $key => $value): ?>
                          <?php if ($value['id'] == $salidaInterna['usuario_id']): ?>
                            <option selected value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                          <?php else: ?>
                            <option value="<?php echo $value['id'] ?>"><?php echo $value['nombre'] ?></option>
                          <?php endif; ?>
                        <?php endforeach; ?>
                      </select>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="card-footer text-muted">
              <button type="button" class="btn btn-primary" name="botonSalidaInterna" id="botonSalidaInterna"><i class="fas fa-check"></i> Aceptar</button>
              <button type="button" class="btn btn-danger" name="botonSalidaInternaEliminar" id="botonSalidaInternaEliminar"><i class="far fa-trash-alt"></i> Eliminar</button>
            </div>
          <?php else: ?>
            <div class="alert alert-danger" role="alert">
              No hay datos de salida interna de este corte.
            </div>
            <div class="card-footer text-muted">
            </div>
          <?php endif; ?>
        </div>
      </div>
      <div class="card">
        <a data-toggle="collapse" role="button" href="#especificosSalidaInterna" aria-expanded="true" aria-controls="especificosSalidaInterna">
          <div class="card-header">
            Datos específicos de salida interna.
          </div>
        </a>
        <div class="card-body" id="especificosSalidaInterna" name="especificosSalidaInterna">
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
                <tbody>
                  <?php foreach ($salidaInternaDatos as $key => $value): ?>
                    <input type="hidden" name="IdLavadoSalidaInternaDatos<?php echo $value['id_carga']; ?>" id="IdLavadoSalidaInternaDatos<?php echo $value['id_carga']; ?>" value="<?php echo $value['lavado_id']; ?>">
                    <tr>
                      <td><?php echo $value['id_carga']; ?></td>
                      <td>
                        <?php foreach ($lavados as $key2 => $value2): ?>
                          <?php if ($value2['id'] == $value['lavado_id']): ?>
                            <?php echo $value2['nombre'] ?>
                          <?php endif; ?>
                        <?php endforeach; ?>
                      </td>
                      <td><input placeholder="Inserta número de piezas" class="form-control" type="number" id="piezasSalidaInternaDatos<?php echo $value['id_carga']; ?>" name="piezasSalidaInternaDatos<?php echo $value['id_carga']; ?>" value="<?php echo $value['piezas'] ?>"></td>
                      <td><button type="button" class="btn btn-warning" onclick="editarSalidaInternaDatos(<?php echo $value['id_carga']; ?>)"><i class="far fa-edit"></i></button></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="alert alert-danger" role="alert">
              No hay datos de salida interna de este corte.
            </div>
          <?php endif; ?>
        </div>
        <div class="card-footer text-muted">
        </div>
      </div>
      <div class="card">
        <a data-toggle="collapse" role="button" href="#produccion" aria-expanded="true" aria-controls="produccion">
          <div class="card-header">
            Datos de producción de proceso seco
          </div>
        </a>
        <div class="card-body" id="produccion" name="produccion">
          <?php if ($produccionProcesoSeco != 0): ?>
            <div class="table-responsive">
              <table class="table table-striped">
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
                    <th>Editar</th>
                    <th>Eliminar</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($produccionProcesoSeco as $key => $value): ?>
                    <tr>
                      <td><?php echo $value['usuario'] ?></td>
                      <td><?php echo $value['lavado'] ?></td>
                      <td><?php echo $value['proceso'] ?></td>
                      <td>
                        <input class="form-control" placeholder="Escribe el número de piezas" type="number" id="piezasProduccion<?php echo $value['id']; ?>" name="piezasProduccion<?php echo $value['id']; ?>" value="<?php echo $value['piezas'] ?>">
                      </td>
                      <td>$<?php echo $value['costo'] ?></td>
                      <td>$<?php echo $value['total'] ?></td>
                      <td>
                        <input class="form-control" type="number" id="defectosProduccion<?php echo $value['id'] ?>" name="defectosProduccion<?php echo $value['id'] ?>" value="<?php echo $value['defectos'] ?>">
                      </td>
                      <td>
                        <input class="form-control" type="date" value="<?php echo $value['fecha'] ?>" id="fechaProduccion<?php echo $value['id']; ?>" name="fechaProduccion<?php echo $value['id']; ?>">
                      </td>
                      <td><button type="button" class="btn btn-warning" onclick="editarProduccion(<?php echo $value['id']; ?>);"><i class="far fa-edit"></i></button></td>
                      <td><button type="button" class="btn btn-danger" onclick="eliminarProduccion(<?php echo $value['id']; ?>);"><i class="far fa-trash-alt"></i></button></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <div class="alert alert-danger" role="alert">
              No hay datos de producción de este corte.
            </div>
          <?php endif; ?>
        </div>
        <div class="card-footer text-muted">
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalImagen" tabindex="-1" role="dialog" aria-labelledby="modalImagen" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modificar imágen actual</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form name="cambiarImagen" id="cambiarImagen" action="<?php echo base_url('index.php/administracion/modificarImagen') ?>" method="post" enctype="multipart/form-data">
        <div class="modal-body">
          <li class="list-group-item"><?php echo $generales['imagen'] ?></li>
          <li class="list-group-item">
            <h6>Imágen nueva</h6>
            <?php echo form_input($input_imagen); ?>
            <input type="hidden" name="folioCambiarImagen" id="folioCambiarImagen" value="<?php echo $this->input->get()['folio'] ?>" >
          </li>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close"></i> Cerrar</button>
          <button type="button" id="botonModalImagen" name="botonModalImagen" class="btn btn-primary"><i class="fas fa-check"></i> Aceptar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php if ($autorizadoDatos != 0): ?>
  <div class="modal fade" id="modalCorteAutorizadoDatos" name="modalCorteAutorizadoDatos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Modificar datos del corte autorizado</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id_autorizado_datos" id="id_autorizado_datos">
          <div class="table-responsive">
            <table class="table table-striped">
              <tbody>
                <tr>
                  <td>Número de carga</td>
                  <td>
                    <input readonly placeholder="Escribe la id de carga" type="number" class="form-control" name="id_carga_autorizado_datos" id="id_carga_autorizado_datos">
                  </td>
                </tr>
                <tr>
                  <td>Lavado</td>
                  <td>
                    <select disabled="true" class="form-control" name="lavado_id_autorizado_datos" id="lavado_id_autorizado_datos">
                      <?php foreach ($lavados as $key2 => $value2): ?>
                        <option value="<?php echo $value2['id'] ?>"><?php echo $value2['nombre'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Proceso Seco</td>
                  <td>
                    <select class="form-control" name="proceso_id_autorizado_datos" id="proceso_id_autorizado_datos">
                      <?php foreach ($procesosecos as $key2 => $value2): ?>
                        <option value="<?php echo $value2['id'] ?>"><?php echo $value2['nombre'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Costo</td>
                  <td><input placeholder="Escribe el costo" type="number" class="form-control" step="any" name="autorizado_datos_costo" id="autorizado_datos_costo"></td>
                </tr>
                <tr>
                  <td>Piezas trabajadas o por trabajar</td>
                  <td><input placeholder="Escribe el número de piezas" type="number" class="form-control" name="autorizado_datos_piezas_trabajadas" id="autorizado_datos_piezas_trabajadas"></td>
                </tr>
                <tr>
                  <td>Defectos</td>
                  <td><input placeholder="Escribe el número de defectos" type="number" class="form-control" name="autorizado_datos_defectos" id="autorizado_datos_defectos"></td>
                </tr>
                <tr>
                  <td>Estatus</td>
                  <td>
                    <select class="form-control" name="autorizado_datos_estatus" id="autorizado_datos_estatus" >
                      <option value="0">No registrado</option>
                      <option value="1">Para registrar</option>
                      <option value="2">Registrado</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>Orden</td>
                  <td><input placeholder="Escribe el orden numérico" class="form-control" type="number" name="autorizado_datos_orden" id="autorizado_datos_orden"></td>
                </tr>
                <tr>
                  <td>Fecha de registro</td>
                  <td><input placeholder="Escribe la fecha" class="form-control" type="date" value="<?php echo date("Y-m-d") ?>" name="autorizado_datos_fecha_registro_" id="autorizado_datos_fecha_registro_"></td>
                </tr>
                <tr>
                  <td>Usuario que registró</td>
                  <td>
                    <select class="form-control" name="autorizado_datos_usuario_id" id="autorizado_datos_usuario_id">
                      <?php foreach ($usuarios as $key2 => $value2): ?>
                        <option value="<?php echo $value2['id'] ?>"><?php echo $value2['nombre'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-window-close"></i> Cerrar</button>
          <button type="button" class="btn btn-primary" name="editarDatosCorteAutorizado" id="editarDatosCorteAutorizado"><i class="fas fa-check"></i> Aceptar</button>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
