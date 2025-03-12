<?php 

class Colorear
{

	private const ID_COLORES_TURQUESA = ['id_1', 'id_2', 'id_3', 'id_4','id_5','id_6','id_7','id_8','id_9', 'id_10', 'id_11', 'id_12'];

	private const CODIGO_COLORES_TURQUESA = ['#020e10', '#052b31', '#084852', '#0b6473', '#0e8194', '#119eb4', '#1cb6cf', '#64cedf', '#87dbe6', '#abe7ee', '#cff3f6', '#f3fffe'];

	private const ID_COLORES_NARANJA = ['id_13' ,'id_14' ,'id_15' ,'id_16' ,'id_17' ,'id_18' ,'id_19' ,'id_20' ,'id_21' ,'id_22' ,'id_23' ,'id_24'];

	private const CODIGO_COLORES_NARANJA = ['#3d2700' ,'#593a00' ,'#754c00' ,'#915f00' ,'#ad7100' ,'#c98400' ,'#db9615' ,'#e2a940' ,'#e9bb6a' ,'#f0ce94' ,'#f8e0bf' ,'#fff3e9'];

	private const ID_COLORES_MAJENTA = [];

	private const CODIGO_COLORES_MAJENTA = [];

	private array $id_de_colores = [];

	private array $codigo_de_colores = [];

	private array $datos_alfabeticos = [];

	private array $datos_numericos = [];

	private array $matriz_intervalos_a = [];

	private array $matriz_intervalos_b = [];

	private array $matriz_indice_de_colores = [];

	private int $color = 0; 

	private bool $btn_enviar = false;

	public function __construct()
	{

		/*Separamos por coma el estring obtenido y lo almacenamos en el array*/
		if(isset($_POST['datos_alfabeticos']))
		{

			$this->datos_alfabeticos = explode(",", $_POST['datos_alfabeticos']);

		}

		/*Separamos por espacios el string obtenido y almacenamos los numeros en un array*/
		if(isset($_POST['datos_numericos']))
		{

			$this->datos_numericos = explode(" ", $_POST['datos_numericos']);
			
		}

		if(isset($_POST['color']))
    	{

    		$this->color = $_POST['color'];

    	}

    	if(isset($_POST['btn_enviar']))
    	{

    		$this->btn_enviar = $_POST['btn_enviar'];

    	}

	}//fin constructor


	private function devolverMatrizConValoresDecimales(array $matriz_con_valores_decimales)
	{

		//Declaramos nueva matriz.
		$matriz = [];

		for($i = 0; $i < count($matriz_con_valores_decimales); $i++)
		{

			/*Remplazamos la coma por el punto, para qué, al obtener los valores decimales de cada elemento del array estos no trunquen la parte decimal*/
			$matriz_con_valores_decimales = str_replace(',', '.', $matriz_con_valores_decimales);

			//Remplazamos los valores de tipo string por valores de tipo decimal y lo almacenamos en la matriz.
			array_push($matriz, doubleval($matriz_con_valores_decimales[$i]));

		}//fin bucle for

		return $matriz;

	}//fin function devolverMatrizConValoresDecimales


	private function imprimirEscalaDeColores(float $intervalo_a, float $intervalo_b, float $amplitud, int $numero_de_intervalos)
	{

		/*Almacenamos los ids y codigos según el color que haya seleccionado el usuario
			
			1_ Turquesa.
			2_Naranja.
			3_Morado.
		*/
		switch($this->color)
		{
			case '1':
				
				$this->id_de_colores = self::ID_COLORES_TURQUESA;

				$this->codigo_de_colores = self::CODIGO_COLORES_TURQUESA;

			break;
			
			case '2':
				
				$this->id_de_colores = self::ID_COLORES_NARANJA;

				$this->codigo_de_colores = self::CODIGO_COLORES_NARANJA;

			break;

			case '3':
				
				$this->id_de_colores = self::ID_COLORES_MAJENTA;

				$this->codigo_de_colores = self::CODIGO_COLORES_MAJENTA;

			break;
			
		}//fin switch

		/*El bule se ejecutará tantas veces como intervalos haya*/
		for($i = 0; $i < $numero_de_intervalos; $i++)
		{

			//Insertamos los valores de intervalos en sus respectivas matrices.
			array_push($this->matriz_intervalos_a, $intervalo_a);

			array_push($this->matriz_intervalos_b, $intervalo_b);

			//Imprimímos las filas de las tablas.
			?>
			<tr>
		
				<td><?php echo '( ' . number_format($intervalo_a, 2, ',', '.') . ' - ' . number_format($intervalo_b, 2, ',', '.') . ' )'; ?></td>

				<td id="<?php echo $this->id_de_colores[$i]; ?>"><?php echo $this->codigo_de_colores[$i]; ?></td>

			</tr>
			<?php

			//Sumamos la amplitud a los intervalos para obtener los siquientes intervalos.
			$intervalo_a += $amplitud;

			$intervalo_b += $amplitud;

			//Redondeamos el intervalo a a 2 cifras.
			$intervalo_a = round($intervalo_a, 2);

			//Redondeamos el intervalo b hacia arriba.
			$intervalo_b = round($intervalo_b, 2, PHP_ROUND_HALF_UP);

		}//fin bucle for
		
	}//fin function imprimirEscalaDeColores


