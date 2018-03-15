<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		if(!isset($_SESSION['id']))
			redirect('/');
	}
	public function costosAdministracion($folio=null)
	{
		if($folio!=null)
		{
			$this->load->model('corte');
			$query=$this->corte->getByFolio($folio);
			$datos['folio']=$folio;
			if(count($query)!=0)
			{
				$this->load->model('corteAutorizadoDatos');$this->load->model('corteAutorizadoDatos');
				$query=$this->corteAutorizadoDatos->joinLavadoProcesos($folio);
				if(count($query)==0)
					echo "<h5 class='white'>CORTE AÚN NO AUTORIZADO</h5>";
				else
				{
					echo "<label>Carga: </label>";
					echo "<select name='carga' id='carga'>";
					foreach ($query as $key => $value)
					{
						echo
						"<option value=".($key+1).">
						".strtoupper ($value['lavado'])."
						</option>";
					}
					echo "</select><br />";
					echo "<input type='submit' class='bootstrap' value='Aceptar'/>";
				}
			}
			else
				echo "<h5 class='white'>CORTE AÚN NO EXISTENTE EN LA BASE DE DATOS</h5>";
		}
	}

	public function salidaInterna($folio=null)
	{
		if($folio==null)
			echo "<h5 class='white'>Ingresa el número de folio</h5>";
		else
		{
			$this->load->model('corte');
			$query2=$this->corte->getByFolio($folio);
			$datos['folio']=$folio;
			if(count($query2)!=0)
			{
				$this->load->model('corteAutorizado');
				$query=$this->corteAutorizado->getByFolio($folio);
				if(count($query)!=0)
				{
					$this->load->model('salidaInterna1');
					$query=$this->salidaInterna1->getByFolio($folio);
					if(count($query)==0)
					{
						echo "
						<table class='table table-striped'>
							<tbody>
							<tr>
								<td>Piezas</td>
								<td><input type='text' name='piezas' id='piezas' readonly='true' value='".$query2[0]['piezas']."'></input></td>
							</tr>
							<tr>
								<td>Muestras</td>
								<td><input type='number' required='true' name='muestras' id='muestras' placeholder='Inserte muestras'></input></td>
							</tr>
							<tr>
							<th><center>Lavado</center></th>
							<th><center>Piezas</center></th>
								<th><center>Abrir con</center></th>
								</tr>";
						$this->load->model('corteAutorizadoDatos');
						$autorizado=$this->corteAutorizadoDatos->joinLavado($folio);
						foreach ($autorizado as $key => $value)
						{
							echo "
							<tr>
							<td>".$value['id_carga']." ".strtoupper($value['nombre'])."</td>
							<td><center><input type='number' name='piezas_parcial$key' id='piezas_parcial$key' class='bootstrap' placeholder='Inserte # de piezas' required='true'/></center></td>";
								$query10=$this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros($folio,$key+1);
								echo "
								<td><center>
										<select name='primero[$key]'>";
										foreach ($query10 as $key => $value)
										{
											echo "<option value='".$value['idproceso']."'>".$value['proceso']."</option>";
										}
										echo "
										</select></center>
										</td>
										</tr>";
						}
						echo "</tbody></table><br />
						<input type='submit' class='bootstrap' value='Aceptar'/>";
						echo "<input type='hidden' name='fechabd' id='fechabd' value='".$query2[0]['fecha_entrada']."'/>";
						echo "<input type='hidden' name='cargas' id='cargas' value='".count($autorizado)."'/>";
					}

					else
						echo "<h5 class='white'>El corte ya tiene salida interna</h5>";
				}
				else
					echo "<h5 class='white'>El corte no se ha autorizado</h5>";
			}
			else
				echo "<h5 class='white'>El corte no está en la base de datos</h5>";
			
		}
	}

	public function autorizacionCorte($folio=null)
	{
		if($folio==null)
		{
			echo "<tr>
				<th>
					<h3 class='black'>Escriba el número del corte</h3>
					</th>
			</tr>";
		}
		else
		{
			$this->load->model('corte');
			$resultado=$this->corte->getByFolio($folio);
			if(count($resultado)==0)
				echo "<h3 class='black'>El corte no existe en la base de datos</h3>";
			else
			{
				$this->load->model('corteAutorizado');
				$resultado2=$this->corteAutorizado->getByFolio($folio);
				if(count($resultado2)!=0)
					echo "<h3 class='white'>El corte ya fue autorizado</h3>";
				else
					echo "<input type='submit' class='bootstrap' value='Aceptar'/>";
			}
		}
	}

	public function operarioCargas($folio=null)
	{
		//echo "<h5 class='white'>$folio</h5>";
		if($folio!=null)
		{
			$this->load->model('corte');
			$query=$this->corte->getByFolio($folio);
			$datos['folio']=$folio;
			if(count($query)!=0)
			{
				$this->load->model('corteAutorizadoDatos');$this->load->model('corteAutorizadoDatos');
				$query=$this->corteAutorizadoDatos->joinLavadoProcesos($folio);
				if(count($query)==0)
					echo "<h5 class='white'>CORTE AÚN NO AUTORIZADO</h5>";
				else
				{
					echo "<label>Carga: </label>";
					echo "<select name='carga' id='carga'>
									<option value=-1>
										Seleccione la carga
									</option>";
					foreach ($query as $key => $value)
					{
						echo
							"<option value=".($key+1).">
							".strtoupper ($value['lavado'])."
							</option>";
					}
					echo "
						</select><br />";
				}
			}
			else
				echo "<h5 class='white'>EL CORTE AÚN NO EXISTENTE EN LA BASE DE DATOS</h5>";
		}
	}

	public function operarioProcesos($cadena=null)
	{
		$folio=explode("_",$cadena)[0];
		$carga=explode("_",$cadena)[1];
		if($carga==-1)
					echo "<h5 class='white'>Escoja una opción válida</h5>";
		else
		{
			$this->load->model('corteAutorizadoDatos');
			$query=$this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros3($folio,$carga);
			if(count($query)==0)
			{
				echo "<h5 class='white'>No hay procesos disponibles</h5>";
			}
			else
			{
				echo "<label>Proceso: </label><select name='proceso' id='proceso'>
					<option value=-1>
						Seleccione el proceso
					</option>";
				foreach ($query as $key => $value)
				{
					echo "
					<option value='".$value['idproceso']."'>
					".strtoupper($value['proceso'])."
					</option>";
				}
				echo "</select>";
			}
		}
	}

	public function operarioValida($cadena=null)
	{
		$folio=explode("_",$cadena)[0];
		$carga=explode("_",$cadena)[1];
		$proceso=explode("_",$cadena)[2];
		if($proceso==-1)
			echo "<h5 class='white'>Seleccione un opción válida</h5>";
		else
		{
			$this->load->model('corteAutorizadoDatos');
			$query=$this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros2($folio,$carga,$proceso);
			if($query[0]['status']==1)
			{
				echo "<input type='submit' class='bootstrap' value='Aceptar'/>";
				echo "<input type='hidden' name='piezas' id='piezas' value='".$query[0]['piezas']."'/>";
				echo "<input type='hidden' name='nombreCarga' id='nombreCarga' value='".$query[0]['lavado']."'/>";
				echo "<input type='hidden' name='nombreProceso' id='nombreProceso' value='".$query[0]['proceso']."'/>";
				echo "<input type='hidden' name='idlavado' id='idlavado' value='".$query[0]['idlavado']."'/>";
				echo "<input type='hidden' name='orden' id='orden' value='".$query[0]['orden']."'/>";
			}
			else
				echo "<h5 class='white'>Éste proceso no está disponible</h5>";
		}
	}

	public function rootReporte($folio=null)
	{
		if($folio==null)
			echo "<h5 class='white'>Favor de insertar el número de folio</h5>";
		else
		{
			$this->load->model('corte');
			$query=$this->corte->getByFolio($folio);
			if(count($query)!=0)
				echo "<input type='submit' value='aceptar'/>";
			else
				echo "<h5 class='white'>EL CORTE AÚN NO EXISTENTE EN LA BASE DE DATOS</h5>";
		}
	}

	public function rootCargas($folio=null)
	{
		//echo "<h5 class='white'>$folio</h5>";
		if($folio!=null)
		{
			$this->load->model('corte');
			$query=$this->corte->getByFolio($folio);
			$datos['folio']=$folio;
			if(count($query)!=0)
			{
				$this->load->model('corteAutorizadoDatos');$this->load->model('corteAutorizadoDatos');
				$query=$this->corteAutorizadoDatos->joinLavadoProcesos($folio);
				if(count($query)==0)
					echo "<h5 class='white'>CORTE AÚN NO AUTORIZADO</h5>";
				else
				{
					echo "<label>Carga: </label>";
					echo "<select name='carga' id='carga'>
									<option value=-1>
										Seleccione la carga
									</option>";
					foreach ($query as $key => $value)
					{
						echo
							"<option value=".($key+1).">
							".strtoupper ($value['lavado'])."
							</option>";
					}
					echo "
					</select><br />";
				}
			}
			else
				echo "<h5 class='white'>EL CORTE AÚN NO EXISTENTE EN LA BASE DE DATOS</h5>";
		}
	}

	public function rootValida($cadena=null)
	{
		$folio=explode("_",$cadena)[0];
		$carga=explode("_",$cadena)[1];
		if($carga==-1)
			echo "<h5 class='white'>Seleccione un opción válida</h5>";
		else
			echo "<input type='submit' name='orden' id='orden' value='Aceptar'/>";
	}

	public function gestionMarcas($cliente=null)
	{
		if($cliente==-1)
			echo "<label class='bootstrap'>Escoja el cliente</label>";
		else
		{
			$this->load->model('clienteHasMarca');
			$query=$this->clienteHasMarca->get($cliente);
			echo "<select class='bootstrap' name='marca' id='marca'>";
			foreach ($query as $key => $value)
			{
				echo "<option value='".$value['idmarca']."'>".$value['marca']."</option>";
			}
			echo "</select>";
		}
	}

	public function agregarRenglon($numero)
	{
		$this->load->model('Marca');
		$marcas=$this->Marca->get();
		echo "<tr id='renglon$numero' name='renglon$numero'><td>Marca</td><td><select id='marca[$numero]' name='marca[$numero]'>";
		foreach ($marcas as $key => $value)
		{
			echo "<option value='".$value['id']."'>".$value['nombre']."</option>";
		}
		echo"</td><td><button type='button' name='eliminar$numero' id='eliminar$numero'>Eliminar</button></td></tr>";
	}

	public function agregarRenglonProduccion($numero)
	{
		echo "<tr name='renglon$numero' id='renglon$numero' ><td><center><select name='lavado[$numero]' id='lavado[$numero]' class='bootstrap'>";
		$this->load->model('Lavado');
		$lavados=$this->Lavado->get();
		foreach ($lavados as $key => $value)
			echo "<option value='".$value['id']."'>".$value['nombre']."</option>";
		echo "</select></center></td><td><center><select name='proceso_seco[$numero][]' id='proceso_seco[$numero][]' class='bootstrap' multiple='true' size='3'>";
		$this->load->Model('ProcesoSeco');
		$procesos=$this->ProcesoSeco->get();
		foreach ($procesos as $key => $value)
			echo "<option value='".$value['id']."'>".$value['nombre']."</option>";
		echo "</select></center></td><td><button type='button' name='eliminar$numero' id='eliminar$numero'>Eliminar</button></td></tr>";
	}
}
