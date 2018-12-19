<?php
class vcTeam extends WPBakeryShortCode
{
    private $teamQuery = NULL;
    // Element Init
    function __construct()
    {
		$this->teamQuery = $this->teamQuery();
        add_action('init', array(
            $this,
            'vc_team_mapping'
        ));
        add_shortcode('vc_team', array(
            $this,
            'vc_team_html'
        ));
        add_action('wp_enqueue_scripts', array(
            $this,
            'team_assets'
        ));
        add_action('wp_ajax_teamloadmore', array(
            $this,
            'team_loadmore_ajax_handler'
        )); 
        add_action('wp_ajax_nopriv_teamloadmore', array(
            $this,
            'team_loadmore_ajax_handler'
        ));
        
        
    }
    public function teamQuery()
    {
		 		
        $args   = array(
            'post_type' => 'emd_employee',
            'order' => 'ASC',
            // order by EMD last name
            'meta_key' => 'emd_employee_lastname',
			'orderby' => 'meta_value title',
            'posts_per_page' => -1,
            'post_status' => 'publish',
			// include newly loaded employees that do not have remove_from_the_list value set 
			'meta_query'=>array(
					'relation'=>'OR',
					array(
						'key' => 'remove_from_the_list',
						'value' => 0,
						'compare' => '=',
				      ),
					  array(
						'key' => 'remove_from_the_list',
						'value' => '',
						'compare' => 'NOT EXISTS',
				      )
				)		
        );
        $object = new WP_Query($args);
		
		return $object;
    }
    public function team_assets()
    {
        wp_register_script('lazy-js', get_stylesheet_directory_uri() . '/js/jquery.lazy.min.js', array(
            'jquery'
        ), '', true);
        wp_enqueue_script('lazy-js');
        
        
		if(is_page(10048)){
        
        wp_register_script('team-ajax', get_stylesheet_directory_uri() . '/js/team-ajax.js', array(
            'jquery'
        ), '', true);
        
        wp_localize_script('team-ajax', 'team_loadmore_params', array(
            'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
            'posts' => json_encode($this->teamQuery->query), // everything about your loop is here            
            'max_page' => $this->teamQuery->max_num_pages
        ));
        
        
        wp_enqueue_script('team-ajax');
		}
    }
	public function title_filter( $where, &$wp_query )
	{
		global $wpdb;

		if ( $search_term = $wp_query->get( 'search_team_title' ) ) {

			$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( like_escape( $search_term ) ) . '%\'';
		}
		return $where;
	}
    public function team_loadmore_ajax_handler()
    {
        
        // prepare our arguments for the query
        $args          = json_decode(stripslashes($_POST['query']), true);
		$args['paged'] = $_POST['page'] + 1;

        if(isset($_POST['staff']) && $_POST['staff'] !=""){	
			if($_POST['staff'] != 'all'){
				$args['meta_query'][] = array('key'=>'staff','value'=>$_POST['staff']);									
			}
		}
		if(isset($_POST['service']) && $_POST['service'] !=""){			
			$args['meta_query'][] = array('key'=>'services','value'=>$_POST['service'],'compare'=>'LIKE');									
		}
		if(isset($_POST['title']) && $_POST['title'] !=""){		
			$args['search_team_title'] = $_POST['title'];									
		}
		
        
        add_filter( 'posts_where', array(&$this,'title_filter'), 10, 2 );
		$object = new WP_Query($args);
		remove_filter( 'posts_where', array(&$this,'title_filter'), 10, 2 );
        

        $members = array();
		$result = array('total'=>0,'items'=>'');
        if ($object->have_posts()):
            $members = $object->posts;			
			$result['total'] = $object->found_posts;
			$result['pages'] = $object->max_num_pages;
		 	$result['items'] =  $this->renderTeamBoxes($members,$args['paged']);
			
        endif;
       
        echo json_encode($result);
        
        
        die;
    }
    
