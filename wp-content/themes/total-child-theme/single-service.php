<?php
/**
 * Products Post Type Singular Simple Template Framework.
 */
get_header();
global $post;
$mainTitle = $post->post_title;
$tagline = get_field("tagline", $post->ID);

$quoteConnectButtonURL = get_field("connect_button_url", $post->ID);
$quoteImage = get_field("featured_team_member_image", $post->ID);
$employeeID = get_field("featured_team_member", $post->ID);
$employeeObject = $employeeID ? get_post($employeeID[0]) : NULL;
if ($employeeObject) {
    $quoteName = $employeeObject->post_title;	
	$jobtitles = "";
	if(!is_wp_error(wp_get_post_terms($employeeObject->ID, 'jobtitles'))){

    $jobtitles = wp_get_post_terms($employeeObject->ID, 'jobtitles')[0]->name;
	}
}
$quoteText = get_field("featured_quote", $post->ID);
$quoteHeading = get_field("subheading", $post->ID);
$quoteDescription = get_field("description", $post->ID);
$productIds = get_field("complementary_products", $post->ID);
$products = null;
if ($productIds) {
    $products = getObjectsByCPT('product', -1, array(), $productIds);
}
$services = get_field('customized_solutions_list', $post->ID);
$customizedSolutionsIntro = get_field('customized_solutions_intro', $post->ID);

/*
$featuredInsightsIds = get_field('featured_insights', $post->ID);

$featuredContent = null;
if (isset($featuredInsightsIds)) {
	$args   = array(
            'post_type' => 'post',
            'order' => 'DESC',
            'orderby' => 'date',			
            'post_status' => 'publish',
		    'posts_per_page'=>3,
			'post__in'=>$featuredInsightsIds            
        );		
		

        $featuredContent = new WP_Query($args);
   
}else{
		
		$args   = array(
            'post_type' => 'post',
            'order' => 'DESC',
            'orderby' => 'date',			
            'post_status' => 'publish',
			'posts_per_page'=>3            
        );

		

        $featuredContent = new WP_Query($args);
}*/
global $post;
		$postsarray = array();		
		$posts = NULL;		
		$args   = array(
            'post_type' => 'post',
            'order' => 'DESC',
            'orderby' => 'date',			
            'post_status' => 'publish',
			'posts_per_page' => '3',  
			'meta_query'=>array(array('key'=>'related_services','value'=>sprintf(':"%s";', $post->ID),'compare'=>'LIKE'))           
        );
		
 wp_reset_query();
        $featuredContent = new WP_Query($args); 
?>

