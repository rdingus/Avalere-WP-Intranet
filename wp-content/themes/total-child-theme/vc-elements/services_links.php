<?php 
class vcServiceLinks extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_services_links_mapping' ) );
        add_shortcode( 'services_links', array( $this, 'vc_services_links_html' ) );
    }
     
    // Element Mapping
    public function vc_services_links_mapping() {
		$services = getObjectsByCPT('service',-1,array(),array(),array(),'title','ASC');
		
		if($services){
        $services = array_map(function($post){ return array($post->ID,$post->post_title);},$services);
		}

		
        // Stop all if VC is not enabled
    if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
    }
         
    // Map the block with vc_map()
    vc_map( 
  
        array(
            'name' => __('VC Services Links', 'text-domain'),
            'base' => 'services_links',
            'description' => __('Display Services Custom', 'text-domain'), 
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
                    'heading' => __( 'Description' , 'text-domain' ),
                    'param_name' => 'description',
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
                    'heading' => __( 'Button Url' , 'text-domain' ),
                    'param_name' => 'button_url',
                    'value'=>'',
                ),
                    array(
                    'type' => 'dropdown_multi',
                    'heading' => __( 'Services', 'text-domain' ),					
					'value'=>$services,
                    'param_name' => 'services',                    
                    
                ),                 				
				
				 				                 
                                
                     
            )
        )
    );   
        
    } 
     
     
    // Element HTML
    public function vc_services_links_html( $atts ) {
         extract(shortcode_atts(array(
		"title"=>"",
		"description"=>"",	
		"button_text"=>"",
		"button_url"=>"",
		"services"=>""
		) , $atts));
		$servicesarray = explode(",",$services);		
		$services = getObjectsByCPT('service',-1,array(),$servicesarray,array(),'title','ASC');
		if(!$services){
			$services = getObjectsByCPT('service',-1,array(),array(),array(),'title','ASC');
		}

		$output = '';
$output .= '<div id="services" class="services-wrapper">';
			$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" style="margin-left:0px;margin-right:0px;">';
				$output .= '<div class="vc_column_container vc_col-sm-4">';
					$output .='<h2 class="section-title">'.$title.'</h2>';
				$output .= '</div>';
				$output .= '<div class="vc_column_container vc_col-sm-4">';
					if($description){
						$output .='<div class="section-description">'.$description.'</div>';
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
		if($services){
			$totalServices = count($services);
			$firstPart = ceil($totalServices/2);
			$secondPart = $totalServices - $firstPart;
			$firstPartContent = $secondPartContent = '';
			
			foreach($services as $key=>$service){
				$position = $key + 1;
				$serviceTitle = $service->post_title;
				$serviceURL = get_permalink($service->ID);				
				
				if($position <= $firstPart){
					$firstlastclass = '';
					if($position == $firstPart){
						$firstlastclass = 'last';
					}
					
					$firstPartContent .= '<div class="service-item vc_column_container vc_col-sm-12 '.$firstlastclass.'">';
					$firstPartContent .= '<i class="arrow-right"></i><a href="'.$serviceURL.'">'.$serviceTitle.'</a>';
					$firstPartContent .= '</div>';	
				}else{
					$secondlastclass = '';
					if(($position / 2)%$firstPart == 0 && $position == ($secondPart * 2)){
						$secondlastclass = 'last';
					}
					$secondPartContent .= '<div class="service-item vc_column_container vc_col-sm-12 '.$secondlastclass.'">';
					$secondPartContent .= '<i class="arrow-right"></i><a href="'.$serviceURL.'">'.$serviceTitle.'</a>';
					$secondPartContent .= '</div>';	
				}				
				
			}
			$output .= '<div class="service-box">';
			$output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex" style="margin-left:0px;margin-right:0px;">';
					$output .= '<div class="vc_column_container vc_col-sm-6">';
						$output .= '<div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;width:100%;">';
							$output .= $firstPartContent;
						$output .= '</div>';
					$output .= '</div>';
					$output .= '<div class="vc_column_container vc_col-sm-6">';
						$output .= '<div class="vc_row wpb_row vc_row-fluid" style="margin-left:0px;margin-right:0px;width:100%;">';
							$output .= $secondPartContent;
						$output .= '</div>';
					$output .= '</div>';
			$output .= '</div>';			
			$output .= '</div>';	
				
		}
		$output .= '</div>';//#services end
		return $output;
    } 
     
} // End Element Class

// Element Class Init
new vcServiceLinks(); ?>