	public function imprimirItemsPorColores()
	{

		$array_asoc = array_combine($this->datos_alfabeticos, $this->datos_numericos);

		arsort($array_asoc);

		$tamaño_array = count($this->matriz_intervalos_b);

		$ultima_vuelta_ciclo_for = $tamaño_array - 1;


		for ($i = $ultima_vuelta_ciclo_for; $i > -1; $i--)
		{ 
			
			foreach ($array_asoc as $key => $value)
			{
				
				if($i === $ultima_vuelta_ciclo_for)
				{

					if($value >= $this->matriz_intervalos_a[$i])
					{

						?><tr>
							
							<td><?php echo $key; ?></td>

							<td><?php echo number_format(floatval($value), 2, ',', '.'); ?></td>

							<td id="<?php echo $this->id_de_colores[$i]; ?>"><?php echo  $this->codigo_de_colores[$i]; ?></td>

						</tr><?php

						continue;

					}//fin if($value >= $this->matriz_intervalos_a[$i])

				}else//fin if($i === $ultima_vuelta_ciclo_for)
				{

					if($value >= $this->matriz_intervalos_a[$i] AND $value <= $this->matriz_intervalos_b[$i])
					{

						?><tr>
							
							<td><?php echo $key; ?></td>

							<td><?php echo number_format(floatval($value), 2, ',', '.'); ?></td>

							<td id="<?php echo $this->id_de_colores[$i]; ?>"><?php echo  $this->codigo_de_colores[$i]; ?></td>

						</tr><?php

						continue;

					}//fin if($value >= $this->matriz_intervalos_a[$i] AND $value <= $this->matriz_intervalos_b[$i])

				}//fin else

			}//fin foreach	

		}//fin bucle for

		//Reiniciamos los valores
		$this->matriz_intervalos_a = [];

		$this->matriz_intervalos_b = [];

		$this->id_de_colores = [];

		$this->codigo_de_colores = [];

	}//fin function imprimirItemsPorColores


	public function dibujarEscala()
	{

		$valor_maximo = 0;

		$valor_minimo = 0;

		$intervalo_a = 0;

		$intervalo_b = 0;

		$rango = 0;

		$amplitud = 0;

		$numero_de_intervalos = 0;

		$cantidad_datos_numericos = count($this->datos_numericos);

		$cantidad_datos_alfabeticos = count($this->datos_alfabeticos);

		//Detectamos cuando se presiona el boton del formulario
		if($this->btn_enviar)
		{

			//Verificamos que los datos necesarios para realizar la operacion no estén vacios
			if(!empty($this->datos_alfabeticos) AND !empty($this->datos_numericos) AND !empty($this->color))
			{

				//Verificamos que la cantidad de elementos en ambas matrices sean iguales
				if($cantidad_datos_numericos === $cantidad_datos_alfabeticos)
				{
					//Obtenemos la matriz con valores decimales.
					$this->datos_numericos = $this->devolverMatrizConValoresDecimales($this->datos_numericos);

					//Calculámos el número de intervalos.
					$numero_de_intervalos = 1 + (3.322 * log(count($this->datos_numericos)));

					//Redondeamos hacia arriba el numero de intervalos.
					$numero_de_intervalos = round($numero_de_intervalos, 0, PHP_ROUND_HALF_UP);

					//Obtenemos el mayor valor de la matriz y lo almacenamos.
					$valor_maximo = max($this->datos_numericos);

					//Obtenemos el menor valor de la matriz y lo almacenamos.
					$valor_minimo = min($this->datos_numericos);

					//Calculámos el rango, restando el valor minimo al valor maximo de la matriz de datos_numericos.
					$rango = ($valor_maximo - $valor_minimo);

					//Calculamos la amplitud en funcion al rango y la cantidad de intervalos.
					$amplitud = ($rango / $numero_de_intervalos);

					//Asignamos el inicio del intervalo, redondeanco hacia abajo el valor minimo.
					$intervalo_a = round($valor_minimo, 2, PHP_ROUND_HALF_DOWN);

					//Asignamos el intervalo b, que se obtiene sumando la amplitud al intervalo a.
					$intervalo_b = ($intervalo_a + $amplitud);

					//Redondeamos el intervalo b hacia arriba
					$intervalo_b = round($intervalo_b, 2, PHP_ROUND_HALF_UP);

					//Llamamos a la funcion que imprimira la escala en pantalla.
					$this->imprimirEscalaDeColores($intervalo_a, $intervalo_b, $amplitud, $numero_de_intervalos);

				}else//fin if(count($this->datos_numericos) === count($this->datos_alfabeticos))
				{

					/*Si la cantidad de elementos en la matriz datos_alfabeticos difiere de la cantidad de elementos de datos_numéricos, se imprime mensaje de error.*/
					echo '<script>alert("La cantidad de datos alfabeticos, debe coincidir con la cantidad de datos numéricos")</script>';

				}//fin else if(count($this->datos_numericos) === count($this->datos_alfabeticos))

			}//fin if(!empty($this->datos_alfabeticos) AND !empty($this->datos_numericos)) 

		}//fin if($this->btn_enviar)
		
	}//fin function dibujarEscala

}//fin class Colorear

?>