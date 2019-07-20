<?php

	//print_r($_REQUEST);
	//exit;
	//echo base64_encode('2');
	//exit;
	session_start();
	if(empty($_SESSION['active']))
	{
		header('location: ../');
	}

	include "../../conexion.php";
	require_once '../pdf/vendor/autoload.php';
	use Dompdf\Dompdf;

	if(empty($_REQUEST['cl']) || empty($_REQUEST['f']))
	{
		echo "No es posible generar la factura.";
	}else{
		$codCliente = $_REQUEST['cl'];
		$noFactura = $_REQUEST['f'];
		$anulada = '';

		$query_config   = mysqli_query($conection,"SELECT * FROM configuracion");
		$result_config  = mysqli_num_rows($query_config);
		if($result_config > 0){
			$configuracion = mysqli_fetch_assoc($query_config);
		}


		$query = mysqli_query($conection,"SELECT f.fac_id, DATE_FORMAT(f.fac_fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(f.fac_fecha,'%H:%i:%s') as  hora, f.fac_tendero, f.estado,
												 v.usu_nombre as vendedor,
												 ten.ten_id, ten.ten_cedula, ten.ten_nombre, ten.ten_telefono,ten.ten_direccion
											FROM factura f
											INNER JOIN usuario v
											ON f.fac_usuario = v.usu_id
											INNER JOIN tendero ten
											ON f.fac_tendero = ten.ten_id
											WHERE f.fac_id = $noFactura AND f.fac_tendero = $codCliente  AND f.estado != 10 ");

		$result = mysqli_num_rows($query);
		if($result > 0){

			$factura = mysqli_fetch_assoc($query);
			$no_factura = $factura['fac_id'];

			if($factura['estado'] == 2){
				$anulada = '<img class="anulada" src="img/anulado.png" alt="Anulada">';
			}

			$query_productos = mysqli_query($conection,"SELECT p.pro_descripcion,dt.deFa_cantidad,dt.deFa_precioTotal,(dt.deFa_cantidad * dt.deFa_precioTotal) as precio_total
														FROM factura f
														INNER JOIN detallefactura dt
														ON f.fac_id = dt.deFa_idFactura
														INNER JOIN producto p
														ON dt.deFa_idProducto = p.pro_id
														WHERE f.fac_id = $no_factura ");
			$result_detalle = mysqli_num_rows($query_productos);

			ob_start();
		    include(dirname('__FILE__').'/factura.php');
		    $html = ob_get_clean();

			// instantiate and use the dompdf class
			$dompdf = new Dompdf();

			$dompdf->loadHtml($html);
			// (Optional) Setup the paper size and orientation
			$dompdf->setPaper('letter', 'portrait');
			// Render the HTML as PDF
			$dompdf->render();
			// Output the generated PDF to Browser
			$dompdf->stream('factura_'.$noFactura.'.pdf',array('Attachment'=>0));
			exit;
		}
	}

?>