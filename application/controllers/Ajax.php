<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if (!isset($_SESSION['id'])) redirect('/');
	}

	//ROOT
	public function rootReporte($folio=null)
	{
		if ($folio == null)
		echo "<div class='alert alert-info' role='alert'>Favor de insertar el número de folio.</div>";
		else
		{
			if ($this->existeCorte($folio)) echo "<input type='submit' value='aceptar'/>";
			else echo "<div class='alert alert-info' role='alert'>El corte aún no existe en la base de datos.</div>";
		}
	}

	public function rootCargas($folio=null)
	{
		if ($folio != null)
		{
			if ($this->existeCorte($folio))
			{
				$this->load->model('corteAutorizadoDatos');$this->load->model('corteAutorizadoDatos');
				$query=$this->corteAutorizadoDatos->joinLavadoProcesos($folio);
				if (count($query) == 0) echo "<div class='alert alert-info' role='alert'>Corte aún no autorizado.</div>";
				else
				{
					echo "<label>Carga: </label><select name='carga' id='carga'><option value=-1>Seleccione la carga</option>";
					foreach ($query as $key => $value) echo "<option value=".($key+1).">".strtoupper ($value['lavado'])."</option>";
					echo "</select><br />";
				}
			}
			else echo "<div class='alert alert-info' role='alert'>El corte aún no existe en la base de datos.</div>";
		}
	}

	public function rootValida($cadena=null)
	{
		$folio = explode("_",$cadena)[0];
		$carga = explode("_",$cadena)[1];
		if ($carga == -1)	echo "<div class='alert alert-warning' role='alert'>Seleccione un opción válida.</div>";
		else	echo "<input type='submit' name='orden' id='orden' value='Aceptar'/>";
	}

	public function agregarRenglon($numero)
	{
		$this->load->model('Marca');
		$marcas = $this->Marca->get();
		echo "<tr id='renglon$numero' name='renglon$numero'><td>Marca</td><td><select id='marca[$numero]' name='marca[$numero]'>";
		foreach ($marcas as $key => $value)	echo "<option value='".$value['id']."'>".$value['nombre']."</option>";
		echo"</td><td><button type='button' name='eliminar$numero' id='eliminar$numero'>Eliminar</button></td></tr>";
	}

	//GESTIÓN
	public function gestionMarcas()
	{
		if ($_SESSION['id'] != 2) redirect('/');
		if (!$this->input->post()) redirect('/');
		else
		{
			$cliente = $this->input->post()["cliente"];
			if ($cliente == -1) echo "<div class='alert alert-warning' role='alert'>Escoja primero el cliente.</div>";
			else
			{
				$this->load->model('marca');
				$query = $this->marca->getByCliente($cliente);
				echo "<select class='form-control' name='marca' id='marca'>";
				if ($query == null) echo "<option value='0'>Ninguna</option>";
				else
				{
					foreach ($query as $key => $value) echo "<option value='".$value['marcaId']."'>".$value['marcaNombre']."</option>";
				}
				echo "</select>";
			}
		}
	}

	public function salidaInterna()
	{
		if ($_SESSION['id'] != 2 || !$this->input->post()) redirect('/');
		else
		{
			$folio=$this->input->post()["folio"];
			if ($folio == null)
			{
				$info = array(
					'respuesta' => utf8_encode("<div class='alert alert-info' role='alert'>Ingresa el número de folio.</div>"),
					'info' => '',
				);
				$info = json_encode($info);
				echo $info;
			}
			else
			{
				$this->load->model('corte');
				$query2 = $this->corte->getByFolio($folio);
				$datos['folio']=$folio;
				if (count($query2) == 0)
				{
					$info = array(
						'respuesta' => utf8_encode("<div class='alert alert-warning' role='alert'>El corte no está en la base de datos.</div>"),
						'info' => '',
					);
					$info = json_encode($info);
					echo $info;
				}
				else
				{
					$this->load->model('corteAutorizado');
					$query = $this->corteAutorizado->getByFolio($folio);
					if (count($query) == 0)
					{
						$info = array(
							'respuesta' => utf8_encode("<div class='alert alert-warning' role='alert'>El corte no se ha autorizado.</div>"),
							'info' => '',
						);
						$info = json_encode($info);
						echo $info;
					}
					else
					{
						$this->load->model('salidaInterna1');
						$query = $this->salidaInterna1->getByFolio($folio);
						if (count($query) != 0)
						{
							$info = array(
								'respuesta' => utf8_encode("<div class='alert alert-warning' role='alert'>El corte ya tiene salida interna.</div>"),
								'info' => '',
							);
							$info = json_encode($info);
							echo $info;
						}
						else
						{
							$cadena = "<div class='form-group row'><label for='Piezas' class='col-3 col-form-label'>Piezas</label><div class='col-9'><input type='number' name='piezas' id='piezas' readonly='true' class='form-control' value='".$query2[0]['piezas']."'></input></div></div><div class='form-group row'><label for='Muestras' class='col-3 col-form-label'>Muestras</label><div class='col-9'><input type='number' required='true' name='muestras' id='muestras' placeholder='Inserte muestras' class='form-control'></input></div></div><div class='form-group row'><div class='col-12'><div class='table-responsive'><table name='tabla' id='tabla' class='table'><thead><tr><th>Lavado</th><th>Piezas</th><th>Abrir con</th></tr></thead><tbody>";
							$this->load->model('corteAutorizadoDatos');
							$autorizado = $this->corteAutorizadoDatos->joinLavado($folio);
							foreach ($autorizado as $key => $value)
							{
								$cadena .= "<tr><td>".$value['id_carga']." ".strtoupper($value['nombre'])."</td><td><input type='number' name='piezas_parcial$key' id='piezas_parcial$key' class='form-control' placeholder='Inserte # de piezas' required='true' class='form-control'/></td>";
								$query10 = $this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros($folio,$key+1);
								$cadena .= "<td><select name='primero[$key]' class='form-control'>";
								foreach ($query10 as $key => $value)
								$cadena .= "<option value='".$value['idproceso']."'>".$value['proceso']."</option>";
								$cadena .= "</select></td></tr>";
							}
							$cadena .= "</tbody></table></div><div class='col-6'><input type='submit' class='btn btn-primary' value='Aceptar'/></div><input type='hidden' name='fechabd' id='fechabd' value='".$query2[0]['fecha_entrada']."'/><input type='hidden' name='cargas' id='cargas' value='".count($autorizado)."'/></div></div>";
							$infoCorte = $this->infoCorte($folio);
							$info = array(
								'respuesta' => utf8_encode($cadena),
								'info' => $infoCorte,
							);
							$info = json_encode($info);
							echo $info;
						}
					}
				}
			}
		}
	}

	public function salidaAlmacen()
	{
		if ($_SESSION['id'] != 2 || !$this->input->post()) redirect('/');
		else
		{
			$folio = $this->input->post()["folio"];
			//Verificar si el corte es nulo
			if ($folio == "")
			{
				$info = array(
					'respuesta' => "<div class='alert alert-info' role='alert'>Ingresa el número de folio.</div>",
					'info' => '',
				);
				$info = json_encode($info);
				echo $info;
			}
			else
			{
				//Verificar si el corte existe en la base de datos
				$datos['folio'] = $folio;
				if (!$this->existeCorte($folio))
				{
					$info = array(
						'respuesta' => "<div class='alert alert-info' role='alert'>El corte con folio ".$folio." no existe en la base de datos.</div>",
						'info' => '',
					);
					$info = json_encode($info);
					echo $info;
				}
				else
				{
					//Verificar si el corte está autorizado
					$this->load->model('corteAutorizado');
					$query = $this->corteAutorizado->getByFolio($folio);
					if (count($query) == 0)
					{
						$info = array(
							'respuesta' => "<div class='alert alert-info' role='alert'>El corte con folio ".$folio." no está autorizado.</div>",
							'info' => '',
						);
						$info = json_encode($info);
						echo $info;
					}
					else
					{
						//Verificar si el corte tiene salida interna
						$this->load->model('salidaInterna1');
						$query = $this->salidaInterna1->getByFolio($folio);
						if (count($query) == 0)
						{
							$info = array(
								'respuesta' => "<div class='alert alert-info' role='alert'>El corte con folio ".$folio." no tiene salida interna.</div>",
								'info' => '',
							);
							$info = json_encode($info);
							echo $info;
						}
						else
						{
							//Verificar si el corte ya tiene salida externa
							$this->load->model('entregaExterna');
							$query = $this->entregaExterna->getByFolio($folio);
							if (count($query) != 0)
							{
								$info = array(
									'respuesta' => "<div class='alert alert-info' role='alert'>El corte con folio ".$folio." ya tiene entrega externa.</div>",
									'info' => '',
								);
								$info = json_encode($info);
								echo $info;
							}
							else
							{
								//Verificar si el corte ya tiene entrega a almacen
								$this->load->model('entregaAlmacen');
								$query = $this->entregaAlmacen->getByFolio($folio);
								if (count($query) != 0)
								{
									$info = array(
										'respuesta' => "<div class='alert alert-info' role='alert'>El corte con folio ".$folio." ya tiene entrega a almacen.</div>",
										'info' => '',
									);
									$info = json_encode($info);
									echo $info;
								}
								else
								{
									$this->load->model('corteAutorizadoDatos');
									$query = $this->corteAutorizadoDatos->getByFolioEspecifico($folio);
									$query2 = $this->corteAutorizadoDatos->getByFolioStatus2($folio);
									$cadena = "<div class='card'><a data-toggle='collapse' href='#ver' role='button' aria-expanded='true' aria-controls='ver'><div class='card-header'><strong>Datos específicos del folio ".$folio."</strong></div></a><div class='collapse' id='ver'><div class='card-body'><div class='table-responsive'><table class='table table-bordered'><thead><tr><th>Carga</th><th>Lavado</th><th>Proceso</th><th>Piezas Trabajdas</th><th>Defectos</th><th>Estado</th><th>Orden</th><th>Fecha de registro</th><th>Usuario que registró</th></tr></thead><tbody>";
									foreach ($query as $key => $value)
									{
										$idcarga = $value['idcarga'];
										$lavado = $value['lavado'];
										$proceso = $value['proceso'];
										$piezas = $value['piezas'];
										$defectos = $value['defectos'];
										$status = $value['status'];
										switch ($status)
										{
											case 0:
											//rojo
											$status = "No registrado";
											$clase = "table-danger";
											$piezas = 0;
											break;
											case 1:
											//azul
											$status = "Listo para registrar";
											$clase = "table-primary";
											$piezas = 0;
											break;
											case 2:
											//verde
											$status = "Registrado";
											$clase = "table-success";
											break;
											default: break;
										}
										$orden = $value['orden'];
										$fecha = $value['fecha'];
										if ($fecha == "0000-00-00")	$fecha = "Sin fecha";
										$usuario = $value['usuario'];
										if ($usuario == "DEFAULT")	$usuario = "Por defecto";
										$cadena .= "<tr class='".$clase."'><td>".$idcarga."</td><td>".$lavado."</td><td>".$proceso."</td><td>".$piezas."</td><td>".$defectos."</td><td>".$status."</td><td>".$orden."</td><td>".$fecha."</td><td>".$usuario."</td></tr>";
									}
									$cadena .= "</tbody></table></div></div></div></div>";
									//No existen procesos con estatus 2
									//No hacer submit
									if (count($query2) != 0)	$cadena .= "<div class='alert alert-danger' role='alert'>No puede dar de alta este corte en almacén porque existen procesos que no se han registrado. Favor de revisar con los operarios y encargados.</div>";
									//No existen procesos con status 2
									//Hacer submit
									else $cadena .= "<form method='post' action='".base_url()."index.php/gestion/salidaAlmacen'><input type='hidden' name='folio' value='$folio'><div class='card'><div class='card-header'>¿Desea dar de alta este corte en almacén?</div><div class='card-body'><input type='submit' class='btn btn-primary' value='Aceptar'></div></div></form>";
									$datos = $this->infoCorte($folio);
									$info = array(
										'respuesta' => $cadena,
										'info' => $datos,
									);
									$info = json_encode($info);
									echo $info;
								}
							}
						}
					}
				}
			}
		}
	}

	public function salidaExterna()
	{
		if ($_SESSION['id'] != 2 || !$this->input->post()) redirect('/');
		else
		{
			$folio = $this->input->post()["folio"];
			//Verificar si el corte es nulo
			if ($folio == "")
			{
				$info = array(
					'respuesta' => "<div class='alert alert-info' role='alert'>Ingresa el número de folio.</div>",
					'info' => '',
				);
				$info = json_encode($info);
				echo $info;
			}
			else
			{
				//Verificar si el corte existe en la base de datos
				$datos['folio']=$folio;
				if ($this->existeCorte($folio))
				{
					$info = array(
						'respuesta' => "<div class='alert alert-info' role='alert'>El corte con folio ".$folio." no existe en la base de datos.</div>",
						'info' => '',
					);
					$info = json_encode($info);
					echo $info;
				}
				else
				{
					//Verificar si el corte está autorizado
					$this->load->model('corteAutorizado');
					$query = $this->corteAutorizado->getByFolio($folio);
					if (count($query) == 0)
					{
						$info = array(
							'respuesta' => "<div class='alert alert-info' role='alert'>El corte con folio ".$folio." no está autorizado.</div>",
							'info' => '',
						);
						$info = json_encode($info);
						echo $info;
					}
					else
					{
						//Verificar si el corte tiene salida interna
						$this->load->model('salidaInterna1');
						$query = $this->salidaInterna1->getByFolio($folio);
						if (count($query) == 0)
						{
							$info = array(
								'respuesta' => "<div class='alert alert-info' role='alert'>El corte con folio ".$folio." no tiene salida interna.</div>",
								'info' => '',
							);
							$info = json_encode($info);
							echo $info;
						}
						else
						{
							//Verificar si el corte ya tiene entrega a almacen
							$this->load->model('entregaAlmacen');
							$query = $this->entregaAlmacen->getByFolio($folio);
							if (count($query) == 0)
							{
								$info = array(
									'respuesta' => "<div class='alert alert-info' role='alert'>El corte con folio ".$folio." no tiene entrega a almacen.</div>",
									'info' => '',
								);
								$info = json_encode($info);
								echo $info;
							}
							else
							{
								//Verificar si el corte ya tiene salida externa
								$this->load->model('entregaExterna');
								$query = $this->entregaExterna->getByFolio($folio);
								if (count($query) != 0)	$cadena = "<div class='alert alert-info' role='alert'>El corte con folio ".$folio." ya tiene entrega externa.</div>";
								else $cadena = "<div class='card'><div class='card-header'>¿Desea dar salida externa a este corte?</div><div class='card-body'><input type='submit' class='btn btn-primary' value='Aceptar'></div></div>";
								$infoCorte = $this->infoCorte($folio);
								$info = array(
									'respuesta' => $cadena,
									'info' => $infoCorte,
								);
								$info = json_encode($info);
								echo $info;
							}
						}
					}
				}
			}
		}
	}

	//PRODUCCIÓN
	public function autorizacionCorte($folio=null)
	{
		if ($_SESSION['id'] != 3 || !$this->input->post()) redirect('/');
		else
		{
			$folio = $this->input->post()["folio"];
			if ($folio == null)
			{
				$info = array(
					'respuesta' => utf8_encode("<div class='alert alert-info' role='alert'>Escriba el número del corte.</div>"),
					'info' => '',
				);
				$info = json_encode($info);
				echo $info;
			}
			else
			{
				$this->load->model('corte');
				$resultado = $this->corte->getByFolio($folio);
				if (count($resultado) == 0)
				{
					$info = array(
						'respuesta' => "<div class='alert alert-info' role='alert'>El corte no se encuentra en la base de datos.</div>",
						'info' => '',
					);
					$info = json_encode($info);
					echo $info;
				}
				else
				{
					$this->load->model('corteAutorizado');
					$resultado2 = $this->corteAutorizado->getByFolio($folio);
					if (count($resultado2) != 0)
					{
						$info = array(
							'respuesta' => "<div class='alert alert-warning' role='alert'>El corte ya fue autorizado.</div>",
							'info' => '',
						);
						$info = json_encode($info);
						echo $info;
					}
					else
					{
						$corte = $this->infoCorte($folio);
						unset($corte['ojales']);
						$info = array(
							'respuesta' => "<input type='submit' class='btn btn-primary' value='Aceptar'/>",
							'info' => $corte,
						);
						$info = json_encode($info);
						echo $info;
					}
				}
			}
		}
	}

	public function agregarRenglonProduccion()
	{
		if (!$this->input->post()) redirect('/');
		else
		{
			$numero = $this->input->post()["numero"];
			echo "<tr name='renglon$numero' id='renglon$numero' ><td><center><select name='lavado[$numero]' id='lavado[$numero]' class='form-control'>";
			$this->load->model('Lavado');
			$lavados = $this->Lavado->get();
			foreach ($lavados as $key => $value) echo "<option value='".$value['id']."'>".$value['nombre']."</option>";
			echo "</select></center></td><td><center><select name='proceso_seco[$numero][]' id='proceso_seco$numero' class='form-control' multiple='multiple'>";
			$this->load->Model('ProcesoSeco');
			$procesos = $this->ProcesoSeco->get();
			foreach ($procesos as $key => $value) echo "<option value='".$value['id']."'>".$value['nombre']."</option>";
			echo "</select></center></td><td><button type='button' name='eliminar$numero' id='eliminar$numero' class='btn btn-danger' onclick='eliminar($numero)'><i class='far fa-trash-alt'></i></button></td></tr>";
		}
	}

	//OPERARIO Y OPERARIO VALIDA
	public function operarioCargas()
	{
		if (($_SESSION['id']!=4 && $_SESSION['id'] != 6) || !$this->input->post()) redirect('/');
		else
		{
			$folio = $this->input->post()["folio"];
			if ($folio == null) echo "<div class='col-12'><div class='alert alert-info' role='alert'>Escriba el número de folio.</div></div>";
			else
			{
				$datos['folio'] = $folio;
				if (!$this->existeCorte($folio)) echo "<div class='col-12'><div class='alert alert-info' role='alert'>El corte aún no existe en la base de datos.</div></div>";
				else
				{
					$this->load->model('corteAutorizadoDatos');
					$query = $this->corteAutorizadoDatos->joinLavadoProcesos($folio);
					if (count($query) == 0)	echo "<div class='col-12'><div class='alert alert-info' role='alert'>Corte aún no autorizado.</div></div>";
					else
					{
						echo "<label for='carga' class='col-3 col-form-label'>Carga</label><div class='col-9'><select name='carga' id='carga' class='form-control'><option value=-1>Seleccione la carga</option>";
						foreach ($query as $key => $value)	echo"<option value=".($key+1).">".strtoupper ($value['lavado'])."</option>";
						echo "</select></div>";
					}
				}
			}
		}
	}

	public function operarioProcesos()
	{
		if (($_SESSION['id'] != 4 && $_SESSION['id'] != 6) || !$this->input->post()) redirect('/');
		else
		{
			$folio = $this->input->post()["folio"];
			$carga = $this->input->post()["carga"];
			if ($carga == -1) echo "<div class='col-12'><div class='alert alert-warning' role='alert'>Escoja una opción válida.</div></div>";
			else
			{
				$this->load->model('corteAutorizadoDatos');
				$query = $this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros3($folio,$carga);
				if (count($query) == 0) echo "<div class='col-12'><div class='alert alert-info' role='alert'>Se han cerrado todos los procesos.</div></div>";
				else
				{
					echo "<label for='proceso' class='col-3 col-form-label'>Proceso</label><div class='col-9'><select name='proceso' id='proceso' class='form-control'><option value=-1>Seleccione el proceso</option>";
					foreach ($query as $key => $value) echo "<option value='".$value['idproceso']."'>".strtoupper($value['proceso'])."</option>";
					echo "</select></div>";
				}
			}
		}
	}

	public function operarioValida()
	{
		if (($_SESSION['id'] != 4 && $_SESSION['id'] != 6 || !$this->input->post())) redirect('/');
		else
		{
			$folio = $this->input->post()["folio"];
			$carga = $this->input->post()["carga"];
			$proceso = $this->input->post()["proceso"];
			if ($proceso == -1) echo "<div class='col-12'><div class='alert alert-warning' role='alert'>Seleccione un opción válida.</div></div>";
			else
			{
				$this->load->model('corteAutorizadoDatos');
				$query = $this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros2($folio,$carga,$proceso);
				if ($query[0]['status'] == 1) echo "<div class='col-12'><input type='submit' class='btn btn-primary' value='Aceptar'/></div><input type='hidden' name='piezas' id='piezas' value='".$query[0]['piezas']."'/><input type='hidden' name='nombreCarga' id='nombreCarga' value='".$query[0]['lavado']."'/><input type='hidden' name='nombreProceso' id='nombreProceso' value='".$query[0]['proceso']."'/><input type='hidden' name='idlavado' id='idlavado' value='".$query[0]['idlavado']."'/><input type='hidden' name='orden' id='orden' value='".$query[0]['orden']."'/>";
				else echo "<div class='col-12'><div class='alert alert-info' role='alert'>Éste proceso no está disponible.</div></div>";
			}
		}
	}

	//ADMINISTRACIÓN
	public function costosAdministracion()
	{
		if ($_SESSION['id'] != 1 || !$this->input->post()) redirect('/');
		else
		{
			$folio = $this->input->post()["folio"];
			if ($folio != null)
			{
				$datos['folio'] = $folio;
				if ($this->existeCorte($folio))
				{
					$this->load->model('corteAutorizadoDatos');$this->load->model('corteAutorizadoDatos');
					$query = $this->corteAutorizadoDatos->joinLavadoProcesos($folio);
					if (count($query) == 0) echo "<div class='col-12'><div class='alert alert-info' role='alert'>Corte aún no autorizado.</div></div>";
					else
					{
						echo "<label for='carga' class='col-3 col-form-label'>Carga</label><div class='col-9'><select name='carga' id='carga' class='form-control'>";
						foreach ($query as $key => $value) echo"<option value=".($key+1).">".strtoupper ($value['lavado'])."</option>";
						echo "</select></div><div class='col-12'><input type='submit' class='btn btn-primary' value='Aceptar'/></div>";
					}
				}
				else echo "<div class='col-12'><div class='alert alert-info' role='alert'>El corte aún no existe en la base de datos.</div></div>";
			}
		}
	}

	public function existeUsuario()
	{
		if ($_SESSION['id'] != 1 || !$this->input->post()) redirect('/');
		else
		{
			$nombre = $this->input->post()["nombre"];
			$this->load->model("Usuarios");
			return $this->Usuarios->exists($nombre);
		}
	}

	public function detalleCorte($folio=null)
	{
		if (!$this->input->post()) redirect("/");
		$folio = $this->input->post()['folio'];
		if (!$this->input->post()) redirect('/');
		else
		{
			if ($this->existeCorte($folio)) echo json_encode($this->infoCorte($folio));
			else echo json_encode(array('folio' => '', ));
		}
	}

	//Método para recuperar datos del corte
	private function infoCorte($folio)
	{
		$extensiones = array("jpg","jpeg","png");
		$ban=false;
		foreach ($extensiones as $key2 => $extension)
		{
			$url = base_url()."img/fotos/".$folio.".".$extension;
			$headers = get_headers($url);
			if (stripos($headers[0],"200 OK"))
			{
				$ban=true;
				$imagen="<img src='".base_url()."img/fotos/".$folio.".".$extension."' class='img-fluid' alt='Responsive image'>";
				break;
			}
		}
		if (!$ban)	$imagen="No hay imágen";
		//Información del corte
		$this->load->model("corte");
		$corte = $this->corte->getByFolioGeneral($folio)[0];
		$corte['imagen'] = $imagen;
		return $corte;
	}

	//Método que devuelve true si existe el corte en la base de datos, de lo contrario regresa false
	private function existeCorte($folio)
	{
		$this->load->model('corte');
		$query = $this->corte->getByFolio($folio);
		return (count($query) != 0);
	}

	public function cargasAdministracion()
	{
		if ($_SESSION['id'] != 1 || !$this->input->post())	redirect('/');
		$folio = $this->input->post()['folio'];
		$this->load->model('corteAutorizadoDatos');
		$query = $this->corteAutorizadoDatos->joinLavadoProcesos($folio);
		echo json_encode($query);
	}

	public function getProcesosReproceso()
	{
		if (!isset($this->input->post()['folio']))	redirect("/");
		$this->load->model("Reproceso");
		$query = $this->Reproceso->getByFolioOperarios($this->input->post()['folio']);
		echo json_encode($query);
	}
}
