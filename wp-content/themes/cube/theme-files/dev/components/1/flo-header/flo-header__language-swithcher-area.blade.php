<?php
	ob_start();
	ob_clean();
	dynamic_sidebar('header-translation');
	$header_translation = ob_get_clean();
?>
@if($header_translation)
  <div class="flo-header__language-switcher-area">
		{{$header_translation}}
  </div>
@endif
