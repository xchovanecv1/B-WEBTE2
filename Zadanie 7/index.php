<?php 
require_once "Page.php";

$page->setHeader("Mashup");
$page->renderHeader();

function kelvin_to_celsius($given_value)
{
  $celsius=$given_value-273.15;
  return $celsius ;
}

?>
<h2>Aktuálne počasie</h2>

              <div class="row">
                <div class="field">
                      <span>Oblaky:</span>

                    </div>
                    <div class="field">
                      <?php echo $_SESSION['weather_data']->{"weather"}[0]->{"main"} . " -> ". $_SESSION['weather_data']->{"weather"}[0]->{"description"}; ?>
                    </div>
              </div>
               <div class="row">
                  <div class="field">
                      <span>Teplota:</span>

                    </div>
                    <div class="field">
                      <?php echo kelvin_to_celsius($_SESSION['weather_data']->{"main"}->{"temp"}) . " °C"; ?>
                    </div>
              </div> 
               <div class="row">
                <div class="field">
                      <span>Teplota:</span>

                    </div>
                    <div class="field">
                      <?php echo $_SESSION['weather_data']->{"main"}->{"humidity"} . " %"; ?>
                    </div>
              </div>
               <div class="row">
              <div class="field">
                      <span>Tlak:</span>

                    </div>
                    <div class="field">
                      <?php echo $_SESSION['weather_data']->{"main"}->{"pressure"} . " hPa"; ?>
                    </div>
              </div>
              <?php

$page->renderFooter();
?>