<?php

	require_once 'controllers/header_navigation_controller.php';
	$controller = new HeaderNavigationController();

?>

<a href=".."><h1 style="color: #fff;">mpNotes</h1></a>
<div class="row">

    <?php
		foreach($controller->getHeaderButtons() as $button) {
			echo $button;
			echo '&nbsp;';
		}
    ?>

</div>
<script src="views/js/navigation_handler.js"></script>