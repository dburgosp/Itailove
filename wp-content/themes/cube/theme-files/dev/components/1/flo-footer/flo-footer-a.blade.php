@include('core.style', [
  "breakpoint__medium_up" => "
    .flo-footer__menu li ul {
      background-color: ". flo_get_option("flo-cube-footer_menu-dropdown-background-color", "#ffffff") .";
    }
    .flo-footer__menu li ul a {
      color: ". flo_get_option("flo-cube-footer_menu-dropdown-elements-color", "#000000") .";
    }
  "
])

<footer class="flo-footer">
    @if($full_content_1)
        <div class="flo-footer--sidebars flo-footer-sidebars--full-width   flo-section--padding-small full-scrollable-footer">
            <article class="flo-footer-sidebars--full-width-content">
                <div class="row">
              <div class="column small-12 medium-12">
                {{ $full_content_1 }}
              </div>
            </div>
            </article>
        </div>
    @endif
    @if($f1 || $f2 || $f3)
    <div class="flo-footer--sidebars flo-footer-sidebars--three-columns   flo-section--padding-small full-scrollable-footer">
        <article class="flo-footer--sidebars-content">
            <div class="row row-flex">
                <div class="column small-12 medium-4 to-appear">
                    {{$f1}}
                </div>
                <div class="column small-12 medium-4 to-appear">
                    {{$f2}}
                </div>
                <div class="column small-12 medium-4 to-appear">
                    {{$f3}}
                </div>
            </div>
        </article>
    </div>
    @endif
    @if($full_content_2)
        <div class="flo-footer--sidebars flo-footer-sidebars--full-width   flo-section--padding-small full-scrollable-footer">
            <article class="flo-footer-sidebars--full-width-content">
                <div class="row">
              <div class="column small-12 medium-12">
                {{ $full_content_2 }}
              </div>
            </div>
            </article>
        </div>
    @endif
    @if( flo_get_option('flo-cube-footer__show-instagram-feed', false) && function_exists('flo_instagram_shortcode') )
        <?php echo do_shortcode('[flo_instagram padding="0" use_pattern="" crop="0" nr_columns="6" limit="6" picture_sizes="320x320_crop" link="1" ]'); ?>
    @endif

    <div class="flo-footer__content">
        <div class="flo-footer__top">

            <div class="flo-footer__first-half">
              @if( flo_get_option('flo-cube-footer__show-logo',false)  )
                  <a href="{{ get_home_url() }}" class="flo-footer__logo">
                    <?php
                      $logo_type = flo_get_option("flo-cube-footer__logo-type", "site-title");
                    ?>
                    @if($logo_type == "image")
                    	<?php

                    		$main_logo_url = '';
                    		if(isset($flo_options['flo-cube-footer__logo-image']) && strlen(trim($flo_options['flo-cube-footer__logo-image']))){
                    			$main_logo_url = $flo_options['flo-cube-footer__logo-image'];
                    		}

                  	?>
                      <img class="flo-footer__logo-image" src="{{ $main_logo_url }}" alt="" />
                    @elseif($logo_type == "text")
                      {{ flo_get_option("flo-cube-footer__logo-text", "") }}
                    @elseif($logo_type == "site-title" || !$logo_type)
                      {{ get_bloginfo( 'name' ); }}
                    @endif
                  </a>

              @endif

              @if(flo_get_option('flo-cube-footer__enable-menu'))
                  <?php
                    $menu = "footer-menu";
                    if (!flo_get_option("flo-cube-footer__use-different-menu")) {
                      $menu = "primary";
                    }
                  ?>
                  @if(has_nav_menu( $menu ))
                  <nav class="flo-footer__menu ">
                      {{
                        wp_nav_menu(
                          array(
                          'theme_location' => $menu
                          )
                        );
                      }}
                  </nav>
                  @endif
                @endif
            </div>

            @if(flo_get_option('flo-cube-footer__contact-block--show', false))
                <div class="flo-footer__contact-block">
                    <div class="flo-footer__contact-block-message">{{ flo_get_option('flo-cube-footer__contact-block--message', '') }}</div>
                    <span class="flo-footer__contact-block-email">{{ flo_get_option('flo-cube-footer__contact-block--email', '') }}</span>
                    <span class="flo-footer__contact-block-separator">|</span>
                    <span class="flo-footer__contact-block-phone">{{ flo_get_option('flo-cube-footer__contact-block--phone', '') }}</span>

                    @if(flo_get_option('flo-cube-footer__contact-block--show-subscription-form', false))
                        <form class="flo-footer__newsletter-form" method="post">
                            <input class="flo-footer__newsletter-form-email" type="email" name="EMAIL" placeholder="{{ __('email address ...','flotheme') }}">
                            <input type="submit" value="{{  __('Subscribe','flotheme') }}" class="flo-footer__newsletter-form-submit">
                        </form>

                        @if(strlen(flo_get_option("flo-cube-footer__mailchimp-form-code","") ) )
                            <noscript type="text/template" class="embed_code">
                              {{ addslashes($flo_options["flo-cube-footer__mailchimp-form-code"]) }}
                            </noscript>
                        @endif
                    @endif
                </div>
            @endif

        </div>


        <div class="flo-footer__addons">
            @if(strlen(flo_get_option('flo-cube-footer__copyright-text', '') ))
                <div class="flo-footer__addons-copyright">
                    {{ str_replace('%year%', date('Y'), flo_get_option('flo-cube-footer__copyright-text', '')) }}
                </div>
            @endif

                <div class="flo-footer__addons-social">
                @if(flo_get_option('flo-cube-footer___addons--show', false))
                    @if(flo_get_option('flo-cube-footer__addons--show-social-links', false) && $flo_social_links = flo_get_option('flo-social-links', false))
                        @include('components.social-links')
                    @endif
                @endif

                    {{ do_action("flo_footer_credits"); }}
                </div>
        </div>

    </div>

    {{--<button class="flo-footer__subscribe" data-open="newsletter-popup">Newsletter Subscribe</button>--}}
    {{--<span class="flo-footer__scroll-up">--}}
    {{--back to top<span class="flo-icon-flo-arrow-up "></span>--}}
    {{--</span>--}}
</footer>
