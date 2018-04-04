<?php 
	/**
	 *
	 * Block comment
	 *
	 */
	function flo_generate_wpml_config() {
	  $result = array();
	  $options_acf_fields = get_field_objects('options'); // acf fields used on option page

	  $flo_option_names = array();

	  $op_start = '<key name="';
	  $op_end = '" />';
	  

	  foreach ($options_acf_fields as $key => $field_data) {
	    //var_dump($field_data['type']); 
	    
	    
	    if(isset($field_data['type'])) {
	      if($field_data['type'] == 'text' || $field_data['type'] == 'textarea' || 'wysiwyg' == $field_data['type'] || $field_data['type'] == 'link') {
	        $flo_option_names[] = $op_start.'options_'.$field_data['name'].$op_end;
	      }


	      if($field_data['type'] == 'clone') {
	        $clone_field_names = flo_get_clone_subfields($field_data);
	        $flo_option_names = array_merge($flo_option_names, $clone_field_names);
	      }

	      if($field_data['type'] == 'repeater') {
	        $repeater_field_names = flo_get_repeater_subfields($field_data);
	        $flo_option_names = array_merge($flo_option_names, $repeater_field_names);
	      }

	      

	    }
	    
	  }

	  global $wp_filesystem;
		if (empty($wp_filesystem)) {
		    require_once (ABSPATH . '/wp-admin/includes/file.php');
		    WP_Filesystem();
		}

		$file = get_stylesheet_directory().'/wpml-config.xml';
		$dev_file = get_stylesheet_directory().'/theme-files/wpml-config.xml';
	  
	  $wpml_config_string = 
"<wpml-config>
  <custom-types>
   <custom-type translate='1'>gallery</custom-type>
  </custom-types>
  <admin-texts>
	  ".implode(PHP_EOL.'		',$flo_option_names)."
  </admin-texts>
</wpml-config>";

	  $wp_filesystem->put_contents(  $file, $wpml_config_string, FS_CHMOD_FILE);  
	  $wp_filesystem->put_contents(  $dev_file, $wpml_config_string, FS_CHMOD_FILE);  
	  
	  //return $wpml_config_string;

	  $result['msg']  = 'All done! The file was succesfully replaced. You can now start translating theme options in "String translation". File path: '.$file;

	  if(isset($_POST['action'])) {
	  	echo json_encode($result);

	  	exit();	
	  }
	  
	}


	/**
	 *
	 * iterate through the complex flexible content blocks used for the Posts or Gallery layouts
	 * @param 
	 *
	 */
	function flo_get_clone_flexible_content_subfields($field_data){
	  $option_names = array();
	  $op_start = '<key name="';
	  $op_end = '" />';

	  // store all the blocks anems in the post or gallery layout
	  $post_or_gal_blocks = array();
	  foreach ($field_data['value'] as $k => $all_v) {
	    foreach ($all_v as $qp => $v) {
	      //var_dump($v);
	      $post_or_gal_blocks[] = $v['acf_fc_layout']; // $v['acf_fc_layout'] represents the block name
	    }
	  }



	  $counter = 0;

	  // $field_data['sub_fields'][0]['layouts'] will store all the blocks available for 
	  // Posts or Galleries. Therefore we have to check if the current block is in the 
	  // earlier crated list: $post_or_gal_blocks
	  foreach ($field_data['sub_fields'][0]['layouts'] as $x => $block) {

	    if(in_array($block['name'], $post_or_gal_blocks)){
	      
	      foreach ($block['sub_fields'] as $block_key => $block_value) {
	        
	        // find the index of the block in the Layout options
	        // the index is important 
	        // i.e. 'options_flo-ev-p_layout_2_prev_item_label' -> here the index is 2
	        // and that means this label belong to the 3rd (we count from 0) block in the layout
	        $block_index = array_search ( $block['name'] , $post_or_gal_blocks );


	        if( ($block_value['type'] == 'text' || $block_value['type'] == 'textarea' || 'wysiwyg' == $block_value['type'] ) && 'flo-block__id' != $block_value['name'] && 'flo-block__classes' != $block_value['name'] ) {

	          
	          if($block_index !== false) {
	            $option_names[] = $op_start.'options_'.$field_data['name'].'_layout'.'_'.$block_index.'_' .$block_value['name'].$op_end;  
	          }
	          


	        }  

	        if($block_value['type'] == 'repeater') {
	          $block_prefix = $field_data['name'].'_layout'.'_'.$block_index;
	          $block_data = array(
	            'block_name' => $block['name'],
	            'block_prefix' => $block_prefix,
	            'value' => $field_data['value']
	          );

	          $repeater_field_names = flo_get_repeater_subfields($block_value, $block_data);
	          $option_names = array_merge($option_names, $repeater_field_names);

	        }
	      }      

	      

	      $counter ++;
	    }

	  }

	  return $option_names;
	  
	}

	function flo_get_clone_subfields($clone_field) {
	  
	  $option_names = array();
	  $op_start = '<key name="';
	  $op_end = '" />';

	  // for the Blog Posts or Gallery Posts layout options we have a more complex 
	  // structure, and therefore needs special tratment:
	  $layout_options_names = array('flo-ev-g','flo-ev-p');

	  // make sure to use the 'flo_layout_options_name' filter for all the themes that have support for wpml config file generation !!!
	  $layout_options_names = apply_filters( 'flo_layout_options_name', $layout_options_names );


	  // another specific case are the Search and Archive layout options


	  if(in_array($clone_field['name'], $layout_options_names)) {
	    $option_names = flo_get_clone_flexible_content_subfields($clone_field);
	  }else if(strpos($clone_field['name'], 'search-options') != false || strpos($clone_field['name'], 'archive-options') != false) {
	  	foreach ($clone_field['sub_fields'] as $key => $field_data) {
	      
	      if( ($field_data['type'] == 'text' || $field_data['type'] == 'textarea' || 'wysiwyg' == $field_data['type']) && 'flo-block__id' != $field_data['name'] && 'flo-block__classes' != $field_data['name'] ) {

	        $option_names[] = $op_start.'options_'.$field_data['name'].$op_end;
	      }
	  
	      
	    }
	  }else if(isset($clone_field['sub_fields'])) {
	    foreach ($clone_field['sub_fields'] as $key => $field_data) {
	      
	      if( ($field_data['type'] == 'text' || $field_data['type'] == 'textarea' || 'wysiwyg' == $field_data['type']) && 'flo-block__id' != $field_data['name'] && 'flo-block__classes' != $field_data['name'] ) {
	        $option_names[] = $op_start.'options_'.$clone_field['name'].'_'.$key.'_' .$field_data['name'].$op_end;
	      }
	  
	      
	    }
	  }

	  return $option_names;
	}


	/**
	 *
	 * Block comment
	 *  $block_data has the following format:
	          $block_data = array(
	            'block_name' => $block['name'],
	            'block_prefix' => $block_prefix,
	            'value' => $field_data['value']
	          );
	 *
	 */
	function flo_get_repeater_subfields($repeater_field, $block_data = array() ) {
	  
	  $option_names = array();
	  $op_start = '<key name="';
	  $op_end = '" />';

	  if(isset($repeater_field['sub_fields'])) {

	    if($repeater_field['value'] != null) {
	      for($i = 0; $i < sizeof($repeater_field['value']); $i++) {
	        foreach ($repeater_field['sub_fields'] as $key => $field_data) {


	          if( ($field_data['type'] == 'link' || $field_data['type'] == 'text' || $field_data['type'] == 'textarea' || 'wysiwyg' == $field_data['type']) && 'flo-block__id' != $field_data['name'] && 'flo-block__classes' != $field_data['name'] ) {
	            $option_names[] = $op_start.'options_'.$repeater_field['name'].'_'.$i.'_' .$field_data['name'].$op_end;

	          }
	        }  
	      }  
	    }
	    
	    
	    // this happens when the repeater is callded from the layout block clone
	    // i.e. a block that belongs to the Posts global potions
	    $block_prefix = '';
	    if(isset($block_data['block_prefix'])) {
	      $block_prefix = $block_data['block_prefix'];
	    }
	    if($repeater_field['value'] == null && sizeof($block_data) && $block_prefix != '' ) {
	      
	      

	      if(isset($block_data['value']['layout'])) {

	        foreach ($block_data['value']['layout'] as $l_key => $l_value) {
	          if($l_value['acf_fc_layout'] == $block_data['block_name']) {
	            
	            //var_dump($l_value);
	            if(isset($l_value[$repeater_field['name']] ) && is_array($l_value[$repeater_field['name']]) ) {
	              $loop_size = sizeof($l_value[$repeater_field['name']]);
	            
	            }
	          }
	        }
	      }

	      if(isset($loop_size)){
	        
	        for($t = 0; $t < $loop_size; $t++) {
	          foreach ($repeater_field['sub_fields'] as $key => $field_data) {

	            if( ($field_data['type'] == 'link' || $field_data['type'] == 'text' || $field_data['type'] == 'textarea' || 'wysiwyg' == $field_data['type']) && 'flo-block__id' != $field_data['name'] && 'flo-block__classes' != $field_data['name'] ) {

	              $option_names[] = $op_start.'options_'.$block_prefix.'_'.$repeater_field['name'].'_'.$t.'_' .$field_data['name'].$op_end;

	            }
	          } 
	        }

	      }
	      
	      
	    } 

	  }

	  return $option_names;
	  
	}


	add_action('wp_ajax_wpml_conf_generation' , 'flo_generate_wpml_config' );


	/**
	 *
	 * Enable/Disable options cache. This is used for the maintainance mode when translating a theme using WPML with string translation.
	 *
	 */
	add_action('wp_ajax_flo_wmpl_alter_cache_option' , 'flo_wmpl_alter_cache_option' );
	function flo_wmpl_alter_cache_option() {

		if($_POST['cache_action'] == 'disable_cache') {
			update_option( 'flo_wmpl_disable_cache', 1, $autoload = false );
      echo '<div class="error warning">The cache was succesfully disabled</div>';
		}else{
			delete_option( 'flo_wmpl_disable_cache' );
			echo '<span class="success">The cache was succesfully enabled</span>';
		}
		
		// delete flo_options transient
		$theme_name = flo_theme_data_variable('Name');
		$options_transient_name = $theme_name . '_flo_options';
		delete_multilingual_transients($options_transient_name);

		// delete the global layout transients
		$cached_layout = array('flo-p2-p_layout','flo-p2-g_layout');
		$cached_layout = apply_filters( 'flo_cached_layout_options',$cached_layout);
		foreach ($cached_layout as $layout_option_name) {
			if(function_exists('icl_object_id')) {
				delete_multilingual_transients($layout_option_name.'_cached');
			}
		}


		exit();
	}

?>