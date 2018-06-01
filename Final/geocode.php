<?php
require_once "Page.php";

// function to geocode address, it will return false if unable to geocode address
function geocode($address){
 
    // url encode the address
    $address = urlencode($address);
     
    // google map geocode api url
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$address}&key=".GOOGLE_MAP_API;
 
    // get the json response
    $resp_json = file_get_contents($url);
     
    // decode the json
    $resp = json_decode($resp_json, true);
 
    // response status will be 'OK', if able to geocode given address 
    if($resp['status']=='OK'){
 
        // get the important data
        $lati = isset($resp['results'][0]['geometry']['location']['lat']) ? $resp['results'][0]['geometry']['location']['lat'] : "";
        $longi = isset($resp['results'][0]['geometry']['location']['lng']) ? $resp['results'][0]['geometry']['location']['lng'] : "";
        $formatted_address = isset($resp['results'][0]['formatted_address']) ? $resp['results'][0]['formatted_address'] : "";
         
        // verify if data is complete
        if($lati && $longi && $formatted_address){
         
            // put the data in the array
            $data_arr = array();            
             
            array_push(
                $data_arr, 
                    $lati, 
                    $longi, 
                    $formatted_address
                );
             
            return $data_arr;
             
        }else{
            return false;
        }
         
    }
 
    else{
        echo "<strong>ERROR: {$resp['status']}</strong>";
        return false;
    }
}

$query = "SELECT ID, CONCAT(`Street`,\" \",`Number`,\", \",`ZIP`,\", \",`City`) as 'Address' FROM `address` WHERE Geo IS NULL or Geo = '' LIMIT ".GOOGLE_MAP_MAX_REQUESTS.";";

if($res = $mysqli->query($query))
{
	while($row = $res->fetch_assoc())
	{
		$data = geocode($row['Address']);

		$formated = array();
		$formated['lat'] = $data[0];
		$formated['lng'] = $data[1];

		$json_out = json_encode($formated);

		if($addrchng = $mysqli->prepare("UPDATE `address` SET `Geo` = ? WHERE `address`.`ID` = ?;")) {

	          $addrchng->bind_param("si",$json_out, $row['ID']);
	          if($addrchng->execute())
	          {

	          }
	      }
	}
}

?>