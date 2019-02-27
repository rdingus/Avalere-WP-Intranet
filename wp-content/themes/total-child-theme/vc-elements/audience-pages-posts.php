<?php 
class vcAudiencePagesPosts extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_audience_pages_posts_mapping' ) );
        add_shortcode( 'vc_audience_pages_posts', array( $this, 'vc_audience_pages_posts_html' ) );
    }
     
    // Element Mapping
    public function vc_audience_pages_posts_mapping() {		

		
        // Stop all if VC is not enabled
    if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
    }
         
    // Map the block with vc_map()
    vc_map( 
  
        array(
            'name' => __('VC Audience Pages Posts', 'text-domain'),
            'base' => 'vc_audience_pages_posts',
            'description' => __('Display Latest Post by Category', 'text-domain'), 
            'category' => __('My Custom Shortcodes', 'text-domain'),               
            'params' => array(   
			array(
                    'type' => 'textfield',                    
                    'class' => 'quote-heading-text',
                    'heading' => __( 'Heading' , 'text-domain' ),
                    'param_name' => 'heading_text',
                    'value'=>'',
                ),                                   				
				
				 				                 
                                
                     
            )
        )
    );   
        
    } 
     
     
    // Element HTML
    public function vc_audience_pages_posts_html( $atts ) {
         extract(shortcode_atts(array(
		 "heading_text"=>"",		
		) , $atts));
		
		global $post;
		$postsarray = array();		
		$posts = NULL;		
		$args   = array(
            'post_type' => 'post',
            'order' => 'DESC',
            'orderby' => 'date',			
            'post_status' => 'publish',
			'posts_per_page' => '3',  
			'meta_query'=>array(array('key'=>'audience_posts','value'=>sprintf(':"%s";', $post->ID),'compare'=>'LIKE'))           
        );
		/*if(empty($postsarray)){
			$args['posts_per_page'] = 3;
		}else{
			$args['post__in'] = $postsarray; 
		}*/
 wp_reset_query();
        $object = new WP_Query($args);        

		$output = '';
        if($object->have_posts()){
         $wpex_columns = apply_filters( 'wpex_related_blog_posts_columns', wpex_get_mod( 'blog_related_columns', '3' ) );
		$output .= '<div id="insights-wrapper" class="insights-wrapper related-posts">';
			$output .= '<div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-4">';
					$output.='<h2 class="section-title">'.$heading_text.'</h2>';
				$output .= '</div>';	
			$output .= '</div>';	
			$output .= '<div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-12">';
					$output.='<div style="height:30px;"></div>';
				$output .= '</div>';	
			$output .= '</div>';	
			$output .= '<div class="featured-insights">';			
			//$output .= '<div class="vc_row wpb_row vc_row-fluid">';
			$wpex_count = 0; 
			 while ( $object->have_posts() ) : $object->the_post();
			 ob_start();
				 $wpex_count++; 
				 include( locate_template( 'partials/blog/blog-single-related-entry.php' ) );
				 $output .= ob_get_contents();
    		ob_end_clean();
				 if ( $wpex_columns == $wpex_count ) $wpex_count=0;
			 endwhile; 
			$output .= '</div>';				
		$output .= '</div>';
		}
		 wp_reset_query();
		return $output;
    } 
     
} // End Element Class

// Element Class Init
new vcAudiencePagesPosts(); ?>