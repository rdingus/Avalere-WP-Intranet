<?php
	add_action( 'init', 'wpse16902_init' );
	function wpse16902_init() {
	    $GLOBALS['wp_rewrite']->use_verbose_page_rules = true;
	}

	add_filter( 'page_rewrite_rules', 'wpse16902_collect_page_rewrite_rules' );
	function wpse16902_collect_page_rewrite_rules( $page_rewrite_rules ) {
	    $GLOBALS['wpse16902_page_rewrite_rules'] = $page_rewrite_rules;
	    return array();
	}

	add_filter( 'rewrite_rules_array', 'wspe16902_prepend_page_rewrite_rules' );
	function wspe16902_prepend_page_rewrite_rules( $rewrite_rules ) {
	    return $GLOBALS['wpse16902_page_rewrite_rules'] + $rewrite_rules;
	}

	if(in_array('wp-mpdf/wp-mpdf.php', apply_filters('active_plugins', get_option('active_plugins')))){ 
	    
		function mpdf_pdfbutton_my( $opennewtab = false, $buttontext = '', $logintext = 'Login!', $print_button = true, $nofollow = false, $options = array() ) {
			$nofollowHtml = '';
			if ( $nofollow ) {
				$nofollowHtml = 'rel="nofollow" ';
			}

			//Check if button should displayed
			if ( get_option( 'mpdf_allow_all' ) != 1 || get_option( 'mpdf_need_login' ) != 0 ) {
				global $wpdb;
				global $post;
				$table_name = $wpdb->prefix . WP_MPDF_POSTS_DB;
				$sql        = 'SELECT general,login FROM ' . $table_name . ' WHERE post_id=' . $post->ID . ' AND post_type="' . $post->post_type . '" LIMIT 1';
				$dsatz      = $wpdb->get_row( $sql );

				if ( get_option( 'mpdf_allow_all' ) == 2 && $dsatz->general == false || get_option( 'mpdf_allow_all' ) == 3 && $dsatz->general == true ) {
					return;
				} else if ( ( get_option( 'mpdf_need_login' ) == 2 && $dsatz->login == false || get_option( 'mpdf_need_login' ) == 3 && $dsatz->login == true ) && is_user_logged_in() != true ) {
					if ( empty( $buttontext ) ) {
						$image = '/wp-content/plugins/wp-mpdf/pdf_lock.png';
						if ( isset( $options['pdf_lock_image'] ) ) {
							$image = $options['pdf_lock_image'];
						}

						$buttontext = '<img src="' . get_bloginfo( 'wpurl' ) . $image . '" alt="' . __( $logintext, 'wp-mpdf' ) . '" title="' . __( 'You must login first', 'wp-mpdf' ) . '" border="0" />';
					} else {
						$buttontext = __( $logintext, 'wp-mpdf' );
					}

					$pdf_button = '<a ' . $nofollowHtml . 'class="pdfbutton loginfirst" href="' . wp_login_url( get_permalink() ) . '" title="' . __( 'You must login first', 'wp-mpdf' ) . '">' . $buttontext . '</a>';

					if ( $print_button === true ) {
						echo $pdf_button;

						return;
					} else {
						return $pdf_button;
					}
				}
			}

		
			//Print the button
			if ( empty( $buttontext ) ) {
				$image = '/wp-content/themes/'.get_stylesheet().'/images/post-icon-download.png';
				if ( isset( $options['pdf_image'] ) ) {
					$image = $options['pdf_image'];
				}

				$buttontext = '<img src="' . get_bloginfo( 'wpurl' ) . $image . '" alt="' . __( 'This page as PDF', 'wp-mpdf' ) . '" border="0" />';
			}

			$x          = ! strpos( apply_filters( 'the_permalink', get_permalink() ), '?' ) ? '?' : '&amp;';
			$pdf_button = '<a ' . $nofollowHtml;
			if ( $opennewtab == true ) {
				$pdf_button .= 'target="_blank" ';
			}
			$pdf_button .= 'class="pdfbutton" href="' . apply_filters( 'the_permalink', get_permalink() ) . $x . 'output=pdf" download>' . __( $buttontext, 'wp-mpdf' ) . '</a>';

			if ( $print_button === true ) {
				echo $pdf_button;
			} else {
				return $pdf_button;
			}
		}
	}


class SoundCloudEmbed{
	private $str;
	private $urls=array();
	// https://regex101.com/r/3jgPki/3
	private $sc_re = '/((https?:\/\/)?(?:www\.)?(soundcloud\.com\/\S+|snd\.sc\/\S+))/i';
	private $scID_re = '/(?:url=)(https%3A%2F%2Fapi.soundcloud.com%2F(tracks|playlists|users)%2F(\d*))/';
	function __construct($str){
		$this->str=$str;
	}
	/**
	 * Check if string has soundcloud links
	 * @param  string  $str the string to check
	 * @return array   [provider][type][id][full_url][embed_src]
	 */
	public function has_soundCloud($str=null){
		$str=($str)?$str:$this->str;
		$res=array();
		preg_match_all($this->sc_re, $str, $matches);
		if($matches[0]){
			$i=0;
			foreach ($matches[0] as $url) {
				$matches[2][$i]=(empty($matches[2][$i]))?'https://':$matches[2][$i];
				$oembedUrl='soundcloud.com/oembed?format=json&url='.$matches[2][$i].$matches[3][$i];
				
				$response=json_decode($this->downloadPage($oembedUrl));
				if($response){
					preg_match($this->scID_re,$response->html,$r);
					if($r[0] && $r[2] && $r[3]){
					$res[]=$this->urls[] = array('provider'=>'soundcloud',
										   'type'=>$r[2],
										   'id'=>$r[3],
										   'embed_src'=>'https://w.soundcloud.com/player/?visual=true&amp;'.$r[0].'&amp;show_artwork=true',
										   'full_url'=>$url
										   );
					}
			    }
			    $i++;
			}
		}
		return $res;
	}
	/**
	 * return the string with soundcloud embeded players
	 * @return string replace all sc urls with players
	 */
	public function getEmbededText(){
		$str=$this->str;
		if(empty($this->urls)){
			$this->has_soundCloud();
		}
		foreach ($this->urls as $sc) {
			//$url = preg_replace('([\.]|[(\?)])', '\\$1', $sc['full_url']);
			$str = preg_replace('@'.str_replace('?', '\?',str_replace('.', '\.',$sc['full_url'])).'@',$this->buildEmbed($sc) , $str);
		}
		
		return $str;
	}
	/**
	 * get All youtube url in a string
	 * @return array [provider][type][id][full_url][embed_src]
	 */
	public function getLinks(){
		if(empty($this->urls)){
			$this->has_soundCloud();
		}
		return $this->urls;
	}
	/**
	 * Use cURL to getPageContent
	 * @param  string $url url to open
	 * @return string      html of the page
	 */
	private function downloadPage($url){
		//The cURL stuff...
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_URL, 'https://'.$url);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		$agent= 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36';
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$bcPage = curl_exec($ch);
		curl_close($ch);
		return $bcPage;
	}
	/**
	 * Create the embed html
	 * @param  array $id [provider][type][id][full_url][embed_src]
	 * @return string    embed html
	 */
	private function buildEmbed($id){
		if($id){
		return '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/'.$id['id'].'&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true"></iframe>';
		}
		return false;
	}
}