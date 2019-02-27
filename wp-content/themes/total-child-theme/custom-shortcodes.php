<?php // code added by agastya mevada 19-07-2019---------------------
add_filter('wpex_has_overlay_header', function( $return ) {



// Enable if your new Overlay Header Image is not empty and is a valid image

    if (is_singular('service') || is_singular('product')) {
		//remove_action('wpex_hook_main_top', 'wpex_page_header');
		remove_action( 'wpex_hook_content_bottom', 'wpex_post_edit' );
		remove_action( 'wpex_hook_main_bottom', 'wpex_next_prev' );
       // $return = false;

    }



// Return bool

    return $return;

},100);

if (!function_exists('shortcode_header_title'))
	{
	function shortcode_header_title($atts, $content = null)
		{

		extract(shortcode_atts(array(
		"title"=>"",
		"description"=>"",
		"menu"=>""
		) , $atts));	
		$hasMenu = false;				
		if($menu){			
			$menuItems = array();
			if(strpos($menu,',') !== false){
				$items = explode(",",$menu);
				foreach($items as $item){
					$part = explode("|",$item);
					$menuItems[] = array("name"=>$part[0],'id'=>$part[1]);
				}
			}else{
				$part = explode("|",$menu);
				$menuItems[] = array("name"=>$part[0],'id'=>$part[1]);
			}
			$hasMenu = true;
		}
		
		$output = '';
		$output .= '<div id="header-wrapper" class="header-wrapper vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" style="margin-left:0px;margin-right:0px;">';
		$output .= '<div class="vc_column_container vc_col-sm-4">';
		$output.='<h1 class="jump-header">'.$title.'</h1>';
		$output .= '</div>';
		$output .= '<div class="vc_column_container vc_col-sm-4">';
		$output.='<div class="header-description">'.$description.'</div>';
		$output .= '</div>';
		if($hasMenu){
		$output .= '<div class="vc_column_container vc_col-sm-4">';
		$output.='<ul class="header-menu">';
		foreach($menuItems as $menuItem){
			$output .= '<li class="local-scroll"><a href="#'.$menuItem['id'].'" data-ls_linkto="#'.$menuItem['id'].'">'.$menuItem['name'].'</a></li>';
		}
		$output.='</ul>';
		$output .= '</div>';
		}
		$output .= '</div>';
		return $output;
		}

	add_shortcode('header_title', 'shortcode_header_title');
}
if (!function_exists('shortcode_products_categories'))
{
	function shortcode_products_categories($atts, $content = null)
	{

	extract(shortcode_atts(array() , $atts));					
	$taxonomy = "product-category";
	$productsCategories = get_terms( array(
    	'taxonomy' => $taxonomy,
	    'hide_empty' => false,
		));
		
	$output = '';
	$output .= '<div id="products-wrapper" class="products-wrapper">';
	if($productsCategories){
		foreach($productsCategories as $key=>$productCategory){

			$bg = $key%2 == 0 ? 'odd' : 'even';
			$productCategoryName = $productCategory->name;
			$productCategoryDescription = $productCategory->description;
			$productCategorySlug = $productCategory->slug;
			$fieldNameCategoryWise = str_replace('-','_',$productCategorySlug);
			$products = getObjectsByCPTWithCategory('product',array(array('taxonomy'=>$taxonomy,'field'=>'slug','terms'=>array($productCategorySlug))));
			$output .= '<div id="'.$productCategorySlug.'" class="product-category-box '.$bg.'">';
				$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" style="margin-left:0px;margin-right:0px;margin-bottom:30px;">';
					$output .= '<div class="wpb_column vc_column_container vc_col-sm-4">';
					$output.='<h2 class="section-title">'.$productCategoryName.'</h2>';
					$output .= '</div>';
					$output .= '<div class="vc_column_container vc_col-sm-4">';
					$output.='<div class="section-description">'.$productCategoryDescription.'</div>';
					$output .= '</div>';
				$output .= '</div>';
				if($products){
					$placeholder = get_stylesheet_directory_uri().'/images/team-placeholder.jpg';
					$productsParts = array_chunk($products,4);					
					foreach($productsParts as $rowkey=>$productsPart){
						$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex">';	
							foreach($productsPart as $key=>$product){
								$rolloverImage = get_field('product_rollover_image',$product->ID);
								$productImage = get_field('product_image',$product->ID);
								$productIntro = get_field($fieldNameCategoryWise.'_intro',$product->ID);
								$index = $key+1;
								$productTitle = $product->post_title;
								$productURL = get_permalink($product->ID);
								$output .= '<div class="product-wrapper wpb_column vc_column_container vc_col-sm-3" >';
								$output .=	'<div class="product-box">';
								$output .= '<a href="'.$productURL.'">';
									$output .= '<div class="imageWrapper">';
										$output .= '<img src="'.$placeholder.'" data-original="'.$productImage['url'].'" alt="'.$productTitle.'"/>';
										$output .= '<i class="arrow-up"></i>';
									$output .= '</div>';
									$output .= '<div class="productSummary">';
									$output .= '<h4>'.$productTitle.'</h4>';
									$output .= '<div class="intro">'.$productIntro.'</div>';
									$output .= '</div>';
								$output .= '</a>';
								$output .= '</div>';
								
								$output .= '</div>';
								
							}
						$output .= '</div>';
					}

				}
			$output .= '</div>';
		}
	}
	$output .= '</div>';
	$output .= "<script type='text/javascript'>
	
	
	(function($) {
    $(window).bind('load', function() {
		var images = $('.product-wrapper .imageWrapper img');
		images.each(function(index, element) {
			
			var original = $(this).attr('data-original');
    		$(this).attr('src',original);
		});
       
    });
	})(jQuery);
	</script>";
	return $output;
	}

	add_shortcode('products_categories', 'shortcode_products_categories');
}
if (!function_exists('getObjectsByCPTWithCategory')) {
    function getObjectsByCPTWithCategory($cpt, $tax_query = array(), $perItems = -1, $meta_query = array(), $include = array(), $exclude = array(), $orderby = 'menu_order', $order = 'DESC', $postStatus = 'publish')
    {
        wp_reset_query();
        $args   = array(
            'post_type' => $cpt,
            'order' => $order,
            'orderby' => $orderby,
            'posts_per_page' => $perItems,
            'post_status' => $postStatus,
            'post__not_in' => $exclude,
            'post__in' => $include,
            'tax_query' => $tax_query,
			'meta_query'=>$meta_query
        );
        $object = new WP_Query($args);
		
        if ($object->have_posts()):
            return $object->get_posts();
        endif;
        wp_reset_query();
        return false;
    }
}
if (!function_exists('getObjectsByCPT')) {
    function getObjectsByCPT($cpt = 'post', $perItems = -1, $meta_query = array(), $include = array(), $exclude = array(), $orderby = 'menu_order', $order = 'DESC', $postStatus = 'publish')
    {
        wp_reset_query();
        $args   = array(
            'post_type' => $cpt,
            'order' => $order,
            'orderby' => $orderby,
            'posts_per_page' => $perItems,
            'post_status' => $postStatus,
            'post__not_in' => $exclude,
            'post__in' => $include,
            'meta_query' => $meta_query
        );
        $object = new WP_Query($args);
        if ($object->have_posts()):
            return $object->get_posts();
        endif;
        wp_reset_query();
        return false;
    }
}

