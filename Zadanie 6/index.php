<?php 
require_once "Page.php";

$page->setHeader("REST Rozhranie");
$page->renderHeader();

?>


      <div class="form-style text-center">
      		<a class="button" href="./def.php">Definícia API</a>
          <fieldset>
            <legend>Základné parametre REST rozhrania</legend>
              <div class="row">
					<div class="field">
	                    <label for="date" class="req"><span>Dátum:</span></label>
	                    <div>
	                    <input required type="date" id="date" name="date" value="">
	                    </div>
                  	</div>
                  	<div class="field">
	                    <label for="country" class="req"><span>Krajina:</span></label>
	                    <div>
	                    <select id="country">
							<option value="SK">Slovensko</option>
							<option value="CZ">Česká Republika</option>
							<option value="HU">Maďarsko</option>
							<option value="PL">Poľsko</option>
							<option value="AT">Rakúsko</option>
						</select>
	                    </div>
                  	</div>
              </div>
          </fieldset>
        </div>
        <div class="form-style text-center">
          <fieldset>
            <legend>Akcie</legend>
              <div class="row">
					<div class="field">
	                   
						<button id="named" class="button">Meniny</button>
                  	</div>
                  	<div class="field">
						<button id="sviat" class="button">Sviatky</button>

                  	</div>
              </div>
              <div class="row">
					<div class="field">
	                   
						<button id="memor" class="button">Pamätné dni</button>
                  	</div>
					<div class="field"></div>
              </div>

              <div class="row">
              		<div class="field">
	                    <label for="srchname" class="req"><span>Hľadané meno:</span></label>
	                    <input required type="text" id="srchname" name="srchname" value="">
                  	</div>
                  	<div class="field">
	                    <label for="addnm" class="req"><span>Pridané meno:</span></label>
	                    <input required type="text" id="addnm" name="addnm" value="">
                  	</div>
             </div>
             <div class="row">
              		
                  	<div class="field">
	                    <button id="nmsrch" class="button">Kedy má meniny ?</button>
                  	</div>

					<div class="field">
	                    
						<button id="addname" class="button">Pridať meniny</button>
                  	</div>

             </div>
          </fieldset>
        </div>
        <div class="form-style text-center">
          <fieldset>
            <legend>Výstup</legend>
				<div id="out"></div>
          </fieldset>
        </div>

<?php 
$page->renderFooter();
?>