    public function renderTeamBoxes($members = NULL,$paged = 0)
    {
        $output = '';
        if ($members) {
			$membersParts = array_chunk($members,4);
			$placeholder = get_stylesheet_directory_uri().'/images/team-placeholder.jpg';
			$bg = get_stylesheet_directory_uri().'/images/member-grid-bg.jpg';
			$output .= '<div id="team-page-'.$paged.'" class="team-page vc_row wpb_row vc_row-fluid m-0">';
				$output .= '<div class="vc_column_container vc_col-sm-12">';
				foreach ($membersParts as $partKey=>$members) {
					$output .= '<div id="team-row-'.$partKey.'" class="team-row vc_row wpb_row vc_row-fluid m-0">';
					$output .= '<div class="vc_column_container vc_col-sm-12">';
					$output .= '<div class="team-row-inner vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-o-equal-height vc_row-flex">';
					foreach ($members as $memberKey=>$member) {
						$image = '';
						$memberTitle       = $member->post_title;
						$memberContent 	   = $member->post_content;
						$memberDesignation = wp_get_post_terms($member->ID, 'jobtitles');
						$memberURL = get_permalink($member->ID);
						if (!is_wp_error($memberDesignation)) {
							$memberDesignationList = implode(",", array_map(function($a)
							{
								return $a->name;
							}, $memberDesignation));
						}
						$image = wp_get_attachment_url(get_post_meta($member->ID, 'emd_employee_photo', true));
						if ($image == '') {
							$image = get_stylesheet_directory_uri() . '/images/member-no-image.jpg';
						}
						$phone =get_post_meta($member->ID, 'emd_employee_phone', true);
						$email =get_post_meta($member->ID, 'emd_employee_email', true);
						
						
						$output .= '<div class="grid vc_column_container vc_col-sm-3 custom-padding">';

							$output .= '<div class="member-box">';
														$output .= '<a href="'.$memberURL.'">';
								$output .= '<div class="imageWrapper">';
									$output .= '<img src= "'.$placeholder.'" alt="'. $memberTitle . '" data-original="' . $image . '"/>';
									$output .= '<i class="arrow-up"></i>';
								$output .= '</div>';
								$output .= '<div class="member-content-wrapper">';
									$output .= '<h6 class="name">' . $memberTitle . '</h6>';
									$output .= '<h6 class="designation">' . $memberDesignationList . '</h6>';
								$output .= '</div>';
								$output .= '</a>';				
							$output .= '</div>';
							
						$output .= '</div>';
						$output .= '<div class="list vc_column_container vc_col-sm-12">';
							$output .= '<div class="member-wrapper">';
								$output .= '<div class="main vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex vc_row-o-content-middle m-0">';
									$output .= '<div class="vc_column_container vc_col-sm-4 custom-padding">';
										$output .= '<a href="'.$memberURL.'"><h4>'.$memberTitle.'</h4></a>';
									$output .= '</div>';
									$output .= '<div class="vc_column_container vc_col-sm-4 custom-padding">';
										$output .= $memberDesignationList;
									$output .= '</div>';
									$output .= '<div class="vc_column_container vc_col-sm-2 custom-padding">';
										if($phone){
												$output .= '<a href="tel:'.$phone.'" class="phone">'.$phone.'</a>';
											}else{
												$output .= 'Not Available';
											}
									$output .= '</div>';
									$output .= '<div class="vc_column_container vc_col-sm-2 custom-padding">';
										if($email){
												$output .= '<a href="mailto:'.$email.'" class="email"><i class="fa fa-envelope-o"></i></a>';
											}else{
												$output .= 'Not Available';
											}
									$output .= '</div>';																	
								$output .= '</div>';								

							$output .= '</div>';                
		
						$output .= '</div>';                
					}	
					$output .= '</div>';
					$output .= '</div>';
					

					
					$output .= '</div>';
				}
				$output .= '</div>';
				
			$output .= '</div>';
			$output .= "<script type='text/javascript'>
	
		(function($) { 
		var teamPageWrapper = $('#team-page-".$paged."');   		
		
		
        var images = teamPageWrapper.find('.member-box .imageWrapper img,.member-wrapper .imageWrapper img');
        images.each(function(index, element) {
            var original = $(this).attr('data-original');
            $(this).attr('src', original);
        });
		})(jQuery);
		</script>";
        }else{
			$output = '';
		}
        return $output;
    }
    // Element Mapping
    public function vc_team_mapping()
    {
        
        
        // Stop all if VC is not enabled
        if (!defined('WPB_VC_VERSION')) {
            return;
        }
        
        // Map the block with vc_map()
        vc_map(array(
            'name' => __('VC team', 'text-domain'),
            'base' => 'vc_team',
            'description' => __('Display team', 'text-domain'),
            'category' => __('My Custom Shortcodes', 'text-domain'),
            'params' => array()
        ));
        
    }
    
    
    // Element HTML
    public function vc_team_html($atts)
    {
        extract(shortcode_atts(array(
            "no_of_team_members" => ""
            
        ), $atts));
        
       
	   
        $loader = get_stylesheet_directory_uri().'/images/ajax-loader.gif';
		$loader2 = get_stylesheet_directory_uri().'/images/loadingDots.gif';
        
		$staff = get_field_object('field_5b9f69fe1a03d');
		$services = getObjectsByCPT('service',-1,array(),array(),array(),'title','ASC');	
		
        $output = '';
		
        $output .= '<div class="team-wrapper grid-view">';
        	$output .= '<div class="filter-wrapper vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex vc_row-o-content-middle m-0">';
       	 		$output .= '<div id="category-archive-searchform" class="vc_column_container vc_col-sm-8 custom-padding filterteam">';
					$output .= '<ul style="margin:0px;">';
					/*$output .= '<label><select id="stafffilter">';
					//$output .= '<option value="">Staff</option>';
					foreach($staff['choices'] as $key=>$choice){
						$output .= '<option value="'.$key.'">'.$choice.'</option>';
					}
					$output .= '</select></label>';*/
					$output .= '<li>';
					$output .= '<label><select id="servicefilter">';
					$output .= '<option value="">All Areas of Expertise</option>';
					foreach($services as $service){
						$output .= '<option value="'.$service->ID.'">'.$service->post_title.'</option>';
					}
					$output .= '</select></label>';
					$output .= '</li>';
					$output .= '<li class="sf-field-search">';
					$output .= '<label>';
					$output .= '<input id="memberfilter" type="text" placeholder="Search..." style="line-height:1em;padding-bottom:7px;"/>';
					
					$output .= '</label>';
					$output .= '</li>';
					$output .= '</ul>';
		        $output .= '</div>';
    		    $output .= '<div class="vc_column_container vc_col-sm-4 custom-padding textright">';
        			$output .= '<div class="view-type-wrapper">';
				        $output .= '<ul><li><a href="javascript:void(0);" class="grid-view active" data-layout="grid-view"><i class="fa fa-th"></i></a></li><li><a href="javascript:void(0);" class="list-view" data-layout="list-view"><i class="fa fa-bars"></i></a></li></ul>';
		        	$output .= '</div>';
	        	$output .= '</div>';
    	    $output .= '</div>';
	        $output .= '<div class="vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex vc_row-o-content-middle m-0">';
    		    $output .= '<div style="height:30px;"></div>';
    	    $output .= '</div>';
        	$output .= '<div class="vc_row wpb_row vc_row-fluid m-0">';
				$output .= '<div class="list-header vc_column_container vc_col-sm-12">';
					$output .= '<div class="list-header-wrapper">';
						$output .= '<div class="list-header-inner-wrapper vc_row wpb_row vc_row-fluid vc_row-o-equal-height vc_row-flex vc_row-o-content-middle m-0">';
							$output .= '<div class="vc_column_container vc_col-sm-4 custom-padding">';
								$output .= 'Name';
							$output .= '</div>';
							$output .= '<div class="vc_column_container vc_col-sm-4 custom-padding">';
								$output .= 'Title';
							$output .= '</div>';
							$output .= '<div class="vc_column_container vc_col-sm-2 custom-padding">';								
								$output .= 'Phone';									
							$output .= '</div>';
							$output .= '<div class="vc_column_container vc_col-sm-2 custom-padding">';
								$output .= 'Email';									
							$output .= '</div>';															
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
        		$output .= '<div class="vc_column_container vc_col-sm-12 ">';
					$output .= '<div class="teamMembersWrapper">';
						$output .= '<div class="teamOverlay"><img src="'.$loader2.'"/></div>';
        				$output .= '<div id="team-member-list" data-pagination="1" data-pages="'.$this->teamQuery->max_num_pages.'" data-total="'.$this->teamQuery->found_posts.'">';							
					        $output .= $this->renderTeamBoxes($this->teamQuery->get_posts());						
				        $output .= '</div>';
					$output .= '</div>';
					
			        $output .= '<div id="team-member-scroller" class="vc_row wpb_row vc_row-fluid textcenter m-0">';
				        $output .= '<div class="vc_column_container vc_col-sm-12 custom-padding">';
					        $output .= '<div class="team-member-loader vcex-button theme-button clean black inline animate-on-hover"><img src="'.$loader.'"/>Please wait ...</div>';
				        $output .= '</div>';
			        $output .= '</div>';
					
		        $output .= '</div>';            
	        $output .= '</div>';
        $output .= '</div>';
		$output .= "<script type='text/javascript'>
	
	(function($) { 
		   
		var teamlistWrapper = $('#team-member-list');		
		var layoutChangerWrapper = $('.view-type-wrapper');
		
		layoutChangerWrapper.find('a').on('click',function(){
			var layoutClass = $(this).data('layout');
			layoutChangerWrapper.find('a').removeClass('active');
			$(this).addClass('active');
			teamlistWrapper.find('.bio').hide();
			teamlistWrapper.find('.member-wrapper').removeClass('open');
			if (layoutClass == 'grid-view') {
				$('.team-wrapper').removeClass('list-view').addClass('grid-view');
			}
			else if(layoutClass = 'list-view') {
				$('.team-wrapper').removeClass('grid-view').addClass('list-view');
			}
		});		




})(jQuery);
	</script>";
        
        return $output;
    }
    
} // End Element Class

// Element Class Init
new vcTeam();
?>