<?php

/*
*  ACF Text Field Class
*
*  All the logic for this field type
*
*  @class 		acf_flo_stylekit_shop
*  @extends		acf_field
*  @package		ACF
*  @subpackage	Fields
*/

if( ! class_exists('acf_flo_stylekit_shop') ) :

class acf_flo_stylekit_shop extends acf_field {


	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

	function __construct() {

		// vars
		$this->name = 'flo_stylekit_shop';
		$this->label = __("Flo Stylekit Shop",'acf');
    $this->category = "layout";


		// do not delete!
    	parent::__construct();
	}


	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function render_field( $field ) {
		$b = "acf-flo-stylekit-shop";

		$promo_slides = Array(
			Array(
				"img" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/evora/promo-banner.jpg",
				"url" => "#"
			),
			Array(
				"img" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/evora/promo-banner.jpg",
				"url" => "#"
			),
			Array(
				"img" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/evora/promo-banner.jpg",
				"url" => "#"
			),
		);

		$stylekits = Array(
			Array(
				"name" => "Evora",
				"price" => "$49",
				"price_discounted" => true,
				"description" => "Stylish, captivating style kit for lorem, bloggers and influencers who love to set trends.",
				"top_label" => false,
				"purchase_link" => "#",
				"thumb" => "http://flothemes-dashboard-images.s3.amazonaws.com/evora/Evora-style-1.thumb.jpg",
				"preview" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/evora/template-home-type-a.preview.jpg"
			),
			Array(
				"name" => "Airy",
				"price" => "$69",
				"price_discounted" => false,
				"description" => "Stylish, captivating style kit for lorem, bloggers and influencers who love to set trends.",
				"top_label" => "New",
				"purchase_link" => "#",
				"thumb" => "http://flothemes-dashboard-images.s3.amazonaws.com/evora/Evora-style-2.thumb.jpg",
				"preview" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/evora/template-home-type-a.preview.jpg"
			),
			Array(
				"name" => "Evora",
				"price" => "$49",
				"price_discounted" => false,
				"description" => "Stylish, captivating style kit for lorem, bloggers and influencers who love to set trends.",
				"top_label" => false,
				"purchase_link" => "#",
				"thumb" => "http://flothemes-dashboard-images.s3.amazonaws.com/evora/Evora-style-1.thumb.jpg",
				"preview" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/evora/template-home-type-a.preview.jpg"
			),
			Array(
				"name" => "Airy",
				"price" => "$69",
				"price_discounted" => false,
				"description" => "Stylish, captivating style kit for lorem, bloggers and influencers who love to set trends.",
				"top_label" => false,
				"purchase_url" => "#",
				"thumb" => "http://flothemes-dashboard-images.s3.amazonaws.com/evora/Evora-style-2.thumb.jpg",
				"preview" => "https://s3-us-west-2.amazonaws.com/flothemes-dashboard-images/evora/template-home-type-a.preview.jpg"
			),
		);
		?>
		<div class="<?php echo $b ?>">
			<div class="<?php echo $b ?>__promo-slideshow-wrap">
				<?php if ($promo_slides): ?>
					<div class="<?php echo $b ?>__promo-slideshow">
						<?php foreach ($promo_slides as $promo_slide): ?>
							<div class="<?php echo $b ?>__promo-slide">
								<div class="<?php echo $b ?>__promo-slide-content">
									<img class="<?php echo $b ?>__promo-slide-image" src="<?php echo $promo_slide["img"] ?>" alt="">
									<?php if ($promo_slide["url"]): ?>
										<a class="<?php echo $b ?>__promo-slide-anchor" href="<?php echo $promo_slide["url"] ?>"></a>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="<?php echo $b ?>__promo-slideshow-arrow <?php echo $b ?>__promo-slideshow-arrow--prev">
						<i class="flo-admin-icon-angle-left"></i>
					</div>
					<div class="<?php echo $b ?>__promo-slideshow-arrow <?php echo $b ?>__promo-slideshow-arrow--next">
						<i class="flo-admin-icon-angle-right"></i>
					</div>
				<?php endif; ?>
			</div>

			<div class="<?php echo $b ?>__title">
				Premium Style Kits
			</div>

			<div class="<?php echo $b ?>__subtitle">
				Lorem ipsum dolor sit amet, consectetur adipisicing elit
			</div>

			<div class="<?php echo $b ?>__stylekits-and-preview">

				<div class="<?php echo $b ?>__stylekits">
					<?php foreach ($stylekits as $stylekit): ?>
						<?php
							$stylekit_discounted_class = $stylekit["price_discounted"] ? $b . "__stylekit--discounted" : "";
						?>
						<div
							class="<?php echo $b ?>__stylekit <?php echo $stylekit_discounted_class ?>"
							data-preview="<?php echo $stylekit["preview"] ?>"
							data-price="<?php echo $stylekit["price"] ?>"
							data-description="<?php echo $stylekit["description"] ?>"
							data-purchase-url="<?php echo $stylekit["purchase_url"] ?>"
							data-name="<?php echo $stylekit["name"] ?>"
						>
							<div class="<?php echo $b ?>__stylekit-thumb-wrap">
								<div class="<?php echo $b ?>__stylekit-thumb" style="background-image: url(<?php echo $stylekit["thumb"] ?>)"></div>
								<?php if ($stylekit["top_label"]): ?>
									<div class="<?php echo $b ?>__stylekit-top-label">
										<?php echo $stylekit["top_label"] ?>
									</div>
								<?php endif; ?>
							</div>
							<div class="<?php echo $b ?>__stylekit-name">
								<?php echo $stylekit["name"] ?>

								<div class="<?php echo $b ?>__stylekit-price">
									<?php echo $stylekit["price"] ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="<?php echo $b ?>__right-wrap">
					<div class="<?php echo $b ?>__right-wrap-title">
						Elegant
					</div>
					<div class="<?php echo $b ?>__right-wrap-description">
						Stylish, captivating style kit for lorem, bloggers and influencers who love to set trends.
					</div>
					<div class="<?php echo $b ?>__right-wrap-purchase-area">
						<div class="<?php echo $b ?>__right-wrap-price">
							$69
						</div>
						<a class="<?php echo $b ?>__purchase-button flo-admin-button" href="#">
							Purchase
						</a>
						<a class="<?php echo $b ?>__test-drive-button" href="">
							<i class="flo-admin-icon-eye"></i>
							Test Drive*
						</a>
					</div>
					<div class="<?php echo $b ?>__preview-wrap">
						<div class="<?php echo $b ?>__preview-loading">Loading Preview</div>
						<div class="<?php echo $b ?>__preview">
							<div class="<?php echo $b ?>__preview-navigation-box"></div>
						</div>
					</div>
					<div class="<?php echo $b ?>__right-wrap-note">
						*Note: The Test Drive allows you to apply the style kit for 24 hours to test it out with your website
					</div>
				</div>
			</div>

		</div>
		<?php
	}


	/*
	*  render_field_settings()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @param	$field	- an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function render_field_settings( $field ) {

	}

  function input_admin_enqueue_scripts() {

    $dir = get_template_directory_uri().'/acf/acf-extensions/acf-flo-stylekit-shop/';

    wp_register_script( 'acf-flo-stylekit-shop', "{$dir}acf-flo-stylekit-shop.js" );
    wp_enqueue_script( 'acf-flo-stylekit-shop' );

  }

}


// initialize
if(function_exists('acf_register_field_type')){
  acf_register_field_type( new acf_flo_stylekit_shop() );
}

endif; // class_exists check

?>
