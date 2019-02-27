<?php 
class vsSingleCategory extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_single_category_mapping' ) );
        add_shortcode( 'vc_single_category', array( $this, 'vc_single_category_html' ) );
    }
     
    // Element Mapping
    public function vc_single_category_mapping() {
		$posts = array();
		$terms = get_terms( array(
		    'taxonomy' => 'category',
		    'hide_empty' => false,
		) );       
         $posts = array_map(function($post){ return array($post->term_id,$post->name);},$terms);
		
        // Stop all if VC is not enabled
    if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
    }
         
    // Map the block with vc_map()
    vc_map( 
  
        array(
            'name' => __('VC Single Category (POST)', 'text-domain'),
            'base' => 'vc_single_category',
            'description' => __('Display Single Category', 'text-domain'), 
            'category' => __('My Custom Shortcodes', 'text-domain'),               
            'params' => array(   

                        array(
                            'type' => 'attach_image',
                            'heading' => __('Image', 'text-domain'),
                            'value' => '',
                            'param_name' => 'image',
							'description'=>'Image size : 640x480'
                        ),                                       
						array(
        	        	    'type' => 'dropdown',            	        
                	    	'heading' => __( 'Category', 'text-domain' ),
							'value'=>$posts,
        		            'param_name' => 'posts',                                        
                		),  
						array(
        	        	    'type' => 'colorpicker',            	        
                	    	'heading' => __( 'Background Color', 'text-domain' ),
							'value'=>'#ffffff',
        		            'param_name' => 'background_color',                                        
                		),  
						
				                 
                                
                     
            )
        )
    );   
        
    }
     
     
    // Element HTML
    public function vc_single_category_html( $atts ) {
         extract(shortcode_atts(array(		
		"image"=>"",		
		"posts"=>"",
		"background_color"=>""				
		) , $atts));

		$postObject = get_term($posts);		
		$postTitle = $postObject->name;		
		
		$postURL = get_term_link($postObject);

		$arrow = 'arrow-up';
		$placeholder = get_stylesheet_directory_uri().'/images/placeholder-640320.jpg';
		$bgStyle = '';
        if($background_color){
			$bgStyle = 'style="background-color:'.$background_color.'"';
		}
        $output = '';
		$output .= '<div class="single_post-wrapper">';						
		$output .= '<a href="'.$postURL.'" '.$bgStyle.'>';
		

		
				$imageurl = wp_get_attachment_url($image);								
				$output .= '<div class="imageWrapper">';
					$output .= '<img src="'.$placeholder.'" alt="'.$postTitle.'" data-original="'.$imageurl.'"/>';
					$output .= '<i class="'.$arrow.'"></i>';
				$output .= '</div>';		
		
		$output .= '<div class="contentWrapper">';
				$output .= '<h4>'.$postTitle.'</h4>';
		$output .= '</div>';		
			
		$output .= '</a>';	
		$output .= '</div>';
		$output .= "<script type='text/javascript'>
	
	(function($) {	
    $(window).bind('load', function() {
		var images = $('.single_category-wrapper .imageWrapper img');
		images.each(function(index, element) {
			
			var original = $(this).attr('data-original');
    		$(this).attr('src',original);
		});
		
       
    });
	
	})(jQuery);
	</script>";
		return $output;
    } 
     
} // End Element Class
 
// Element Class Init
new vsSingleCategory(); ?>