if (!function_exists('shortcode_team_quote'))
	{
	function shortcode_team_quote($atts, $content = null)
		{

		extract(shortcode_atts(array(
		"quote"=>"",
		"name"=>"",
		"designation"=>"",
		"title"=>"",
		"description"=>"",
		"image"=>"",
		"button_text"=>"",
		"button_text"=>"",
		) , $atts));	
		
		$output = '';
		$output .= '<div id="quote-wrapper" class="quote-wrapper">';
			$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex vc_row-o-content-middle" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-6">';
					$output .= '<div class="vc_column-inner padding0">';
						$output .= '<div class="imageWrapper">';
							$output .= '<img src="'.$image.'" alt="'.$name.'"/>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
				$output .= '<div class="vc_column_container vc_col-sm-6">';
					$output .= '<div class="vc_column-inner padding0">';
						
						if($button_text){
							$output .= '<div class="button-section quote">';
								$output .='<a class="section-button" href="'.$button_url.'">'.$button_text.'</a>';
							$output .= '</div>';
						}

						$output .= '<div class="quoteTextWrapper">';
							$output .= '<h2 class="gradientEffect">&ldquo;'.$quote.'&rdquo;</h2>';
							$output .= '<div class="horizontalFixLine"></div>';
							$output .= '<div class="name"><strong>'.$name.'</strong></div>';
							$output .= '<div class="designation">'.$designation.'</div>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';					
			$output .= '</div>';			
			$output .= '<div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-12">';
					$output .= '<h4 class="title">'.$title.'</h4>';
					$output .= '<div class="description">'.$description.'</div>';
				$output .= '</div>';
			$output .= '</div>';
			
			
		$output .= '</div>';
		return $output;
		}

	add_shortcode('team_quote', 'shortcode_team_quote');
}
if (!function_exists('shortcode_insights'))
	{
	function shortcode_insights($atts, $content = null)
		{

		extract(shortcode_atts(array(
		"heading"=>"",
		"image_box_1"=>"",
		"title_box_1"=>"",
		"url_box_1"=>"",
		"image_box_2"=>"",
		"title_box_2"=>"",
		"url_box_2"=>"",
		"image_box_3"=>"",
		"title_box_3"=>"",
		"url_box_3"=>"",
		"image_box_4"=>"",
		"title_box_4"=>"",
		"url_box_4"=>"",
		
		
		
		) , $atts));			
		
		$output = '';
		$output .= '<div id="insights-wrapper" class="insights-wrapper">';
			$output .= '<div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-4">';
					$output.='<h2 class="section-title">'.$heading.'</h2>';
				$output .= '</div>';	
			$output .= '</div>';	
			$output .= '<div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-12">';
					$output.='<div style="height:60px;"></div>';
				$output .= '</div>';	
			$output .= '</div>';	
			$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex">';
				$output .= '<div class="vc_column_container vc_col-sm-3">';				
					$output.='<div class="insightWrapper">';
						$output.='<div class="insight-box">';
						$output .= '<a href="'.$url_box_1.'">';
							$output.='<div class="imageWrapper">';
								$output .= '<img src="'.$image_box_1.'" alt=""/>';
								$output .= '<i class="arrow-up"></i>';
							$output .= '</div>';
							$output.='<div class="urlWrapper">';
								$output.= $title_box_1;
							$output .= '</div>';
						$output .= '</a>';
						$output.='</div>';
					$output .= '</div>';
				$output .= '</div>';
				$output .= '<div class="vc_column_container vc_col-sm-3">';
					$output.='<div class="insightWrapper">';
											$output.='<div class="insight-box">';
					$output .= '<a href="'.$url_box_2.'">';
						$output.='<div class="imageWrapper">';
							$output .= '<img src="'.$image_box_2.'" alt=""/>';
							$output .= '<i class="arrow-up"></i>';
						$output .= '</div>';
						$output.='<div class="urlWrapper">';
							$output.= $title_box_2;
						$output .= '</div>';
						$output .= '</a>';
											$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
				$output .= '<div class="vc_column_container vc_col-sm-3">';
					$output.='<div class="insightWrapper">';
											$output.='<div class="insight-box">';
					$output .= '<a href="'.$url_box_3.'">';
						$output.='<div class="imageWrapper">';
							$output .= '<img src="'.$image_box_3.'" alt=""/>';
							$output .= '<i class="arrow-up"></i>';
						$output .= '</div>';
						$output.='<div class="urlWrapper">';
							$output.= $title_box_3;
						$output .= '</div>';
						$output .= '</a>';
											$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
				$output .= '<div class="vc_column_container vc_col-sm-3">';
					$output.='<div class="insightWrapper">';
											$output.='<div class="insight-box">';
					$output .= '<a href="'.$url_box_4.'">';
						$output.='<div class="imageWrapper">';
							$output .= '<img src="'.$image_box_4.'" alt=""/>';
							$output .= '<i class="arrow-up"></i>';
						$output .= '</div>';
						$output.='<div class="urlWrapper">';
							$output.=$title_box_4;
						$output .= '</div>';
						$output .= '</a>';
					$output .= '</div>';						
					$output .= '</div>';
				$output .= '</div>';
			
			$output .= '</div>';	
		$output .= '</div>';
		return $output;
		}

	add_shortcode('insights', 'shortcode_insights');
}
	?>