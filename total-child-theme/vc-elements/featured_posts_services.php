<?php
class vcFeaturedPostsServices extends WPBakeryShortCode
{    
 private $categoryPostsQuery = NULL;
    // Element Init
    function __construct()
    {		
		 $this->categoryPostsQuery = $this->categoryPostsQuery();       
        add_action('init', array(
            $this,
            'vc_featuredpostsservices_mapping'
        ));
        add_shortcode('vc_featuredpostsservices', array(
            $this,
            'vc_featuredpostsservices_html'
        ));        
    } 
   
    public function categoryPostsQuery()
    {
        $args   = array(
            'post_type' => 'post',
            'order' => 'DESC',
            'orderby' => 'date',			
            'posts_per_page' =>3,
            'post_status' => 'publish',
			'meta_query'=>array(array('key'=>'feature_on_services_intro_page','value'=>'Yes'))
        );		
        $object = new WP_Query($args);

		return $object;
    }

    // Element Mapping
    public function vc_featuredpostsservices_mapping()
    {
        
        // Stop all if VC is not enabled
        if (!defined('WPB_VC_VERSION')) {
            return;
        }

        // Map the block with vc_map()
        vc_map(array(
            'name' => __('VC Services Intro Featured Posts', 'text-domain'),
            'base' => 'vc_featuredpostsservices',
            'description' => __('Display featuredpostsservices', 'text-domain'),
            'category' => __('My Custom Shortcodes', 'text-domain'),
            'params' => array(   
			array(
                    'type' => 'textfield',                    
                    'class' => 'quote-heading-text',
                    'heading' => __( 'Heading' , 'text-domain' ),
                    'param_name' => 'heading_text',
                    'value'=>'',
                ),)                                				

        ));
        
    }
	function renderPosts($title){		
		$output = '';		
		//echo '<pre>';print_r($this->categoryPostsQuery);
		if($this->categoryPostsQuery->have_posts()):
		$wpex_columns = apply_filters( 'wpex_related_blog_posts_columns', wpex_get_mod( 'blog_related_columns', '3' ) );
		$output .= '<div id="insights-wrapper" class="insights-wrapper related-posts">';
			$output .= '<div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-4">';
					$output.='<h2 class="section-title">'.$title.'</h2>';
				$output .= '</div>';
			$output .= '</div>';		
			$output .= '<div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-12">';
					$output.='<div style="height:30px;"></div>';		
				$output .= '</div>';	
			$output .= '</div>';	
			$output .= '<div class="featured-insights">';
				$wpex_count = 0; 
				while ( $this->categoryPostsQuery->have_posts() ) : $this->categoryPostsQuery->the_post();
				ob_start();
				$wpex_count++; 
				include( locate_template( 'partials/blog/blog-single-related-entry.php' ) );
				$output .= ob_get_contents();
				ob_end_clean();
				if ( $wpex_columns == $wpex_count ) $wpex_count=0;
				endwhile; 
			$output .= '</div>';	
			$output .= '</div>';
		//$output .= '<div class="vc_row wpb_row vc_row-fluid">';
		//$html .= $this->renderPagination();
		endif;
		return $output;
	}
    // Element HTML
    public function vc_featuredpostsservices_html($atts)
    {   	
		extract(shortcode_atts(array(
		"heading_text"=>"",		
		) , $atts));	
        $output = '';
		$output .= $this->renderPosts($heading_text);        
        return $output;
    }
    
} // End Element Class

// Element Class Init

new vcFeaturedPostsServices();?>