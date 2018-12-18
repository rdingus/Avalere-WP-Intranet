<?php 
class vcServiceInsights extends WPBakeryShortCode {
     
    // Element Init
    function __construct() {
        add_action( 'init', array( $this, 'vc_services_insights_mapping' ) );
        add_shortcode( 'vc_services_insights', array( $this, 'vc_services_insights_html' ) );
    }
     
    // Element Mapping
    public function vc_services_insights_mapping() {
		$posts = getObjectsByCPT('post',-1,array(),array(),array(),'date','DESC');
		
		if($posts){
        $posts = array_map(function($post){ return array($post->ID,$post->post_title);},$posts);
		}

		
        // Stop all if VC is not enabled
    if ( !defined( 'WPB_VC_VERSION' ) ) {
            return;
    }
         
    // Map the block with vc_map()
    vc_map( 
  
        array(
            'name' => __('VC Insights', 'text-domain'),
            'base' => 'vc_services_insights',
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
                    array(
                    'type' => 'dropdown_multi',
                    'class' => 'quote-employee-text',
                    'heading' => __( 'Posts', 'text-domain' ),					
					'value'=>$posts,
                    'param_name' => 'posts',                    
                    
                ),                 				
				
				 				                 
                                
                     
            )
        )
    );   
        
    } 
     
     
    // Element HTML
    public function vc_services_insights_html( $atts ) {
         extract(shortcode_atts(array(
		 "heading_text"=>"",
		"posts"=>""		
		) , $atts));

		$postsarray = $posts ? explode(",",$posts) : array();		
		$posts = NULL;		
		$args   = array(
            'post_type' => 'post',
            'order' => 'DESC',
            'orderby' => 'date',			
            'post_status' => 'publish',
			'posts_per_page' => '3',             
        );
		if(empty($postsarray)){
			$args['posts_per_page'] = 3;
		}else{
			$args['post__in'] = $postsarray; 
		}

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
			/* $featuredInsightsParts = array_chunk($featuredInsights,4);					
    foreach($featuredInsightsParts as $rowkey=>$featuredInsightPart){
        $output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex">';
          foreach($featuredInsightPart as $key=>$featuredInsight){
			  $metaWrapper = array();
	  $index = $key+1;
	  $featuredInsightTitle = mb_strimwidth($featuredInsight->post_title,0,50,"...");
	  $featuredInsightURL = get_permalink($featuredInsight->ID);
	  $featuredInsightDate = $featuredInsight->post_date;
	  
	  if($authors = get_field('post_authors',$featuredInsight->ID)){
		  $authorsList = getObjectsByCPT('emd_employee',-1,array(),$authors);
		  $authorsName = array();
		  foreach($authorsList as $author){
			  $authorsName[] = $author->post_title;
			  
		  }
		  $metaWrapper[] = implode(", ",$authorsName);

	  
	  }
	  $metaWrapper[] = date('M d, Y',strtotime($featuredInsightDate));
	  $metaWrapperString = implode(" | ",$metaWrapper);
	  $featuredInsightSummary = mb_strimwidth(get_field('post_summary',$featuredInsight->ID),0,150,'...');
          $output .= '<div class="featuredInsight-wrapper wpb_column vc_column_container vc_col-sm-3" >
            <div class="featuredInsight-box">
              <h4> <a href="'.$featuredInsightURL.'">'.$featuredInsightTitle.'</a></h4>
              <div class="metaWrapper">'.$metaWrapperString.'</div>
              <div class="summary">'.$featuredInsightSummary.'</div>
            </div>
          </div>';
           }
        $output .= '</div>';
         }*/
			/*foreach($posts as $post){
				$url = get_permalink($post->ID);
				$image = get_the_post_thumbnail_url($post->ID);
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
			}*/
				
				
			
			//$output .= '</div>';	
		$output .= '</div>';
		}
		return $output;
    } 
     
} // End Element Class
 vc_add_shortcode_param( 'dropdown_multi', 'dropdown_multi_settings_field' );
function dropdown_multi_settings_field( $param, $value ) {
   $param_line = '';
   $param_line .= '<button class="button '.esc_attr( $param['param_name'] ).'-'.esc_attr($param['type']).'">Clear Selection</button>';
   $param_line .= '<select multiple name="'. esc_attr( $param['param_name'] ).'" class="wpb_vc_param_value wpb-input wpb-select '. esc_attr( $param['param_name'] ).'-'. esc_attr($param['type']).'">';
   foreach ( $param['value'] as $text_val => $val ) {
       if ( is_numeric($text_val) && (is_string($val) || is_numeric($val)) ) {
                    $text_val = $val[1];
                }
                $text_val = __($val[1], "js_composer");
                $selected = '';

                if(!is_array($value)) {
                    $param_value_arr = explode(',',$value);
                } else {
                    $param_value_arr = $value;
                }

                if ($value!=='' && in_array($val[0], $param_value_arr)) {
                    $selected = ' selected="selected"';
                }
                $param_line .= '<option value="'.$val[0].'"'.$selected.'>'.$text_val.'</option>';
            }
   $param_line .= '</select>';
	$param_line .= "<script type='text/javascript'>
	
	(function($) {
		/* var last_valid_selection = null;

          $('select.".esc_attr( $param['param_name'] ).'-'. esc_attr($param['type'])."').change(function(event) {

            if ($(this).val().length > 4) {
				alert('You can not select more than 4 items.');
              $(this).val(last_valid_selection);
            } else {
              last_valid_selection = $(this).val();
            }
          });*/
		//for clear selection
    var multiDrowdownClearButton = $('button.".esc_attr( $param['param_name'] ).'-'.esc_attr($param['type'])."');
	multiDrowdownClearButton.on('click',function(){
		multiDrowdownClearButton.next()[0].selectedIndex = -1;
	});
	})(jQuery);
	</script>";
   return  $param_line;
}
// Element Class Init
new vcServiceInsights(); ?>