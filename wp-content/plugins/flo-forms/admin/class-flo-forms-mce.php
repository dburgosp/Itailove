<?php
if(!class_exists('Flo_Forms_Mce')){
    class Flo_Forms_Mce{

    	function flo_forms_add_button( $plugins ) {

    		// floFormsPopup - this is the MCE plugun slug, it should be changed for other plugins
			$plugins['floFormsPopup'] = plugin_dir_url( dirname( __FILE__ ) ).'admin/js/plugin.js';

			return $plugins;
		}

		function flo_forms_register_button( $buttons ) {
			// insert our button before the wp_adv (toolbar) toggle button
			$key = array_search( 'wp_adv', $buttons );
			$inserted = array( 'floFormsPopup' );
			array_splice( $buttons, $key, 0, $inserted );

			return $buttons;
		}


		function flo_forms_insert_gist_dialog() {

			$args = array(
					'posts_per_page'   => -1,
					'orderby'          => 'post_date',
					'order'            => 'DESC',
					'post_type'        => 'flo_forms',
					'post_status'      => 'publish',
					'suppress_filters' => true
				);

			$posts = get_posts($args);

			if(isset($posts) && !empty($posts)){
				echo  "<select name='mosaic' style='width: 100%;background: #ccc' id='flo-forms-select'>";
				foreach($posts as $post){
					echo "<option value='".$post->ID."'>".$post->post_title."</option>";
				}
				echo "</select>";
			}else{
				echo "<div style='white-space: normal'>";
				echo sprintf(__('There are no forms created. You can create one %s here %s ', 'flo-forms'), '<a href="edit.php?post_type=flo_forms" style="color: blue" target="blank">', '.</a>');
				echo "</div>";
			}

			exit();
		//return $select;
		}

    }
}
?>
