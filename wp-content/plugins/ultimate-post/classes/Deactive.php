<?php
/**
 * Deactivation Action.
 * 
 * @package ULTP\Notice
 * @since v.1.1.0
*/
namespace ULTP;
defined('ABSPATH') || exit;

/**
 * Deactive class.
*/
class Deactive {

	private $PLUGIN_NAME = 'PostX';
	private $PLUGIN_SLUG = 'ultimate-post';
	private $PLUGIN_VERSION = ULTP_VER;
    private $API_ENDPOINT = 'https://inside.wpxpo.com';
    
	/**
	 * Setup class.s
	 * @since v.1.1.0
	*/
    public function __construct() {
		global $pagenow;
        if ( $pagenow == 'plugins.php' ) {
			add_action( 'admin_footer', array( $this, 'get_source_data_callback' ) );
		}
		add_action( 'wp_ajax_ultp_deactive_plugin', array( $this, 'send_plugin_data' ) );
	}

	/**
	 * Get Plugin Data Response
	 *
	 * @since v.1.0.0
	 * @param STRING
	 * @return ARRAY | Product return data
	*/
	public function send_plugin_data( $type , $site = '' ) {
		
		if ( current_user_can( 'administrator' ) ) {
			$data = $this->get_required_data();
			$data['site_type'] = $site ? $site : get_option( '__ultp_site_type', '' );
			$data['type'] = $type ? $type : 'deactive';
			$form_data = isset($_POST) ? ultimate_post()->ultp_rest_sanitize_params($_POST) : array(); //phpcs:Ignore
			
			if ( isset( $form_data['action'] ) ) {
				unset( $form_data['action'] );
			}
			$response = wp_remote_post( 
				$this->API_ENDPOINT,
				array(
					'method'      => 'POST',
					'timeout'     => 30,
					'redirection' => 5,
					'headers'     => array(
						'user-agent' => 'wpxpo/' . md5( esc_url( home_url() ) ) . ';',
						'Accept'     => 'application/json',
					),
					'blocking'    => true,
					'httpversion' => '1.0',
					'body'        => array_merge($data, $form_data),
				)
			);

			return $response;
		}	
	}

	/**
	 * Deactive Form Settings Data
	 *
	 * @since v.1.0.0
	 * @param NULL
	 * @return ARRAY | Settings Parameters
	*/
	public function get_deactive_settings() {
		$attr = array(
			array(
				'id'          	=> 'no-need',
				'input' 		=> false,
				'text'        	=> __( "I no longer need the plugin.", "ultimate-post" )
			),
			array(
				'id'          	=> 'better-plugin',
				'input' 		=> true,
				'text'        	=> __( "I found a better plugin.", "ultimate-post" ),
				'placeholder' 	=> __( "Please share which plugin.", "ultimate-post" ),
			),
			array(
				'id'          	=> 'stop-working',
				'input' 		=> true,
				'text'        	=> __( "The plugin suddenly stopped working.", "ultimate-post" ),
				'placeholder' 	=> __( "Please share more details.", "ultimate-post" ),
			),
			array(
				'id'          	=> 'not-working',
				'input' 		=> false,
				'text'        	=> __( "I could not get the plugin to work.", "ultimate-post" )
			),
			array(
				'id'          	=> 'temporary-deactivation',
				'input' 		=> false,
				'text'        	=> __( "It's a temporary deactivation.", "ultimate-post" )
			),
			array(
				'id'          	=> 'other',
				'input' 		=> true,
				'text'        	=> __( "Other.", "ultimate-post" ),
				'placeholder' 	=> __( "Please share the reason.", "ultimate-post" ),
			),
		);
		return $attr;
	}

	/**
	 * Deactive HTML View
	 *
	 * @since v.1.0.0
	 * @param NULL
	 * @return STRING | HTML Data
	*/
    public function get_source_data_callback() {
		$this->deactive_html_container();
		$this->deactive_container_css();
		$this->deactive_container_js();
	}

