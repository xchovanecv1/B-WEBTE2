<?php 
$_SERVER['REQUEST_URI_PATH'] = preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']);

$segments = explode('/', trim($_SERVER['REQUEST_URI_PATH'], '/'));

function find_parameter($param,$args,$def)
{
	for($i=0; $i < count($args); $i++)
	{
		if($args[$i] == $param)
		{
			if(!empty($args[$i+1]))
			{
				return urldecode($args[$i+1]);
			}
		}
	}
	return $def;
}

function find_zaznam($xmlobj,$param,$val,$strps = false)
{
	if(!empty($xmlobj))
	{
		foreach ($xmlobj->children() as $second_gen) {
			if($strps)
			{
				if(!empty($second_gen->{$param}) && (strpos($second_gen->{$param}, $val) !== false))
				{
					return $second_gen;
				}
			} else {
				if(!empty($second_gen->{$param}) && $second_gen->{$param} == $val)
				{
					return $second_gen;
				}
			}
			
		}
		
	}
	return NULL;
}

function find_value($xmlobj,$param)
{
	if(!empty($xmlobj))
	{
		if(!empty($xmlobj->{$param}))
		{
			return $xmlobj->{$param}->__toString();
		}
		
	}
	return NULL;
}

function update_zaznam(&$xmlobj,$id,$id_val,$change,$change_val)
{
	if(!empty($xmlobj))
	{
		foreach ($xmlobj->children() as $key => $second_gen) {
			if(!empty($second_gen->{$id}) && $second_gen->{$id} == $id_val)
			{
				$second_gen->{$change} = $change_val;
				return true;
			}
		}
		
	}
	return false;
}

function generate_output($params)
{
	if(is_array($params))
	{
		$params["links"] = array('rel' => "self", "href" => urldecode((isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") );

		return json_encode($params,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	}
	return "";
}

if(!empty($segments))
{
	$count = count($segments);

	if($count == 1) header("Location: ./index.php");

	if($count > 1)
	{
		if($segments[1] == 'api')
		{
			$xml = simplexml_load_file("./meniny.xml");
			if($xml !== false)
			{
				$country = strtoupper(find_parameter("krajina",$segments,"SK"));
				
				//meniny
				$cmd = find_parameter("meniny",$segments,"");
				if(!empty($cmd))
				{
					$zaznam = find_zaznam($xml,"den",$cmd);
					if($zaznam !== NULL)
					{

						if(!empty($_POST["meno"]))
						{
								
								$skd = find_value($zaznam,$country."d");
								$dayk = find_value($zaznam,"den");
								if(!empty($dayk))
								{
									header("HTTP/1.1 201 CREATED");
									
									if(empty($skd))
									{
										$skd = $_POST["meno"];
									}else{
										$skd .=", ".$_POST["meno"];
									}
									if(update_zaznam($xml,"den",$dayk,$country."d",$skd))
									{
										$xml->saveXML('./meniny.xml');
										$zaznam = find_zaznam($xml,"den",$cmd);
										$skd = find_value($zaznam,$country."d");
										echo "Added";
									} else {
										echo "Not added";
									}
									return;
								}
						}else{
							$val = find_value($zaznam,$country);
							$val_d = find_value($zaznam,$country."d");
							if($val !== NULL || $val_d != NULL)
							{
								// Pridanie noveho elementu do <SKd>
									header("HTTP/1.1 200 OK");
									$data = (!empty($val) ? (!empty($val_d) ? $val.", " : $val) : "") . (!empty($val_d) ? $val_d : "");
									echo generate_output(array("meniny"=>$data,"krajina"=>$country));
									return ;
							}
						} 

						{
							// zaznam neexistuje
							header("HTTP/1.1 404 Not Found");
							echo "Not found";
							return;
						}
					} else {
						// Resource not found

						header("HTTP/1.1 404 Not Found");
						echo "Not found";
						return;
					}
				}

				// zistovanie kedy ma dane meno meniny
				$cmd = find_parameter("meno",$segments,"");
				if(!empty($cmd))
				{
					$zaznam = find_zaznam($xml,$country,$cmd,true);
					$zaznam2 = find_zaznam($xml,$country."d",$cmd,true);
					if($zaznam !== NULL || $zaznam2 !== NUL)
					{
						$val = find_value($zaznam,"den");
						$val2 = find_value($zaznam2,"den");

						if($val !== NULL)
						{
							header("HTTP/1.1 200 OK");
							echo generate_output(array("datum"=>$val,"krajina"=>$country));
							return;
						} else if($val2 !== NULL){
							header("HTTP/1.1 200 OK");
							echo generate_output(array("datum"=>$val2,"krajina"=>$country));
							return;
						} else {
							// zaznam neexistuje
							header("HTTP/1.1 404 Not Found");
							echo "Not found";
							return;
						}
					} else {
						// Resource not found

						header("HTTP/1.1 404 Not Found");
						echo "Not found";
						return;
					}
				}

				// Vsetky sviatky v krajine
				$cmd = find_parameter("sviatky",$segments,"");
				if(!empty($cmd))
				{
					header("HTTP/1.1 200 OK");
					$ret_arr = array();
					foreach ($xml->children() as $second_gen) {
						$val = $second_gen->{$country."sviatky"};
						$day = $second_gen->{"den"};
						if(!empty($val) && !empty($day))
						{
							$ret_arr[] = array("den"=>$day->__toString(),"sviatok"=>$val->__toString());
						}

					}
					if(count($ret_arr))
					{
						echo generate_output(array("sviatky"=>$ret_arr,"krajina"=>$country));
							return;
					} else {
						header("HTTP/1.1 404 Not Found");
						echo "Holiday for country ".$country." not found";
							return;
					}

				}

				// Vsetky pamatne dni v krajine
				$cmd = find_parameter("pamatne",$segments,"");
				if(!empty($cmd))
				{
					header("HTTP/1.1 200 OK");
					$ret_arr = array();
					foreach ($xml->children() as $second_gen) {
						$val = $second_gen->{$country."dni"};
						$day = $second_gen->{"den"};
						if(!empty($val) && !empty($day))
						{
							$ret_arr[] = array("den"=>$day->__toString(),"sviatok"=>$val->__toString());
						}

					}
					if(count($ret_arr))
					{
						echo generate_output(array("pamatne"=>$ret_arr,"krajina"=>$country));
							return;
					} else {
						header("HTTP/1.1 404 Not Found");
						echo "Memorials for country ".$country." not found";
							return;
					}

				}
				/*
				foreach ($xml->children() as $second_gen) {
					echo "<pre>";
					var_dump($second_gen);

					echo "</pre>";
				}*/
				
				header("HTTP/1.1 404 Not Found");
				echo "Wrong api call";
				return;
			}

		} else { // unknow address
			header("HTTP/1.1 404 Not Found");
			echo "Wrong api call";
							return;
		}
	}
}

?>