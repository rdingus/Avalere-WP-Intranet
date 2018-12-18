<?php 
class vsSingleStatistic extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_single_statistic_mapping' ) );
        add_shortcode( 'vc_single_statistic', array( $this, 'vc_single_statistic_html' ) );
    }
     
    // Element Mapping
    public function vc_single_statistic_mapping() {
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
            'name' => __('VC Single Statistic', 'text-domain'),
            'base' => 'vc_single_statistic',
            'description' => __('Display Single Statistic Quote', 'text-domain'), 
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
                    'heading' => __( 'Title', 'text-domain' ),
                    'param_name' => 'title',
					'value'=>'',
                ),
				array(
                    'type' => 'textfield',                    
                    'heading' => __( 'Statistic' , 'text-domain' ),
                    'param_name' => 'percentage',
                    'value'=>'',
                ),
				array(
                    'type' => 'textfield',                    
                    'heading' => __( 'Box Class' , 'text-domain' ),
                    'param_name' => 'box_class',
                    'value'=>'',
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
    public function vc_single_statistic_html( $atts ) {
         extract(shortcode_atts(array(
		"background_color"=>"",
		"content_color"=>"",		
		"title"=>"",
		"percentage"=>"",
		"box_class"=>""	,
		"box_url"=>""	
		
		) , $atts));
		$statisticStyle = $percentageStyle = $arrowStyle = '';
		$statisticStyleArray = $percentageStyleArray = $arrowStyleArray = array();
		$arrowStyleArray[] = 'border-top-style:solid;';
		$arrowStyleArray[] = 'border-top-width:20px;';
		$arrowStyleArray[] = 'border-top-color:#e1e7ee;';
		$arrowStyleArray[] = 'border-bottom:none;';
		$arrowStyleArray[] = 'border-left-style:solid;';
		$arrowStyleArray[] = 'border-left-width:20px;';
	
		if($background_color){
			$statisticStyleArray[] = 'background-color:'.$background_color.';';			
		}else{
			$statisticStyleArray[] = 'background-color:#ffffff;';
			
		}
		if($content_color){
			$statisticStyleArray[] = 'color:'.$content_color.';';
			$arrowStyleArray[] = 'border-left-color:'.$content_color.';';
		}else{
			$statisticStyleArray[] = 'color:#5fa8f4;';
			$arrowStyleArray[] = 'border-left-color:#5fa8f4;';
		}
		
		if($statisticStyleArray){
			$statisticStyle .= 'style="'.implode('',$statisticStyleArray).'"';
		}		
		if($arrowStyleArray){
			$arrowStyle .= 'style="'.implode('',$arrowStyleArray).'"';
		}
        
         $output = '';
		$output .= '<div class="single_statistic-wrapper '.$box_class.'">';
			if($box_url){
				$output .= '<a href="'.$box_url.'"></a>';
			}
			$output .= '<div class="statistic-box textcenter" '.$statisticStyle.'>';
				$output .= '<div class="statistic-content-wrapper">';
					if(strpos($percentage,'%') !== false){
						$percentagenumber = str_replace("%","",$percentage);						
						$output .= '<div class="percentageWrapper"><span class="counter" data-count="'.$percentagenumber.'">0</span>%</div>';
					}else if(strpos($percentage,'M') !== false){
                        $percentagenumber = str_replace("M","",$percentage);                        
                        $output .= '<div class="percentageWrapper"><span class="counter" data-count="'.$percentagenumber.'">0</span>M</div>';
                    }else if(strpos($percentage,'B') !== false){
                        $percentagenumber = str_replace("B","",$percentage);                        
                        $output .= '<div class="percentageWrapper"><span class="counter" data-count="'.$percentagenumber.'">0</span>B</div>';
                    }else{
						$output .= '<div class="percentageWrapper"><span class="counter" data-count="'.$percentage.'">0</span></div>';
					}
					
					$output .= '<div class="titleWrapper">'.$title.'</div>';
				$output .= '</div>';
				$output .= '<i '.$arrowStyle.'></i>';			
			$output .= '</div>';
			
		$output .= '</div>';
		$output .= "<script type='text/javascript'>
	
	(function($) {
    

        $(window).scroll(function() {
			$('.single_statistic-wrapper').each(function(index, element) {
				var mainWrapper = $(this);
            	var oTop = mainWrapper.offset().top - window.innerHeight;
				if ($(window).scrollTop() > oTop) {
					mainWrapper.find('.counter').each(function() {
                    var thisObject = $(this),
                        countTo = thisObject.attr('data-count');
                    $({
                        countNum: thisObject.text()
                    }).animate({
                            countNum: countTo
                        },

                        {

                            duration: 1000,
                            easing: 'swing',
                            step: function() {
                                thisObject.text(Math.floor(this.countNum));
                            },
                            complete: function() {
                                thisObject.text(this.countNum);
                                //alert('finished');
                            }

                        });
                });
               
				}
            });            


        });


    

})(jQuery);
					</script>";
		return $output;
    } 
     
} // End Element Class
 
// Element Class Init
new vsSingleStatistic(); ?>