	public function deactive_html_container() { 
		?>
    	<div class="ultp-modal" id="ultp-deactive-modal">
            <div class="ultp-modal-wrap">
			
                <div class="ultp-modal-header">
                    <h2><?php esc_html_e( "Quick Feedback", "ultimate-post" ); ?></h2>
                    <button class="ultp-modal-cancel"><span class="dashicons dashicons-no-alt"></span></button>
                </div>

                <div class="ultp-modal-body">
                    <h3><?php esc_html_e( "If you have a moment, please let us know why you are deactivating PostX:", "ultimate-post" ); ?></h3>
                    <ul class="ultp-modal-input">
						<?php foreach ($this->get_deactive_settings() as $key => $setting) { ?>
							<li>
								<label>
									<input type="radio" <?php echo $key == 0 ? 'checked="checked"' : ''; ?> id="<?php echo esc_attr($setting['id']); ?>" name="<?php echo esc_attr($this->PLUGIN_SLUG); ?>" value="<?php echo esc_attr($setting['text']); ?>">
									<div class="ultp-reason-text"><?php echo esc_html($setting['text']); ?></div>
									<?php if( isset($setting['input']) && $setting['input'] ) { ?>
										<textarea placeholder="<?php echo esc_attr($setting['placeholder']); ?>" class="ultp-reason-input <?php echo $key == 0 ? 'ultp-active' : ''; ?> <?php echo esc_html($setting['id']); ?>"></textarea>
									<?php } ?>
								</label>
							</li>
						<?php } ?>
                    </ul>
                </div>

                <div class="ultp-modal-footer">
                    <a class="ultp-modal-submit ultp-btn ultp-btn-primary" href="#"><?php esc_html_e( "Submit & Deactivate", "ultimate-post" ); ?><span class="dashicons dashicons-update rotate"></span></a>
                    <a class="ultp-modal-deactive" href="#"><?php esc_html_e( "Skip & Deactivate", "ultimate-post" ); ?></a>
				</div>
				
            </div>
        </div>
		<?php 
	}

