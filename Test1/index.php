<!DOCTYPE html>
<html>
<head>
	<title>Test 1</title>
</head>
<body>
	<?php 

		if(!empty($_POST['odoslat']) && !empty($_POST['year']))
		{
				if(is_numeric($_POST['year']))
				{
					$Mondays = array();
					$Fridays = array();
					for($i=1; $i <= 12; $i++)
					{
						$date = new DateTime($_POST['year']."-".$i."-1");
						$first_day = $date->format("w");

						$date2 = new DateTime($_POST['year']."-".$i."-13");
						$sec_day = $date2->format("w");

						$is_monday = ($first_day == "1" ? true : false);
						$is_friday = ($sec_day == "5" ? true : false);

						if($is_monday) $Mondays[] = $date->format("F (m)");
						if($is_friday) $Fridays[] = $date2->format("F (m)");
					}

					$size = max(sizeof($Mondays), sizeof($Fridays));
					echo '

						<table>
							<thead>
								<tr><th colspan="2">Prebieha výpočet pre rok: '.$_POST['year'].'</th></tr>
								<tr><th>Pondelok 1.</th><th>Piatok 13.</th></tr>
							</thead>	
							<tbody>
					';
					for($d = 0; $d < $size; $d++)
					{
						echo "
							<tr>	
								<td>".(!empty($Mondays[$d]) ? $Mondays[$d] : "")."</td>
								<td>".(!empty($Fridays[$d]) ? $Fridays[$d] : "")."</td>
							</tr>
						";
					}
					echo '
							</tbody>
						</table>
						<br>
					';

				} else {
					echo "<p>Zadaný rok nie je v správnom formáte!</p>";
				}

		}
	?>

	<form action="" method="post">
		<input type="text" name="year">
		<input type="submit" name="odoslat" value="Odoslať">
	</form>

</body>
</html>