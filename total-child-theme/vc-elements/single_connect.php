<?php 

class vsSingleConnect extends WPBakeryShortCode {

     

    // Element Init

    function __construct() {

        add_action( 'init', array( $this, 'vc_single_connect_mapping' ) );

        add_shortcode( 'vc_single_connect', array( $this, 'vc_single_connect_html' ) );

    }

     

    // Element Mapping

    public function vc_single_connect_mapping() {

		$postsList = $posts = array();

		$args   = array(

            'post_type' => 'post',

            'order' => 'DESC',

            'orderby' => 'date',

            'posts_per_page' => -1,

            'post_status' => 'publish',

            

        );

        $object = new WP_Query($args);

        if ($object->have_posts()):

			$postsList = $object->get_posts();            

        endif;

         $posts = array_map(function($post){ return array($post->ID,$post->post_title);},$postsList);

		

        // Stop all if VC is not enabled

    if ( !defined( 'WPB_VC_VERSION' ) ) {

            return;

    }

         

    // Map the block with vc_map()

    vc_map( 

  

        array(

            'name' => __('VC Single Connect Button', 'text-domain'),

            'base' => 'vc_single_connect',

            'description' => __('Display Single Connect Button', 'text-domain'), 

            'category' => __('My Custom Shortcodes', 'text-domain'),               

            'params' => array(   

                      array(

                            'type' => 'textfield',

                            'heading' => __('Title', 'text-domain'),

                            'param_name' => 'title',

                            'value' => ''

                        ),  
						 array(

                            'type' => 'textarea',

                            'heading' => __('Description', 'text-domain'),

                            'param_name' => 'description',

                            'value' => ''

                        ), 
						 array(

                            'type' => 'textfield',

                            'heading' => __('Button Text', 'text-domain'),

                            'param_name' => 'button_text',

                            'value' => ''

                        ), 
						 array(

                            'type' => 'textfield',

                            'heading' => __('Button URL', 'text-domain'),

                            'param_name' => 'button_url',

                            'value' => ''

                        ), 

						array(

                            'type' => 'attach_image',

                            'heading' => __('Button Icon', 'text-domain'),

                            'param_name' => 'button_icon',

                            'value' => ''

                        ), 

						array(

        	        	    'type' => 'colorpicker',            	        

                	    	'heading' => __( 'Button Background Color', 'text-domain' ),

							'value'=>'',

        		            'param_name' => 'button_background_color',                                        

                		),
						array(

        	        	    'type' => 'colorpicker',            	        

                	    	'heading' => __( 'Button Text Color', 'text-domain' ),

							'value'=>'',

        		            'param_name' => 'button_text_color',                                        

                		),  

						

				                 

                                

                     

            )

        )

    );   

        

    }

     

     

    // Element HTML

    public function vc_single_connect_html( $atts ) {

         extract(shortcode_atts(array(

		"title"=>"",

		"description"=>"",

		"button_text"=>"",

		"button_url"=>"",
		"button_icon"=>"",

		"button_background_color"=>"",

		"button_text_color"=>""				

		) , $atts));


        

        $output = '';

		$output .= '<div class="single_connect-wrapper">';						

			$output .= '<h2 class="section-title">'.$title.'</h2>';
	
	
	
			$output .= '<div class="contentWrapper">';
	
					$output .= $description;
					
	
			$output .= '</div>';		
	
				
			if($button_icon){
				$button_icon_url = wp_get_attachment_url($button_icon);
				$output .= '<a class="connectbutton" href="'.$button_url.'" style="background-color:'.$button_background_color.';color:'.$button_text_color.'"><img src="'.$button_icon_url.'" class="btn-icon"/>'.$button_text.'</a>';	
			}else{
				$output .= '<a class="connectbutton" href="'.$button_url.'" style="background-color:'.$button_background_color.';color:'.$button_text_color.'">'.$button_text.'</a>';	
			}

		$output .= '</div>';
		

		return $output;

    } 

     

} // End Element Class

 

// Element Class Init

new vsSingleConnect(); ?>
