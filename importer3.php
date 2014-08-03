<?php
$mysqli = new mysqli("localhost", "root", "", "tienda");
$result = $mysqli->query("SELECT * FROM pricefile");
$counter = 1;
while ($row = $result->fetch_assoc()) {
	$result0 = $mysqli->query("SELECT id FROM categorias WHERE codigo ='".$row['NewCPUCode']."'");
	$result1 = $mysqli->query("SELECT id FROM fabricantes WHERE codigo ='".$row['VendorNbr']."'");
	if ($result0) {
		$cat = $result0->fetch_assoc();
	}
	if ($result1) {
		$fab = $result1->fetch_assoc();
	}
	$nombre = $row["ProductDescription1"]." ".$row["ProductDescription2"];
	$sku = $row["Sku"];
	$modelo = $row["PartNbr"];
	$upc = $row["UPC"];
	$precio = $row["Column1Price"];
	$oferta = ($row["SpecialPriceFlag"]=='P')?1:0;
	$precio_oferta = $row["SpecialPrice"];
	$fecha_inicio_oferta = str_replace("/", "-", $row["SpecialEffectiveDate"]);
	$fecha_termino_oferta = str_replace("/", "-", $row["SpecialExpirationDate"]);
	$disponibilidad = $row["QuantityAvailable"];
	$weight = $row["Weight"];
	$height = $row["Height"];
	$width = $row["Width"];
	$lenght = $row["ProductLength"];
	$id_fabricante = $fab["id"];
	$id_categoria = $cat["id"];
	$result2= $mysqli->query("INSERT INTO productos (nombre,sku,modelo,upc,precio,oferta,precio_oferta,fecha_inicio_oferta,fecha_termino_oferta,disponibilidad,weight,height,width,lenght,id_fabricante,id_categoria) VALUES ('".$nombre."','".$sku."','".$modelo."','".$upc."',".$precio.",".$oferta.",".$precio_oferta.",'".$fecha_inicio_oferta."','".$fecha_termino_oferta."',".$disponibilidad.",".$weight.",".$height.",".$width.",".$lenght.",".$id_fabricante.",".$id_categoria.")");
	if (!$result2) {
		echo $row["id"];
		echo "<br>";
	}
}

?>    
