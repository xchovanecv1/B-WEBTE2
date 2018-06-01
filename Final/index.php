<?php
require_once 'Page.php';

$page->setHeader("Úvodná stránka");

$page->renderHeader();

display_errors();
// Author: Peter
// Task #13 - Send nudes...news (admin only)
if(check_access_role(ROLE_ADMIN,true)){
    require_once 'newsletterSubmit.php';
}


if(check_access_role(ROLE_USER,true))
{
	// Author: Peter
	// Task #13 - Do you want newsletter?
	require_once 'newsletterSubscribe.php';
} else {

	?>
	<div class="col-md-12">
        <div class="box box-primary">
        	<div class="col-md-6">
        		<a class="btn btn-block btn-success" href="./">Mapa používateľov</a>
        	</div>
        	<div class="col-md-6">
        		<a class="btn btn-block btn-success" href="./?map=schools">Mapa škôl</a>
        	</div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box box-primary">
            
	<?php
	if(!empty($_GET['map']))
	{
		if($_GET['map'] == 'schools')
		{
			echo '
				<script type="text/javascript" src="show_schools.js"></script>
				<div class="box-header with-border">
	                <h3 class="box-title">Mapa škôl zaregistrovaných používateľov</h3>
	            </div>
                <div class="box-body">

					<div class="form-group">
                        <div id="map" style="width: 100%; height: 100%; display: block;min-height: 350px;"></div>
                        <div id="pano"></div>
                    </div>
                </div>

			';
		}
	} else {
		echo '
				<script type="text/javascript" src="show_residence.js"></script>
				<div class="box-header with-border">
	                <h3 class="box-title">Mapa adries zaregistrovaných používateľov</h3>
	            </div>
                <div class="box-body">

					<div class="form-group">
                        <div id="map" style="width: 100%; height: 100%; display: block;min-height: 350px;"></div>
                        <div id="pano"></div>
                    </div>
                </div>

			';
	}

	?>
		</div>
	</div>
<script  src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_API; ?>&libraries=places&callback=initMap" async defer>
</script>
	<?php



}



$page->renderFooter();
?>
