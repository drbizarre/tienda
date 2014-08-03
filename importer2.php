<?php

$mysqli = new mysqli("localhost", "root", "", "tienda");
$result = $mysqli->query("SELECT DISTINCT VendorNameDisplayed FROM vendorname ORDER BY VendorNameDisplayed");
$c=1;
while ($row = $result->fetch_assoc()) {
	echo $c." - ".$row["VendorNameDisplayed"];
	echo "<br>";
	$c++;
	//$mysqli->query("INSERT INTO fabricantes (nombre,codigo) VALUES ('".$row['VendorNameDisplayed']."','".$row['VendorNumber']."')");
}
/*
$mysqli = new mysqli("localhost", "root", "", "tienda");
$result = $mysqli->query("SELECT DISTINCT CodeLevel3,DescriptionLevel2,DescriptionLevel1,DescriptionLevel3 FROM categories order by DescriptionLevel3 asc");
while ($row = $result->fetch_assoc()) {
	$result0 = $mysqli->query("SELECT * FROM categorias WHERE nombre ='".$row['DescriptionLevel2']."'");
	$papa = $result0->fetch_assoc();
	//echo $papa["id"]." - ".$row['DescriptionLevel3'];
	//echo "<br>";
	$mysqli->query("INSERT INTO categorias (nombre,parent_id) VALUES ('".$row['DescriptionLevel3']."',".$papa["id"].")");
}

*/
function httpsPost($Url, $strRequest)
{
	// Initialisation
	$ch=curl_init();
	// Set parameters
	curl_setopt($ch, CURLOPT_URL, $Url);
	// Return a variable instead of posting it directly
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, true);
	// Active the POST method
	curl_setopt($ch, CURLOPT_POST, 1) ;
	// Request
	curl_setopt($ch, CURLOPT_POSTFIELDS, $strRequest);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	// execute the connexion
	$result = curl_exec($ch);
	// Close it
	curl_close($ch);
	return $result;
}

function getIngramPrice($products)
{
		$string = '<?xml version="1.0" encoding="ISO-8859-1"?><PNARequest><Version>2.0</Version>';
		$string.= '<TransactionHeader><SenderID>OMICRON</SenderID>';
		$string.= '<ReceiverID>INGRAM MICRO</ReceiverID>';
		$string.= '<CountryCode>MX</CountryCode><LoginID>NvOAci1RSe</LoginID><Password>LoDTa310114</Password>';
		$string.= '<TransactionID>{A0DEA52B-341F-40C3-9C80-77A4A84F9EB7}</TransactionID></TransactionHeader>';
		for($i=0;$i<sizeof($products);$i++){
			$string .= '<PNAInformation SKU="'.$products[$i]["sku"].'" Quantity="1" ReservedInventory="N"/>';
		}
		$string.= '<ShowDetail>0</ShowDetail></PNARequest>';
		$url = 'https://newport.ingrammicro.com/MUSTANG';

		$strRequest = utf8_encode($string);
		$Response = httpsPost($url, $strRequest);
		// validate xml response from pcg
		
		$doc = new DOMDocument();
		$doc->loadXML($Response);
		$pnaResp = "PNAResponse.xml";
		$doc->save("$pnaResp");

		
		// loocking for errors
		$ErrorStatus = $doc->getElementsByTagName("ErrorStatus");
		$ErrorNumber = $ErrorStatus->item(0)->getAttribute("ErrorNumber");
		if(strlen($ErrorNumber)<=0){
			$productos =  array();
			// No errors
			$PriceAndAvailability = $doc->getElementsByTagName("PriceAndAvailability");
			  foreach( $PriceAndAvailability as $PriceAndAvailability ){
				  
				  $TotAvail = 0;
				  $Prices = $PriceAndAvailability->getElementsByTagName("Price");
				  $sku = $PriceAndAvailability->getAttribute("SKU");

				  $Price = @$Prices->item(0)->nodeValue;
				  $Parts = $PriceAndAvailability->getElementsByTagName("ManufacturerPartNumber");
				  
				  $Branchs = $PriceAndAvailability->getElementsByTagName("Branch");
				  $Avails = $PriceAndAvailability->getElementsByTagName("Availability");
				  
				  for ($i = 0; $i < $Branchs->length; ++$i) {
						$TotAvail += $Avails->item($i)->nodeValue;
				  }
				  				  
				  //$Price = str_replace(",",".",$Price);
				  // Agregamos el margen del producto
				  $FinalPrice = $Price;
				  //$id = getProductId($sku);

				 
					for($i=0;$i<sizeof($products);$i++){
						if ($products[$i]["sku"]==$sku) {
							$utilidad = ($products[$i]["utilidad"]/100)+1;
							$precio_mas_utilidad = $FinalPrice * $utilidad;
							$id = $products[$i]["id"];
							break(1);

						}
					}
				  array_push($productos,array("id"=>$id,"sku"=>$sku,"precio"=>$precio_mas_utilidad,"disponibilidad"=>$TotAvail));
				  unset($id);
				  unset($TotAvail);
				  unset($sku);
			  } //for each
		}
		else{
			mail('oscar@creactivo.mx', 'storefront pcsmart', $ErrorStatus->item(0)->nodeValue." - ".date("Y-m-d h:m:s"));
			
			die($ErrorStatus->item(0)->nodeValue);
		}
		return $productos;
}			
	
	/*		
$result = mysql_query("SELECT product_id as id, sku, (SELECT category.utilidad FROM category INNER JOIN product_to_category ON product_to_category.category_id = category.category_id where product_to_category.product_id = product.product_id
) as utilidad FROM product");             
            if (!$result) {
                die('Invalid query: ' . mysql_error());
            }		
            else
            {
				$productos = array();
				$total_productos = mysql_num_rows($result);
				
				$numero_de_arreglos = ceil($total_productos/100);
                while ($row = mysql_fetch_assoc($result)) {
					array_push($productos,array("id"=>$row["id"],"sku"=>$row["sku"],"utilidad"=>$row["utilidad"]));
                }
				$muchos = array_chunk($productos,100);

				$time_start = microtime(true);
				for($j=0;$j<sizeof($muchos);$j++){
					$products_consultados = getIngramPrice($muchos[$j]);
					for($k=0;$k<=sizeof($products_consultados);$k++){
						if (isset($products_consultados[$k]["precio"]) && floatval($products_consultados[$k]["precio"])>0) {
							if ($products_consultados[$k]["disponibilidad"]>0) {
								mysql_query("UPDATE product SET stock_status_id = 7, status=1, price = ".$products_consultados[$k]["precio"].", quantity = ".$products_consultados[$k]["disponibilidad"]." WHERE product_id = ".$products_consultados[$k]['id']);
							}else{
								mysql_query("UPDATE product SET stock_status_id = 5, status=0, price = ".$products_consultados[$k]["precio"].", quantity = ".$products_consultados[$k]["disponibilidad"]." WHERE product_id = ".$products_consultados[$k]['id']);
							}
							
						}else{
							if (isset($products_consultados[$k]['id'])) {
								mysql_query("UPDATE product SET stock_status_id = 5, status=0 WHERE product_id = ".$products_consultados[$k]['id']);	
							}
						}
					
					}
					
				}		
}
mysql_close();
*/
?>    
