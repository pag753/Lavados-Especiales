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

	public function rootReporte($folio=null)
	{
		if($folio==null)
			echo "<h5>Favor de insertar el número de folio</h5>";
		else
		{
			$this->load->model('corte');
			$query=$this->corte->getByFolio($folio);
			if(count($query)!=0)
				echo "<input type='submit' value='aceptar'/>";
			else
				echo "<h5>EL CORTE AÚN NO EXISTENTE EN LA BASE DE DATOS</h5>";
		}
	}

	public function rootCargas($folio=null)
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
					echo "<h5>CORTE AÚN NO AUTORIZADO</h5>";
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
				echo "<h5>EL CORTE AÚN NO EXISTENTE EN LA BASE DE DATOS</h5>";
		}
	}

	public function rootValida($cadena=null)
	{
		$folio=explode("_",$cadena)[0];
		$carga=explode("_",$cadena)[1];
		if($carga==-1)
			echo "<h5>Seleccione un opción válida</h5>";
		else
			echo "<input type='submit' name='orden' id='orden' value='Aceptar'/>";
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

	//GESTIÓN
	public function gestionMarcas($cliente=null)
	{
		if ($_SESSION['id']!=2) redirect('/');
		if($cliente==-1) echo "<label>Escoja primero el cliente</label>";
		else
		{
			$this->load->model('clienteHasMarca');
			$query=$this->clienteHasMarca->get($cliente);
			echo "<select class='form-control' name='marca' id='marca'>";
			foreach ($query as $key => $value)
				echo "<option value='".$value['idmarca']."'>".$value['marca']."</option>";
			echo "</select>";
		}
	}

	public function salidaInterna($folio=null)
	{
		if ($_SESSION['id']!=2) redirect('/');
		if($folio==null)
			echo "<h5>Ingresa el número de folio</h5>";
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
						<div class='form-group row'>
							<label for='Piezas' class='col-3 col-form-label'>Piezas</label>
							<div class='col-9'>
								<input type='text' name='piezas' id='piezas' readonly='true' class='form-control' value='".$query2[0]['piezas']."'></input>
							</div>
						</div>
						<div class='form-group row'>
							<label for='Muestras' class='col-3 col-form-label'>Muestras</label>
							<div class='col-9'>
								<input type='number' required='true' name='muestras' id='muestras' placeholder='Inserte muestras' class='form-control'></input>
							</div>
						</div>
						<div class='form-group row'>
		          <div class='col-12'>
		            <table name='tabla' id='tabla' class='table'>
									<thead>
										<tr>
											<th>Lavado</th>
											<th>Piezas</th>
											<th>Abrir con</th>
										</tr>
									</thead>
									<tbody>";
						$this->load->model('corteAutorizadoDatos');
						$autorizado=$this->corteAutorizadoDatos->joinLavado($folio);
						foreach ($autorizado as $key => $value)
						{
							echo "
							<tr>
							<td>".$value['id_carga']." ".strtoupper($value['nombre'])."</td>
							<td><input type='number' name='piezas_parcial$key' id='piezas_parcial$key' class='form-control' placeholder='Inserte # de piezas' required='true' class='form-control'/></td>";
								$query10=$this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros($folio,$key+1);
								echo "
								<td>
									<select name='primero[$key]' class='form-control'>";
										foreach ($query10 as $key => $value)
											echo "<option value='".$value['idproceso']."'>".$value['proceso']."</option>";
										echo "
										</select>
										</td>
										</tr>";
						}
						echo "</tbody></table>
						<input type='submit' class='btn btn-primary' value='Aceptar'/>";
						echo "<input type='hidden' name='fechabd' id='fechabd' value='".$query2[0]['fecha_entrada']."'/>";
						echo "<input type='hidden' name='cargas' id='cargas' value='".count($autorizado)."'/>";
					}

					else
						echo "<h5>El corte ya tiene salida interna</h5>";
				}
				else
					echo "<h5>El corte no se ha autorizado</h5>";
			}
			else
				echo "<h5>El corte no está en la base de datos</h5>";

		}
	}

	//PRODUCCIÓN
	public function autorizacionCorte($folio=null)
	{
		if ($_SESSION['id']!=3) redirect('/');

		if($folio==null)
			echo "<h3>Escriba el número del corte</h3>";
		else
		{
			$this->load->model('corte');
			$resultado=$this->corte->getByFolio($folio);
			if(count($resultado)==0)
				echo "<h3>El corte no existe en la base de datos</h3>";
			else
			{
				$this->load->model('corteAutorizado');
				$resultado2=$this->corteAutorizado->getByFolio($folio);
				if(count($resultado2)!=0)
					echo "<h3>El corte ya fue autorizado</h3>";
				else
					echo "<input type='submit' class='btn btn-primary' value='Aceptar'/>";
			}
		}
	}

	public function agregarRenglonProduccion($numero)
	{
		if ($_SESSION['id']!=3) redirect('/');
		echo "<tr name='renglon$numero' id='renglon$numero' ><td><center><select name='lavado[$numero]' id='lavado[$numero]' class='form-control'>";
		$this->load->model('Lavado');
		$lavados=$this->Lavado->get();
		foreach ($lavados as $key => $value)
			echo "<option value='".$value['id']."'>".$value['nombre']."</option>";
		echo "</select></center></td><td><center><select name='proceso_seco[$numero][]' id='proceso_seco$numero' class='form-control' multiple='multiple' size='3'>";
		$this->load->Model('ProcesoSeco');
		$procesos=$this->ProcesoSeco->get();
		foreach ($procesos as $key => $value)
			echo "<option value='".$value['id']."'>".$value['nombre']."</option>";
		echo "</select></center></td><td><button type='button' name='eliminar$numero' id='eliminar$numero' class='btn btn-danger'>Eliminar</button></td></tr>";
	}

	//OPERARIO Y OPERARIO VALIDA
	public function operarioCargas($folio=null)
	{
		if ($_SESSION['id']!=4 && $_SESSION['id']!=6) redirect('/');
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
					echo "<h5>CORTE AÚN NO AUTORIZADO</h5>";
				else
				{
					echo"
					<label for='carga' class='col-3 col-form-label'>Carga</label>
					<div class='col-9'>
						<select name='carga' id='carga' class='form-control'>
							<option value=-1>Seleccione la carga</option>";
					foreach ($query as $key => $value)
					{
						echo
							"<option value=".($key+1).">".strtoupper ($value['lavado'])."</option>";
					}
					echo "</select></div>";
				}
			}
			else
				echo "<h5>EL CORTE AÚN NO EXISTENTE EN LA BASE DE DATOS</h5>";
		}
	}

	public function operarioProcesos($cadena=null)
	{
		if ($_SESSION['id']!=4 && $_SESSION['id']!=6) redirect('/');
		$folio=explode("_",$cadena)[0];
		$carga=explode("_",$cadena)[1];
		if($carga==-1)
					echo "<h5>Escoja una opción válida</h5>";
		else
		{
			$this->load->model('corteAutorizadoDatos');
			$query=$this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros3($folio,$carga);
			if(count($query)==0)
			{
				echo "<h5>No hay procesos disponibles</h5>";
			}
			else
			{
				echo "
				<label for='proceso' class='col-3 col-form-label'>Proceso</label>
				<div class='col-9'>
					<select name='proceso' id='proceso' class='form-control'>
						<option value=-1>Seleccione el proceso</option>";
				foreach ($query as $key => $value)
				{
					echo "
					<option value='".$value['idproceso']."'>
					".strtoupper($value['proceso'])."
					</option>";
				}
				echo "</select></div>";
			}
		}
	}

	public function operarioValida($cadena=null)
	{
		if ($_SESSION['id']!=4 && $_SESSION['id']!=6) redirect('/');
		$folio=explode("_",$cadena)[0];
		$carga=explode("_",$cadena)[1];
		$proceso=explode("_",$cadena)[2];
		if($proceso==-1)
			echo "<h5>Seleccione un opción válida</h5>";
		else
		{
			$this->load->model('corteAutorizadoDatos');
			$query=$this->corteAutorizadoDatos->joinLavadoProcesosCargaNoCeros2($folio,$carga,$proceso);
			if($query[0]['status']==1)
			{
				echo "<div class='col-12'><input type='submit' class='btn btn-primary' value='Aceptar'/></div>";
				echo "<input type='hidden' name='piezas' id='piezas' value='".$query[0]['piezas']."'/>";
				echo "<input type='hidden' name='nombreCarga' id='nombreCarga' value='".$query[0]['lavado']."'/>";
				echo "<input type='hidden' name='nombreProceso' id='nombreProceso' value='".$query[0]['proceso']."'/>";
				echo "<input type='hidden' name='idlavado' id='idlavado' value='".$query[0]['idlavado']."'/>";
				echo "<input type='hidden' name='orden' id='orden' value='".$query[0]['orden']."'/>";
			}
			else
				echo "<h5>Éste proceso no está disponible</h5>";
		}
	}

	//ADMINISTRACIÓN
	public function costosAdministracion($folio=null)
	{
		if ($_SESSION['id']!=1) redirect('/');
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
					echo "<h5>Corte aún no autorizado</h5>";
				else
				{
					echo "<label for='carga' class='col-3 col-form-label'>Carga</label>";
					echo "<div class='col-9'>";
					echo "<select name='carga' id='carga' class='form-control'>";
					foreach ($query as $key => $value)
						echo"<option value=".($key+1).">".strtoupper ($value['lavado'])."</option>";
					echo "</select></div>";
					echo "<div class='col-12'>";
					echo "<input type='submit' class='btn btn-primary' value='Aceptar'/>";
					echo "</div>";
				}
			}
			else
				echo "<h5>El corte aún no existe en la base de datos</h5>";
		}
	}

	public function existeUsuario($nombre=null)
	{
		if ($_SESSION['id']!=1) redirect('/');
		$this->load->model("Usuarios");
		return $this->Usuarios->exists($nombre);
	}
}
