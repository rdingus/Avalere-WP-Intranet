<?php 
class vcServiceInsightsDynamic extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_services_insights_dynamic_mapping' ) );
        add_shortcode( 'vc_services_insights_dynamic', array( $this, 'vc_services_insights_dynamic_html' ) );
    }
     
    // Element Mapping
    public function vc_services_insights_dynamic_mapping() {	

		
        // Stop all if VC is not enabled
    if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
    }
         
    // Map the block with vc_map()
    vc_map( 
  
        array(
            'name' => __('VC Insights Dynamic', 'text-domain'),
            'base' => 'vc_services_insights_dynamic',
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
    public function vc_services_insights_dynamic_html( $atts ) {
         extract(shortcode_atts(array(
		 "heading_text"=>"",
		"posts"=>""		
		) , $atts));
		$postsarray = explode(",",$posts);		
		$posts = NULL;		
		$args   = array(
            'post_type' => 'post',
            'order' => 'DESC',
            'orderby' => 'date',
			'posts_per_page'=>4,
            'post_status' => 'publish',
            
        );
        $object = new WP_Query($args);
        if ($object->have_posts()):
			$posts = $object->get_posts();            
        endif;

		$output = '';
        if($posts){
         
		$output .= '<div id="insights-wrapper" class="insights-wrapper">';
			$output .= '<div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-4">';
					$output.='<h2 class="section-title">'.$heading_text.'</h2>';
				$output .= '</div>';	
			$output .= '</div>';	
			$output .= '<div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-12">';
					$output.='<div style="height:60px;"></div>';
				$output .= '</div>';	
			$output .= '</div>';	
			$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex">';
			foreach($posts as $post){
				$url = get_permalink($post->ID);
				$image = get_the_post_thumbnail_url($post->ID);
				if($image == ''){
					$image = get_stylesheet_directory_uri() . '/images/member-no-image.jpg';
				}
				$title = $post->post_title;
				$output .= '<div class="vc_column_container vc_col-sm-3">';				
					$output.='<div class="insightWrapper">';
						$output.='<div class="insight-box">';
						$output .= '<a href="'.$url.'">';
							$output.='<div class="imageWrapper">';
								$output .= '<img src="'.$image.'" alt=""/>';
								$output .= '<i class="arrow-up"></i>';
							$output .= '</div>';
							$output.='<div class="urlWrapper">';
								$output.= $title;
							$output .= '</div>';
						$output .= '</a>';
						$output.='</div>';
					$output .= '</div>';
				$output .= '</div>';
			}
				
				
			
			$output .= '</div>';	
		$output .= '</div>';
		}
		return $output;
    } 
     
} // End Element Class

// Element Class Init
new vcServiceInsightsDynamic(); ?>