	/**
	 * Deactivation Forms CSS File
	 *
	 * @since v.1.0.0
	 * @param NULL
	 * @return STRING | CSS Code
	 */
	public function deactive_container_css() { ?>
		<style type="text/css">
			.ultp-modal {
                position: fixed;
                z-index: 99999;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                background: rgba(0,0,0,0.5);
                display: none;
                box-sizing: border-box;
                overflow: scroll;
            }
            .ultp-modal * {
                box-sizing: border-box;
            }
            .ultp-modal.modal-active {
                display: block;
            }
			.ultp-modal-wrap {
                max-width: 870px;
                width: 100%;
                position: relative;
                margin: 10% auto;
                background: #fff;
            }
			.ultp-reason-input{
				display: none;
			}
			.ultp-reason-input.ultp-active{
				display: block;
			}
			.rotate{
				animation: rotate 1.5s linear infinite; 
			}
			@keyframes rotate{
				to{ transform: rotate(360deg); }
			}
			.ultp-popup-rotate{
				animation: popupRotate 1s linear infinite; 
			}
			@keyframes popupRotate{
				to{ transform: rotate(360deg); }
			}
			#ultp-deactive-modal {
				background: rgb(0 0 0 / 85%);
				overflow: hidden;
			}
			#ultp-deactive-modal .ultp-modal-wrap {
				max-width: 570px;
				border-radius: 5px;
				margin: 5% auto;
				overflow: hidden
			}
			#ultp-deactive-modal .ultp-modal-header {
				padding: 17px 30px;
				border-bottom: 1px solid #ececec;
				display: flex;
				align-items: center;
				background: #f5f5f5;
			}
			#ultp-deactive-modal .ultp-modal-header .ultp-modal-cancel {
				padding: 0;
				border-radius: 100px;
				border: 1px solid #b9b9b9;
				background: none;
				color: #b9b9b9;
				cursor: pointer;
				transition: 400ms;
			}
			#ultp-deactive-modal .ultp-modal-header .ultp-modal-cancel:focus {
				color: red;
				border: 1px solid red;
				outline: 0;
			}
			#ultp-deactive-modal .ultp-modal-header .ultp-modal-cancel:hover {
				color: red;
				border: 1px solid red;
			}
			#ultp-deactive-modal .ultp-modal-header h2 {
				margin: 0;
				padding: 0;
				flex: 1;
				line-height: 1;
				font-size: 20px;
				text-transform: uppercase;
				color: #8e8d8d;
			}
			#ultp-deactive-modal .ultp-modal-body {
				padding: 25px 30px;
			}
			#ultp-deactive-modal .ultp-modal-body h3{
				padding: 0;
				margin: 0;
				line-height: 1.4;
				font-size: 15px;
			}
			#ultp-deactive-modal .ultp-modal-body ul {
				margin: 25px 0 10px;
			}
			#ultp-deactive-modal .ultp-modal-body ul li {
				display: flex;
				margin-bottom: 10px;
				color: #807d7d;
			}
			#ultp-deactive-modal .ultp-modal-body ul li:last-child {
				margin-bottom: 0;
			}
			#ultp-deactive-modal .ultp-modal-body ul li label {
				align-items: center;
				width: 100%;
			}
			#ultp-deactive-modal .ultp-modal-body ul li label input {
				padding: 0 !important;
				margin: 0;
				display: inline-block;
			}
			#ultp-deactive-modal .ultp-modal-body ul li label textarea {
				margin-top: 8px;
				width: 350px;
			}
			#ultp-deactive-modal .ultp-modal-body ul li label .ultp-reason-text {
				margin-left: 8px;
				display: inline-block;
			}
			#ultp-deactive-modal .ultp-modal-footer {
				padding: 0 30px 30px 30px;
				display: flex;
				align-items: center;
				/* border-top: 1px solid #e5e5e5;
				box-shadow: 0 0px 8px 0px rgb(0 0 0 / 12%); */
			}
			#ultp-deactive-modal .ultp-modal-footer .ultp-modal-submit {
				display: flex;
				align-items: center;
			}
			#ultp-deactive-modal .ultp-modal-footer .ultp-modal-submit span {
				margin-left: 4px;
				display: none;
			}
			#ultp-deactive-modal .ultp-modal-footer .ultp-modal-submit.loading span {
				display: block;
			}
			#ultp-deactive-modal .ultp-modal-footer .ultp-modal-deactive {
				margin-left: auto;
				color: #c5c5c5;
				text-decoration: none;
			}
			.wpxpo-btn-tracking-notice {
				display: flex;
                align-items: center;
                flex-wrap: wrap;
                padding: 5px 0;
			}
			.wpxpo-btn-tracking-notice .wpxpo-btn-tracking {
				margin: 0 5px;
				text-decoration: none;
			}
		</style>
    <?php }


	/**
	 * Deactivation Forms JS File
	 *
	 * @since v.1.0.0
	 * @param NULL
	 * @return STRING | JS Code
	 */
	public function deactive_container_js() { ?>
        <script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				'use strict';

				// Modal Radio Input Click Action
				$('.ultp-modal-input input[type=radio]').on( 'change', function(e) {
					$('.ultp-reason-input').removeClass('ultp-active');
					$('.ultp-modal-input').find( '.'+$(this).attr('id') ).addClass('ultp-active');
				});

				// Modal Cancel Click Action
				$( document ).on( 'click', '.ultp-modal-cancel', function(e) {
					$( '#ultp-deactive-modal' ).removeClass( 'modal-active' );
				});

				// Deactivate Button Click Action
				$( document ).on( 'click', '#deactivate-ultimate-post', function(e) {
					e.preventDefault();
					$( '#ultp-deactive-modal' ).addClass( 'modal-active' );
					$( '.ultp-modal-deactive' ).attr( 'href', $(this).attr('href') );
					$( '.ultp-modal-submit' ).attr( 'href', $(this).attr('href') );
				});

				// Submit to Remote Server
				$( document ).on( 'click', '.ultp-modal-submit', function(e) {
					e.preventDefault();
					
					$(this).addClass('loading');
					const url = $(this).attr('href')

					$.ajax({
						url: '<?php echo esc_url(admin_url('admin-ajax.php')); ?>',
						type: 'POST',
						data: { 
							action: 'ultp_deactive_plugin',
							cause_id: $('input[type=radio]:checked').attr('id'),
							cause_title: $('.ultp-modal-input input[type=radio]:checked').val(),
							cause_details: $('.ultp-reason-input.ultp-active').val()
						},
						success: function (data) {
							$( '#ultp-deactive-modal' ).removeClass( 'modal-active' );
							window.location.href = url;
						},
						error: function(xhr) {
							console.log( 'Error occured. Please try again' + xhr.statusText + xhr.responseText );
						},
					});

				});

			});
		</script>
    <?php }


	/**
	 * Get All the Installed Plugin Data
	 *
	 * @since v.1.0.0
	 * @param NULL
	 * @return ARRAY | Return Plugin Information
	 */
	public function get_installed_plugins() {
		if (! function_exists( 'get_plugins' ) ) {
            include ABSPATH . '/wp-admin/includes/plugin.php';
        }

		$active = array();
        $inactive = array();
        $all_plugins = get_plugins();
        $active_plugins = get_option( 'active_plugins', array() );
		if (is_multisite()) {
			$active_plugins = array_merge($active_plugins, array_keys(get_site_option( 'active_sitewide_plugins', array() )));
		}

        foreach ( $all_plugins as $key => $plugin ) {
			$arr = array();
			
			$arr['name'] 	= isset( $plugin['Name'] ) ? $plugin['Name'] : '';
			$arr['url'] 	= isset( $plugin['PluginURI'] ) ? $plugin['PluginURI'] : '';
			$arr['author'] 	= isset( $plugin['Author'] ) ? $plugin['Author'] : '';
			$arr['version'] = isset( $plugin['Version'] ) ? $plugin['Version'] : '';

			if (in_array( $key, $active_plugins )) {
				$active[$key] = $arr;
			} else {
				$inactive[$key] = $arr;
			}
		}

		return array( 'active' => $active, 'inactive' => $inactive );		
	}


	/**
	 * Get All the Theme Installed
	 *
	 * @since v.1.0.0
	 * @param NULL
	 * @return ARRAY | Return Theme Information
	*/
	public function get_installed_themes() {
		$theme_data = array();
		$all_themes = wp_get_themes();	

		if (is_array($all_themes)) {
			foreach ($all_themes as $key => $theme) {
				$attr = array();
				$attr['name'] 		= $theme->Name;
				$attr['url'] 		= $theme->ThemeURI;
				$attr['author'] 	= $theme->Author;
				$attr['version'] 	= $theme->Version;
				$theme_data[$key] 	= $attr;
			}
		}
		return $theme_data;
	}

	/**
	 * Get Current User IP
	 *
	 * @since v.1.0.0
	 * @param NULL
	 * @return STRING | Return IP
	*/
	public function get_user_ip() {
		$response = wp_remote_get( 'https://icanhazip.com/' );
        if ( is_wp_error( $response ) ) {
            return '';
        } else {
			$user_ip = trim( wp_remote_retrieve_body( $response ) );
			return filter_var( $user_ip, FILTER_VALIDATE_IP ) ? $user_ip : '';
		}
    }

	/**
	 * Get All the Data Collected
	 *
	 * @since v.1.0.0
	 * @param NULL
	 * @return ARRAY | All Send Data
	*/
	public function get_required_data() {
		global $wpdb;
		$user = wp_get_current_user();
		$user_count = count_users();
		$plugins_data = $this->get_installed_plugins();

		$data = array(
			'name' => get_bloginfo( 'name' ),
			'home' => esc_url( home_url() ),
			'admin_email' => $user->user_email,
			'first_name' => isset($user->user_firstname) ? $user->user_firstname : '',
			'last_name' => isset($user->user_lastname) ? $user->user_lastname : '',
			'display_name' => $user->display_name,
			'wordpress' => get_bloginfo( 'version' ),
			'memory_limit' => WP_MEMORY_LIMIT,
			'debug_mode' => ( defined('WP_DEBUG') && WP_DEBUG ) ? 'Yes' : 'No',
			'locale' => get_locale(),
			'multisite' => is_multisite() ? 'Yes' : 'No',

			'themes' => $this->get_installed_themes(),
			'active_theme' => get_stylesheet(),
			'users' => isset($user_count['total_users']) ? $user_count['total_users'] : 0,
			'active_plugins' => $plugins_data['active'],
			'inactive_plugins' => $plugins_data['inactive'],
			'server' => isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_key($_SERVER['SERVER_SOFTWARE']) : '',
			
			'timezone' => date_default_timezone_get(),
			'php_curl' => function_exists( 'curl_init' ) ? 'Yes' : 'No',
			'php_version' => function_exists('phpversion') ? phpversion() : '',
			'upload_size' => size_format( wp_max_upload_size() ),
			'mysql_version' => $wpdb->db_version(),
			'php_fsockopen' => function_exists( 'fsockopen' ) ? 'Yes' : 'No',

			'ip' => $this->get_user_ip(),
			'plugin_name' => $this->PLUGIN_NAME,
			'plugin_version' => $this->PLUGIN_VERSION,
			'plugin_slug' => $this->PLUGIN_SLUG
		);

		return $data;
	}
}