<?php 
class vcEmployeeQuote extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_quote_mapping' ) );
        add_shortcode( 'vc_quote', array( $this, 'vc_quote_html' ) );
    }
     
    // Element Mapping
    public function vc_quote_mapping() {
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
            'name' => __('VC Employee Quote', 'text-domain'),
            'base' => 'vc_quote',
            'description' => __('Display Employee Quote', 'text-domain'), 
            'category' => __('My Custom Shortcodes', 'text-domain'),               
            'params' => array(   
                      
                array(
                    'type' => 'textarea',                    
                    'class' => 'quote-text',
                    'heading' => __( 'Quote', 'text-domain' ),
                    'param_name' => 'quote',
                   					'value'=>'',
                ),
				array(
                    'type' => 'textfield',
                    
                    'class' => 'quote-title-text',
                    'heading' => __( 'Title' , 'text-domain' ),
                    'param_name' => 'title',
                    					'value'=>'',
                ),
				array(
                    'type' => 'textarea_html',                    
                    'class' => 'quote-description-text',
                    'heading' => __( 'Description', 'text-domain' ),
                    'param_name' => 'description',
         					'value'=>'',
                ),  
				array(
                    'type' => 'attach_image',
                    'class' => 'quote-image-text',
                    'heading' => __( 'Image', 'text-domain' ),
					'value'=>'',
                    'param_name' => 'image',                    
                    
                ),
				array(
                    'type' => 'dropdown',
                    'class' => 'quote-employee-text',
                    'heading' => __( 'Name', 'text-domain' ),
					'value'=>$employees,
                    'param_name' => 'name',                    
                    
                ),  
				array(
                    'type' => 'textfield',                    
                    'class' => 'quote-button-text',
                    'heading' => __( 'Button Text' , 'text-domain' ),
                    'param_name' => 'button_text',
                    					'value'=>'',
                ), 
				array(
                    'type' => 'textfield',                    
                    'class' => 'quote-button-url',
                    'heading' => __( 'Button URL' , 'text-domain' ),
                    'param_name' => 'button_url',
                    					'value'=>'',
                ),                  
                                
                     
            )
        )
    );   
        
    } 
     
     
    // Element HTML
    public function vc_quote_html( $atts ) {
         extract(shortcode_atts(array(
		"quote"=>"",
		"name"=>"",
		"image"=>"",
		"title"=>"",
		"description"=>"",		
		"button_text"=>"",
		"button_url"=>"",
		) , $atts));
		$employeeObject = get_post($name);		
		$empolyeename = $employeeObject->post_title;
		$employeedesignation = wp_get_post_terms( $employeeObject->ID, 'jobtitles' )[0]->name;	
		$imageurl = wp_get_attachment_url($image);
        
         $output = '';
		$output .= '<div id="quote-wrapper" class="quote-wrapper">';
			$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex vc_row-o-content-middle" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-6">';
					$output .= '<div class="vc_column-inner padding0">';
						$output .= '<div class="imageWrapper">';
							$output .= '<img src="'.$imageurl.'" alt="'.$empolyeename.'"/>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
				$output .= '<div class="vc_column_container vc_col-sm-6">';
					$output .= '<div class="vc_column-inner padding0">';
						$quoteStyle = '';
						if($button_text){
							$quoteStyle = 'style="margin-top:30px;"';
							$output .= '<div class="button-section quote">';
								$output .='<a class="section-button" href="'.$button_url.'">'.$button_text.'</a>';
							$output .= '</div>';
						}

						$output .= '<div class="quoteTextWrapper" '.$quoteStyle.'>';
							$output .= '<h2 class="gradientEffect">&ldquo;'.$quote.'&rdquo;</h2>';
							$output .= '<div class="horizontalFixLine"></div>';
							$output .= '<div class="name"><strong>'.$empolyeename.'</strong></div>';
							$output .= '<div class="designation">'.$employeedesignation.'</div>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';					
			$output .= '</div>';			
			$output .= '<div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;">';
			$output .= '<div class="vc_column_container vc_col-sm-4">';
					$output .= '<h4 class="title">'.$title.'</h4>';					
				$output .= '</div>';
				$output .= '<div class="vc_column_container vc_col-sm-12">';					
					$output .= '<div class="description">'.$description.'</div>';
				$output .= '</div>';
			$output .= '</div>';
			
			
		$output .= '</div>';
		return $output;
    } 
     
} // End Element Class
 
// Element Class Init
new vcEmployeeQuote(); ?>