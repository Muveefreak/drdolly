<?php
/**
 * bs-likebox.php
 *---------------------------
 * [bs-likebox] short code & widget
 *
 */


/**
 * Publisher Likebox Shortcode
 */
class Publisher_Likebox_Shortcode extends BF_Shortcode {

	/**
	 * Flag used to determine print Facebook SDK in footer
	 *
	 * @var bool
	 */
	public static $print_footer_sdk = FALSE;


	/**
	 * Flag used to determine print Facebook SDK in footer
	 *
	 * @var bool
	 */
	public static $locale = 'en_US';


	function __construct( $id, $options ) {

		$id = 'bs-likebox';

		$_options = array(
			'defaults'            => array(
				'title'                => '',
				'show_title'           => 0,
				'icon'                 => '',
				'url'                  => '',
				'heading_color'        => '',
				'heading_style'        => 'default',
				'show_faces'           => 1,
				'show_posts'           => 0,
				'locale'               => 'en_US',
				'bs-show-desktop'      => TRUE,
				'bs-show-tablet'       => TRUE,
				'bs-show-phone'        => TRUE,
				'bs-text-color-scheme' => '',
			),
			'have_widget'         => TRUE,
			'have_vc_add_on'      => TRUE,
			'have_tinymce_add_on' => TRUE,
		);

		if ( isset( $options['shortcode_class'] ) ) {
			$_options['shortcode_class'] = $options['shortcode_class'];
		}

		if ( isset( $options['widget_class'] ) ) {
			$_options['widget_class'] = $options['widget_class'];
		}

		parent::__construct( $id, $_options );

		// Hooked to print Facebook JS SDK
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );

	}


	/**
	 * Callback: used to print Facebook SDK in footer
	 *
	 * Action filter: wp_footer
	 */
	public static function wp_footer() {

		// print footer if needed
		if ( ! self::$print_footer_sdk ) {
			return;
		}


		$locales = publisher_likebox_locales();

		// validate locale
		if ( empty( self::$locale ) || ! isset( $locales[ self::$locale ] ) ) {
			self::$locale = 'en_US';
		}

		?>

		<div id="fb-root"></div>
		<?php

	}


	/**
	 * Filter custom css codes for shortcode widget!
	 *
	 * @param $fields
	 *
	 * @return array
	 */
	function register_custom_css( $fields ) {

		return $fields;
	}


	/**
	 * Handle displaying of shortcode
	 *
	 * @param array  $atts
	 * @param string $content
	 *
	 * @return string
	 */
	function display( array $atts, $content = '' ) {

		self::$print_footer_sdk = TRUE;

		if ( ! empty( $atts['locale'] ) ) {
			self::$locale = $atts['locale'];
		}

		ob_start();

		publisher_set_prop( 'shortcode-bs-likebox-atts', $atts );

		publisher_get_view( 'shortcodes', 'bs-likebox' );

		publisher_clear_props();

		return ob_get_clean();

	}


	public function get_fields() {

		return array(
			array(
				'type' => 'tab',
				'name' => __( 'Facebook', 'publisher' ),
				'id'   => 'facebook',
			),
			array(
				'name'           => __( 'Facebook Page Link', 'publisher' ),
				'type'           => 'text',
				'id'             => 'url',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Show Posts', 'publisher' ),
				'id'             => 'show_posts',
				//
				'on-label'       => __( 'Show', 'publisher' ),
				'off-label'      => __( 'Hide', 'publisher' ),
				'type'           => 'switch',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Show Faces', 'publisher' ),
				'id'             => 'show_faces',
				//
				'on-label'       => __( 'Show', 'publisher' ),
				'off-label'      => __( 'Hide', 'publisher' ),
				'type'           => 'switch',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'             => __( 'Language', 'publisher' ),
				'id'               => 'locale',
				//
				'deferred-options' => 'publisher_likebox_locales',
				'type'             => 'select',
				//
				'vc_admin_label'   => FALSE,
			),
			array(
				'type' => 'tab',
				'name' => __( 'Heading', 'publisher' ),
				'id'   => 'heading',
			),
			array(
				'name'           => __( 'Title', 'publisher' ),
				'type'           => 'text',
				'id'             => 'title',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Show Title?', 'publisher' ),
				'id'             => 'show_title',
				'type'           => 'switch',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'desc'           => __( 'Select custom icon for share widget.', 'publisher' ),
				'name'           => __( 'Title Icon (Optional)', 'publisher' ),
				'type'           => 'icon_select',
				'id'             => 'icon',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Heading Custom Color', 'publisher' ),
				'desc'           => __( 'Change block heading color.', 'publisher' ),
				'id'             => 'heading_color',
				'type'           => 'color',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'             => __( 'Custom Heading Style', 'publisher' ),
				'desc'             => __( 'Specialize this block with custom heading.', 'publisher' ),
				'id'               => 'heading_style',
				'type'             => 'select_popup',
				'deferred-options' => array(
					'callback' => 'publisher_cb_heading_option_list',
					'args'     => array(
						TRUE
					),
				),
				//
				'vc_admin_label'   => FALSE,
			),
			array(
				'type' => 'tab',
				'name' => __( 'Design options', 'publisher' ),
				'id'   => 'design_options'
			),
			array(
				'section_class'  => 'style-floated-left bordered bf-css-edit-switch',
				'name'           => __( 'Show on Desktop', 'publisher' ),
				'id'             => 'bs-show-desktop',
				'type'           => 'switch',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'section_class'  => 'style-floated-left bordered bf-css-edit-switch',
				'name'           => __( 'Show on Tablet Portrait', 'publisher' ),
				'id'             => 'bs-show-tablet',
				'type'           => 'switch',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'section_class'  => 'style-floated-left bordered bf-css-edit-switch',
				'name'           => __( 'Show on Phone', 'publisher' ),
				'id'             => 'bs-show-phone',
				'type'           => 'switch',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Block Text Color Scheme', 'publisher' ),
				'id'             => 'bs-text-color-scheme',
				//
				'type'           => 'select',
				'options'        => array(
					''      => __( '-- Default --', 'publisher' ),
					'light' => __( 'White Color Texts', 'publisher' ),
				),
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Custom CSS Class', 'publisher' ),
				'section_class'  => 'bf-section-two-column',
				'id'             => 'custom-css-class',
				'type'           => 'text',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name'           => __( 'Custom ID', 'publisher' ),
				'section_class'  => 'bf-section-two-column',
				'id'             => 'custom-id',
				'type'           => 'text',
				//
				'vc_admin_label' => FALSE,
			),
			array(
				'name' => __( 'CSS box', 'publisher' ),
				'type' => 'css_editor',
				'id'   => 'css',
				//
			),
		);
	}


	/**
	 * Registers Visual Composer Add-on
	 */
	function register_vc_add_on() {

		vc_map( array(
			'name'           => __( 'FB Likebox', 'publisher' ),
			"base"           => $this->id,
			"weight"         => 10,
			"wrapper_height" => 'full',

			"category" => publisher_white_label_get_option( 'publisher' ),
			"params"   => $this->vc_map_listing_all(),
		) );

	} // register_vc_add_on


	function tinymce_settings() {

		return array(
			'name'    => __( 'LikeBox', 'publisher' ),
			'scripts' => array(
				array(
					'type'    => 'registered',
					'handles' => 'jquery',
				),
				array(
					'type' => 'inline',
					'data' => '
						function loadFbLikeBox(locale) {locale = locale || \'en_US\';(function (d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s);js.id = id;js.src = "//connect.facebook.net/"+locale+"/sdk.js#xfbml=1&version=v2.4";fjs.parentNode.insertBefore(js, fjs);}(document, \'script\', \'facebook-jssdk\'));}
						jQuery(function($) {
							loadFbLikeBox($(".fb-page").data(\'locale\'));
						});
					',
				),
			)
		);
	}
}


if ( ! function_exists( 'publisher_likebox_locales' ) ) {
	/**
	 * List of all localizations for Likebox
	 *
	 * @return array
	 */
	function publisher_likebox_locales() {

		return array(
			'en_US' => '-- English (US) -- ',
			'af_ZA' => 'Afrikaans',
			'ak_GH' => 'Akan',
			'am_ET' => 'Amharic',
			'ar_AR' => 'Arabic',
			'as_IN' => 'Assamese',
			'ay_BO' => 'Aymara',
			'az_AZ' => 'Azerbaijani',
			'be_BY' => 'Belarusian',
			'bg_BG' => 'Bulgarian',
			'bn_IN' => 'Bengali',
			'br_FR' => 'Breton',
			'bs_BA' => 'Bosnian',
			'ca_ES' => 'Catalan',
			'cb_IQ' => 'Sorani Kurdish',
			'ck_US' => 'Cherokee',
			'co_FR' => 'Corsican',
			'cs_CZ' => 'Czech',
			'cx_PH' => 'Cebuano',
			'cy_GB' => 'Welsh',
			'da_DK' => 'Danish',
			'de_DE' => 'German',
			'el_GR' => 'Greek',
			'en_GB' => 'English (UK)',
			'en_IN' => 'English (India)',
			'en_PI' => 'English (Pirate)',
			'en_UD' => 'English (Upside Down)',
			'eo_EO' => 'Esperanto',
			'es_CO' => 'Spanish (Colombia)',
			'es_ES' => 'Spanish (Spain)',
			'es_LA' => 'Spanish',
			'et_EE' => 'Estonian',
			'eu_ES' => 'Basque',
			'fa_IR' => 'Persian',
			'fb_LT' => 'Leet Speak',
			'ff_NG' => 'Fulah',
			'fi_FI' => 'Finnish',
			'fo_FO' => 'Faroese',
			'fr_CA' => 'French (Canada)',
			'fr_FR' => 'French (France)',
			'fy_NL' => 'Frisian',
			'ga_IE' => 'Irish',
			'gl_ES' => 'Galician',
			'gn_PY' => 'Guarani',
			'gu_IN' => 'Gujarati',
			'gx_GR' => 'Classical Greek',
			'ha_NG' => 'Hausa',
			'he_IL' => 'Hebrew',
			'hi_IN' => 'Hindi',
			'hr_HR' => 'Croatian',
			'hu_HU' => 'Hungarian',
			'hy_AM' => 'Armenian',
			'id_ID' => 'Indonesian',
			'ig_NG' => 'Igbo',
			'is_IS' => 'Icelandic',
			'it_IT' => 'Italian',
			'ja_JP' => 'Japanese',
			'ja_KS' => 'Japanese (Kansai)',
			'jv_ID' => 'Javanese',
			'ka_GE' => 'Georgian',
			'kk_KZ' => 'Kazakh',
			'km_KH' => 'Khmer',
			'kn_IN' => 'Kannada',
			'ko_KR' => 'Korean',
			'ku_TR' => 'Kurdish (Kurmanji)',
			'la_VA' => 'Latin',
			'lg_UG' => 'Ganda',
			'li_NL' => 'Limburgish',
			'ln_CD' => 'Lingala',
			'lo_LA' => 'Lao',
			'lt_LT' => 'Lithuanian',
			'lv_LV' => 'Latvian',
			'mg_MG' => 'Malagasy',
			'mk_MK' => 'Macedonian',
			'ml_IN' => 'Malayalam',
			'mn_MN' => 'Mongolian',
			'mr_IN' => 'Marathi',
			'ms_MY' => 'Malay',
			'mt_MT' => 'Maltese',
			'my_MM' => 'Burmese',
			'nb_NO' => 'Norwegian (bokmal)',
			'nd_ZW' => 'Ndebele',
			'ne_NP' => 'Nepali',
			'nl_BE' => 'Dutch (België)',
			'nl_NL' => 'Dutch',
			'nn_NO' => 'Norwegian (nynorsk)',
			'ny_MW' => 'Chewa',
			'or_IN' => 'Oriya',
			'pa_IN' => 'Punjabi',
			'pl_PL' => 'Polish',
			'ps_AF' => 'Pashto',
			'pt_BR' => 'Portuguese (Brazil)',
			'pt_PT' => 'Portuguese (Portugal)',
			'qu_PE' => 'Quechua',
			'rm_CH' => 'Romansh',
			'ro_RO' => 'Romanian',
			'ru_RU' => 'Russian',
			'rw_RW' => 'Kinyarwanda',
			'sa_IN' => 'Sanskrit',
			'sc_IT' => 'Sardinian',
			'se_NO' => 'Northern Sámi',
			'si_LK' => 'Sinhala',
			'sk_SK' => 'Slovak',
			'sl_SI' => 'Slovenian',
			'sn_ZW' => 'Shona',
			'so_SO' => 'Somali',
			'sq_AL' => 'Albanian',
			'sr_RS' => 'Serbian',
			'sv_SE' => 'Swedish',
			'sw_KE' => 'Swahili',
			'sy_SY' => 'Syriac',
			'sz_PL' => 'Silesian',
			'ta_IN' => 'Tamil',
			'te_IN' => 'Telugu',
			'tg_TJ' => 'Tajik',
			'th_TH' => 'Thai',
			'tk_TM' => 'Turkmen',
			'tl_PH' => 'Filipino',
			'tl_ST' => 'Klingon',
			'tr_TR' => 'Turkish',
			'tt_RU' => 'Tatar',
			'tz_MA' => 'Tamazight',
			'uk_UA' => 'Ukrainian',
			'ur_PK' => 'Urdu',
			'uz_UZ' => 'Uzbek',
			'vi_VN' => 'Vietnamese',
			'wo_SN' => 'Wolof',
			'xh_ZA' => 'Xhosa',
			'yi_DE' => 'Yiddish',
			'yo_NG' => 'Yoruba',
			'zh_CN' => 'Simplified Chinese (China)',
			'zh_HK' => 'Traditional Chinese (Hong Kong)',
			'zh_TW' => 'Traditional Chinese (Taiwan)',
			'zu_ZA' => 'Zulu',
			'zz_TR' => 'Zazaki',
		);
	}
}


/**
 * Publisher Likebox Widget
 */
class Publisher_Likebox_Widget extends BF_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		parent::__construct(
			'bs-likebox',
			sprintf( __( '%s - Like Box', 'publisher' ), publisher_white_label_get_option( 'publisher' ) ),
			array(
				'description' => __( 'Display a Facebook Like Box', 'publisher' )
			)
		);
	}


	/**
	 * Adds backend fields
	 */
	function load_fields() {

		// Back end form fields
		$this->fields = array(
			array(
				'name' => __( 'Title (Optional)', 'publisher' ),
				'id'   => 'title',
				'type' => 'text',
			),
			array(
				'name' => __( 'Facebook Page Link', 'publisher' ),
				'id'   => 'url',
				'desc' => __( 'EG. http://www.facebook.com/envato', 'publisher' ),
				'type' => 'text',
			),
			array(
				'name'      => __( 'Show Posts', 'publisher' ),
				'id'        => 'show_posts',
				'id'        => 'show_posts',
				'type'      => 'switch',
				'on-label'  => __( 'Show', 'publisher' ),
				'off-label' => __( 'Hide', 'publisher' ),
			),
			array(
				'name'      => __( 'Show Faces', 'publisher' ),
				'id'        => 'show_faces',
				'id'        => 'show_faces',
				'type'      => 'switch',
				'on-label'  => __( 'Show', 'publisher' ),
				'off-label' => __( 'Hide', 'publisher' ),
			),
			array(
				'name'             => __( 'Language', 'publisher' ),
				'id'               => 'locale',
				'id'               => 'locale',
				'type'             => 'select',
				'deferred-options' => 'publisher_likebox_locales',
			),
		);
	}
}