<div id="content-wrap" class="container clr">
    <?php wpex_hook_primary_before(); ?>
    <section id="primary" class="content-area clr" style="padding-bottom:0px;">
        <?php wpex_hook_content_before(); ?>
        <div id="content" class="site-content clr" role="main">
            <div id="header-wrapper" class="header-wrapper vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">
                <!--<div class="vc_column_container vc_col-sm-8">
                    <h1 class="single-header"><?php //echo $mainTitle; ?></h1>
                    <h3 class="single-tagline"><?php //echo $tagline; ?></h3>
                </div>-->
              
                  <div class="vc_column_container vc_col-sm-10">
                  <span class="single-tagline"><?php //echo $tagline; ?>Expertise</span>
                  <h1 class="jump-header"><?php echo $mainTitle;?></h1>
                  </div>
            

            </div>
            <?php wpex_hook_content_top(); ?>
            <?php
            // YOUR POST LOOP STARTS HERE
            while (have_posts()) : the_post();
                ?>
                <?php
                // Display the featured image
                // You could also instead use the function wpex_get_post_media which returns the post
                // thumbnail, gallery slider, video or audio
                if (has_post_thumbnail() && wpex_get_mod('page_featured_image')) :
                    ?>
                    <div id="page-featured-img" class="clr">
                        <?php the_post_thumbnail(); ?>
                    </div>
                    <!-- #page-featured-img -->

                <?php endif; ?>
                <?php
                // Display the post content
                // Note the "entry" class this is used for styling purposes so it's important to use it on any content element 
                ?>
                <div class="entry-content entry clr">
                    <?php the_content(); ?>
                </div>
                <!-- .entry-content -->

                <?php
            // YOUR POST LOOP ENDS HERE
            endwhile;
            ?>
            <?php wpex_hook_content_bottom(); ?>
            <?php if ($employeeObject) { ?>
                <div id="quote-wrapper" class="quote-wrapper">
                    <div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex vc_row-o-content-middle" style="margin-left:0px;margin-right:0px;">
                        <div class="vc_column_container vc_col-sm-6">
                            <div class="vc_column-inner padding0">
                                <div class="imageWrapper"> <img src="<?php echo $quoteImage; ?>" alt="<?php echo $quoteName; ?>"/> </div>
                            </div>
                        </div>
                        <div class="vc_column_container vc_col-sm-6">
                            <div class="vc_column-inner padding0">
                                <div class="button-section quote"><a class="section-button" href="<?php echo $quoteConnectButtonURL; ?>">Connect</a> </div>
                                <div class="quoteTextWrapper">
                                    <h2 class="gradientEffect">&ldquo;<?php echo $quoteText; ?>&rdquo;</h2>
                                    <div class="horizontalFixLine"></div>
                                    <div class="name"><strong><?php echo $quoteName; ?></strong></div>
                                    <div class="designation"><?php echo $jobtitles; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">
                        <div class="vc_column_container vc_col-sm-12">
                            <h4 class="title"><?php echo $quoteHeading; ?></h4>
                            <div class="description"><?php echo $quoteDescription; ?></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if ($featuredContent->have_posts()) { 
			$wpex_columns = apply_filters( 'wpex_related_blog_posts_columns', wpex_get_mod( 'blog_related_columns', '3' ) );
			?>
  <div id="featured-insights" class="featured-insights related-posts">
    <div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" style="margin-left:0px;margin-right:0px;">
      <div class="vc_column_container vc_col-sm-4">
                            <h2 class="section-title">Master the<br />
                                Evolving Market</h2>
                        </div>
                        <div class="vc_column_container vc_col-sm-4">
                            <div class="section-description">We anticipate and identify today's challenges, opportunities, and top trends to watch.</div>
                        </div>
    </div>
    <div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">
      <div style="height:30px;"></div>
    </div>
    <!--<div class="vc_row wpb_row vc_row-fluid">-->
      <?php 
	                  $wpex_count = 0; 
					while ( $featuredContent->have_posts() ) : $featuredContent->the_post();

				 $wpex_count++; 
				 include( locate_template( 'partials/blog/blog-single-related-entry.php' ) );

				 if ( $wpex_columns == $wpex_count ) $wpex_count=0;
					 endwhile;
					?>
    <!--</div>-->
    <div class="bottom-shadow"></div>
  </div>
  <?php } ?>
            <?php if ($services) { ?>
                <div id="services" class="services-wrapper">
                    <div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" style="margin-left:0px;margin-right:0px;">
                        <div class="vc_column_container vc_col-sm-4">
                            <h2 class="section-title">Customized Solutions</h2>
                        </div>
                        <div class="vc_column_container vc_col-sm-4">
                            <?php if ($customizedSolutionsIntro) { ?>
                                <div class="section-description"><?php echo $customizedSolutionsIntro; ?></div>
                            <?php } ?>
                        </div>
                        <div class="vc_column_container vc_col-sm-4 textright">
                            <div class="buttonWrapper"> <a class="section-button" href="<?php echo $quoteConnectButtonURL; ?>">Connect</a> </div>
                        </div>
                    </div>
                    <?php
                    $output = '';
                    $totalServices = count($services);
                    $firstPart = ceil($totalServices / 2);
                    $secondPart = $totalServices - $firstPart;
                    $firstPartContent = $secondPartContent = '';

                    foreach ($services as $key => $service) {
                        $position = $key + 1;
                        $serviceTitle = $service['item'];

                        if ($position <= $firstPart) {
                            $firstlastclass = '';
                            if ($position == $firstPart) {
                                $firstlastclass = 'last';
                            }

                            $firstPartContent .= '<div class="service-item vc_column_container vc_col-sm-12 ' . $firstlastclass . '">';
                            $firstPartContent .= $serviceTitle;
                            $firstPartContent .= '</div>';
                        } else {

                            $secondlastclass = '';
                            if (($position / 2) % $firstPart == 0 && $position == ($secondPart * 2)) {
                                $secondlastclass = 'last';
                            }
                            $secondPartContent .= '<div class="service-item vc_column_container vc_col-sm-12 ' . $secondlastclass . '">';
                            $secondPartContent .= $serviceTitle;
                            $secondPartContent .= '</div>';
                        }
                    }
                    $output .= '<div class="service-box">';
                    $output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" style="margin-left:0px;margin-right:0px;">';
                    $output .= '<div class="vc_column_container vc_col-sm-6">';
                    $output .= '<div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">';
                    $output .= $firstPartContent;
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '<div class="vc_column_container vc_col-sm-6">';
                    $output .= '<div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">';
                    $output .= $secondPartContent;
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    $output .= '</div>';
                    echo $output;
                    ?>

                    
                </div>
            <?php } ?>
            <?php if ($products) { ?>
                <div id="products-wrapper" class="products-wrapper">
                    <div class="product-category-box odd">
                        <div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;margin-bottom:30px;">
                            <div class="wpb_column vc_column_container vc_col-sm-4">
                                <h2 class="section-title">Complementary Products</h2>
                            </div>
                        </div>
                        <?php
                        $productsParts = array_chunk($products, 4);
                        foreach ($productsParts as $rowkey => $productsPart) {
                            ?>
                            <div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex">
                                <?php
                                foreach ($productsPart as $key => $product) {
                                    $rolloverImage = get_field('product_rollover_image', $product->ID);
                                    $productImage = get_field('product_image', $product->ID);
                                    //$productCategorySlug = wp_get_post_terms($product->ID, 'product-category')[0]->slug;
                                    //$fieldNameCategoryWise = str_replace('-', '_', $productCategorySlug);
                                   // $productIntro = get_field($fieldNameCategoryWise . '_intro', $product->ID);
								    $productIntro = get_field('intro', $product->ID);
                                    $index = $key + 1;
                                    $productTitle = $product->post_title;
                                    $placeholder = get_stylesheet_directory_uri() . '/images/team-placeholder.jpg';
									if($productImage){
										$productImageURL = $productImage['url'];										
									}else{
										$productImageURL = get_stylesheet_directory_uri() . '/images/team-placeholder.jpg';
									}
                                    $productURL = get_permalink($product->ID);
									
                                    ?>
                                    <div class="product-wrapper wpb_column vc_column_container vc_col-sm-4" >
                                        <div class="product-box"> <a href="<?php echo $productURL; ?>">
                                                <div class="imageWrapper"> <img src="<?php echo $placeholder; ?>" data-original="<?php echo $productImageURL; ?>" alt="<?php echo $productTitle; ?>"/> <i class="arrow-up"></i> </div>
                                                <div class="productSummary">
                                                    <h4><?php echo $productTitle; ?></h4>
                                                    <div class="intro"><?php echo $productIntro; ?></div>
                                                </div>
                                            </a> </div>

                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <script type='text/javascript'>


                    (function ($) {
                        $(window).bind('load', function () {
                            var images = $('.product-wrapper .imageWrapper img');
                            images.each(function (index, element) {

                                var original = $(this).attr('data-original');
                                $(this).attr('src', original);
                            });

                        });
                    })(jQuery);
                </script>
            <?php } ?>
        </div>
        <!-- #content -->

        <?php wpex_hook_content_after(); ?>
    </section>
    <!-- #primary -->

    <?php wpex_hook_primary_after(); ?>
</div>
<!-- #content-wrap -->

<?php get_footer(); ?>
