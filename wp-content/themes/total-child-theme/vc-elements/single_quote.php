<?php 
class vcSingleQuote extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_single_quote_mapping' ) );
        add_shortcode( 'vc_single_quote', array( $this, 'vc_single_quote_html' ) );		

    }
	
     
    // Element Mapping
    public function vc_single_quote_mapping() {
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
            'name' => __('VC Single Quote', 'text-domain'),
            'base' => 'vc_single_quote',
            'description' => __('Display Single Employee Quote', 'text-domain'), 
            'category' => __('My Custom Shortcodes', 'text-domain'),               
            'params' => array(   
                      array(
        	        	    'type' => 'colorpicker',            	        
                	    	'heading' => __( 'Background Color', 'text-domain' ),
							'value'=>'#5fa8f4',
        		            'param_name' => 'background_color',                                        
                		),
						array(
        	        	    'type' => 'colorpicker',            	        
                	    	'heading' => __( 'Content Color', 'text-domain' ),
							'value'=>'#ffffff',
        		            'param_name' => 'content_color',                                        
                		),
						 array(
        	        	    'type' => 'textfield',            	        
                	    	'heading' => __( 'Quote Date', 'text-domain' ),
							'value'=>'',
        		            'param_name' => 'quote_date',                                        
                		), 
                array(
                    'type' => 'textarea_html',                    
                    'heading' => __( 'Quote', 'text-domain' ),
                    'param_name' => 'single_quote',
                   	'value'=>'',
                ),											
				array(
                    'type' => 'dropdown',                    
                    'heading' => __( 'Name', 'text-domain' ),
					'value'=>$employees,
                    'param_name' => 'name',                    
                    
                ),  
				array(
        	        	    'type' => 'textfield',            	        
                	    	'heading' => __( 'Box Class', 'text-domain' ),
							'value'=>'',
        		            'param_name' => 'box_class',                                        
                		), 
						array(
                    'type' => 'textfield',                    
                    'heading' => __( 'Box URL' , 'text-domain' ),
                    'param_name' => 'box_url',
                    'value'=>'',
                ),
				                  
                                
                     
            )
        )
    );   
        
    } 
     
     
    // Element HTML
    public function vc_single_quote_html( $atts ) {
         extract(shortcode_atts(array(
		"background_color"=>"",
		"content_color"=>"",
		"quote_date"=>"",
		"single_quote"=>"",
		"name"=>"",	
		"box_class"=>""	,
		"box_url"=>""
		) , $atts));
		$quoteStyle = $employeeStyle = $arrowStyle = '';
		$quoteStyleArray = $employeeStyleArray = $arrowStyleArray = array();
		$arrowStyleArray[] = 'border-top-width:10px;';
		$arrowStyleArray[] = 'border-bottom-width:10px;';
		$arrowStyleArray[] = 'border-left-width:10px;';
		if($background_color){
			$quoteStyleArray[] = 'background-color:'.$background_color.';';
			$arrowStyleArray[] = 'border-left-color:'.$background_color.';';
		}else{
			$quoteStyleArray[] = 'background-color:#5fa8f4;';
			$arrowStyleArray[] = 'border-left-color:#5fa8f4;';
		}
		if($content_color){
			$quoteStyleArray[] = 'color:'.$content_color.';';
			$employeeStyleArray[] = 'color:'.$content_color.';';
		}else{
			$quoteStyleArray[] = 'color:#ffffff;';
			$employeeStyleArray[] = 'color:#ffffff;';
		}
		
		if($quoteStyleArray){
			$quoteStyle .= 'style="'.implode('',$quoteStyleArray).'"';
		}
		if($employeeStyleArray){
			$employeeStyle .= 'style="'.implode('',$employeeStyleArray).'"';
		}
		if($arrowStyleArray){
			$arrowStyle .= 'style="'.implode('',$arrowStyleArray).'"';
		}
		$employeeObject = get_post($name);		
		$empolyeename = $employeeObject->post_title;
		$employeedesignation = wp_get_post_terms( $employeeObject->ID, 'jobtitles' )[0]->name;			
		$imageurl = wp_get_attachment_image_src(get_post_meta($employeeObject->ID,'emd_employee_photo',true),'thumbnail');

         $output = '';
		$output .= '<div class="single_quote-wrapper '.$box_class.'">';
		if($box_url){
				$output .= '<a href="'.$box_url.'"></a>';
			}
			$output .= '<div class="quote-box" '.$quoteStyle.'>';
						$output .= '<div class="dateWrapper">';
							//$output .= $quote_date;
						$output .= '</div>';
						$output .= '<div class="quote">&ldquo;'.$single_quote.'&rdquo;</div>';
						$output .= '<div class="employeeWrapper">';						
							$output .= '<div class="employeeImageWrapper"><img src="'.$imageurl[0].'" alt="'.$empolyeename.'" style="width:75px;height:75px;"/></div>';
							$output .= '<div class="employeeWrapper">';
								$output .= '<h6 '.$employeeStyle.'>'.$empolyeename.'</h6>';
								$output .= '<h6 '.$employeeStyle.'>'.$employeedesignation.'</h6>';
							$output .= '</div>';
							
						$output .= '</div>';
			$output .= '<i class="arrow-right" '.$arrowStyle.'></i>';
		
			
			$output .= '</div>';	
		$output .= '</div>';
		return $output;
    } 
     
} // End Element Class
 
// Element Class Init
new vcSingleQuote(); ?>