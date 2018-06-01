<?php 
require_once "Page.php";

$page->setHeader("Mashup");
$page->renderHeader();

?>
			<div class="row">
					<div class="field">
	                    <span>IP Adresa:</span>

                  	</div>
                  	<div class="field">
                  		<?php echo $_SESSION['ip_data']->{"ip"}; ?>
                  	</div>
              </div>
			<div class="row">
					<div class="field">
	                    <span>Latitude:</span>

                  	</div>
                  	<div class="field">
                  		<?php echo $_SESSION['ip_data']->{"latitude"}; ?>
                  	</div>
              </div>
              <div class="row">
					<div class="field">
	                    <span>Longitude:</span>

                  	</div>
                  	<div class="field">
                  		<?php echo $_SESSION['ip_data']->{"longitude"}; ?>
                  	</div>
              </div>
              <div class="row">
					<div class="field">
	                    <span>Mesto:</span>

                  	</div>
                  	<div class="field">
                  		<?php echo (!empty($_SESSION['ip_data']->{"city"}) ? $_SESSION['ip_data']->{"city"} : no_city_coment); ?>
                  	</div>
              </div>
              <div class="row">
					<div class="field">
	                    <span>Štát:</span>

                  	</div>
                  	<div class="field">
                  		<?php echo $_SESSION['ip_data']->{"country_name"}; ?>
                  	</div>
              </div>
              <div class="row">
					<div class="field">
	                    <span>Hlavné mesto:</span>

                  	</div>
                  	<div class="field">
                  		<?php echo $_SESSION['ip_data']->{"location"}->{"capital"}; ?>
                  	</div>
              </div>

<?php 
$page->renderFooter();
?>