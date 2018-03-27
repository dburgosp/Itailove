<?php
    $special_block__text =  get_field('flo-special_block__text');
?>
<div class="flo-special-block flo-special-block--type-{{$special_block__type}}">
    @if($special_block__type == "related_posts")
        <div class="flo-special-block__related-posts">
            @if(sizeof($special_block__related_posts) && $special_block__related_posts->have_posts())
                <?php
                    $related_posts_enabled = true;
                ?>
                <div class="flo-special-block__related-block-title">
                    {{  flo_get_option('flo-cube-blog-post__related-posts-block-title',__('RELATED POSTS:','flotheme') ) }}
                </div>
                @while($special_block__related_posts->have_posts())
                    <?php $special_block__related_posts->the_post(); ?>
                    
                    <div class="flo-special-block__related-posts-item">
                        <a href="{{ the_permalink() }}">{{ the_title() }}</a>
                    </div>
                    <?php wp_reset_postdata(); ?>
                @endwhile
            @endif
        </div>
    @elseif($special_block__type == "additional_information")
        <div class="flo-special-block__additional-information">
            @if(isset($special_block__additional_information) &&  is_array($special_block__additional_information) &&  sizeof($special_block__additional_information))
                <ul>
                    @foreach($special_block__additional_information as $item)
                        <li class="flo-special-block__additional-information-item to-appear to-appear--uniform">
                            <div class="flo-special-block__additional-information-item-key">{{ $item['key'] }}</div>
                            <div class="flo-special-block__additional-information-item-value">{{ $item['value'] }}</div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    <div class="flo-special-block__text to-appear @if($special_block__type == 'related_posts') flo-post--gap-left @endif">
            <div class="flo-special-block__text-content">
        @if(strlen($special_block__text))
                {{ $special_block__text }}
        @endif
            </div>
    </div>
</div>
