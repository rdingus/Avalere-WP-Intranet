<?php 
class vcCustomSectionHeading extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'custom_section_heading_mapping' ) );
        add_shortcode( 'vc_custom_section_heading', array( $this, 'custom_section_heading_html' ) );
    }
     
    // Element Mapping
    public function custom_section_heading_mapping() {
		$employeesList = $employees = array();
		$args   = array(
            'post_type' => 'emd_employee',
            'order' => 'ASC',
            'orderby' => 'title',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            
        );
        $object = new WP_Query($args);
        if ($object->have_posts()):
			$employeesList = $object->get_posts();            
        endif;
         $employees = array_map(function($employee){ return array($employee->ID,$employee->post_title);},$employeesList);
		
        // Stop all if VC is not enabled
    if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
    }
         
    // Map the block with vc_map()
    vc_map( 
  
        array(
            'name' => __('Custom Section Heading', 'text-domain'),
            'base' => 'vc_custom_section_heading',
            'description' => __('Display Heading inside section', 'text-domain'), 
            'category' => __('My Custom Shortcodes', 'text-domain'),               
            'params' => array(   
                array(
                    'type' => 'textfield',                    
                    'heading' => __( 'Title' , 'text-domain' ),
                    'param_name' => 'title',
                    'value'=>'',
                ),  
                array(
                    'type' => 'textarea',                    
                    'heading' => __( 'Description', 'text-domain' ),
                    'param_name' => 'description_text',
                   	'value'=>'',
                ),
				array(
                    'type' => 'textfield',                    
                    'heading' => __( 'Button Text' , 'text-domain' ),
                    'param_name' => 'button_text',
                    'value'=>'',
                ), 
				array(
                    'type' => 'textfield',                                        
                    'heading' => __( 'Button URL' , 'text-domain' ),
                    'param_name' => 'button_url',
                    'value'=>'',
                ), 		 
				                 
                                
                     
            )
        )
    );   
        
    } 
     
     
    // Element HTML
    public function custom_section_heading_html( $atts ) {
         extract(shortcode_atts(array(

		"title"=>"",
		"description_text"=>"",		
		"button_text"=>"",
		"button_url"=>"",
		) , $atts));
		
        
         $output = '';
		$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-4">';
					$output .='<h2 class="section-title">'.$title.'</h2>';
				$output .= '</div>';
				$output .= '<div class="vc_column_container vc_col-sm-4">';
					if($description_text){
						$output .='<div class="section-description">'.$description_text.'</div>';
					}
				$output .= '</div>';
				$output .= '<div class="vc_column_container vc_col-sm-4 textright">';
					$output .= '<div class="buttonWrapper">';
					if($button_text){
						$output .='<a class="section-button" href="'.$button_url.'">'.$button_text.'</a>';
					}
									$output .= '</div>';
				$output .= '</div>';
				
			$output .= '</div>';
		return $output;
    } 
     
} // End Element Class
 
// Element Class Init
new vcCustomSectionHeading(); ?>