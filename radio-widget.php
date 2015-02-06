<?php
/*
    Plugin Name: PBP Radio widget
    Plugin URI: http://projoktibangla.net
    Author: PBSMF
    Author URI: http://projoktibangla.net
    Description: PBP Radio Widget produces a widget allowing users listen to a radio station from your website.
    Tags: radio widget, PBP, radio, radio stations, radio player, audio element html5, widget
	  Version: 1.0
    Requires at least: 3.0.1
    Tested up to: 4.1
    Stable tag: 1.0
    License: GPLv2
    License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/


/*
 * Adds Radio_widget widget.
 */
class PBP_Radio_widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        $radio = array(
            'default' => 'http://sip.mrtcommunication.com:6092/;stream.mp3',
            'volume' => 8,
            'stations' => array(
                1 => array( 'name' => "Dhaka FM 90.4", 'url' => "http://118.179.219.244:8000/;"),
                2 => array( 'name' => "abc Radio 89.2", 'url' => "http://live.abcradiobd.fm:8282/;"),
                3 => array( 'name' => "Radio Today 89.6", 'url' => "http://202.22.195.242:7170/;stream.mp3"),
                4 => array( 'name' => "Radio AAmar 88.6", 'url' => "http://192.184.9.158:8343/;stream.mp3"),
                5 => array( 'name' => "Radio furti 88.0", 'url' => "http://121.200.62.53:1021/;stream.mp3"),
                6 => array( 'name' => "Radio Bhumi 92.8", 'url' => "http://sip.mrtcommunication.com:6092/;stream.mp3"),
				7 => array( 'name' => "Radio Shadhin 92.4", 'url' => "http://184.107.144.218:8240/;stream.mp3"),
				8 => array( 'name' => "BD Betar", 'url' => "http://108.178.13.122:8081/;stream.mp3"),
                )
        );
        $radio = maybe_serialize ( $radio );
        add_option( 'pbp_radio_settings', $radio );
        parent::__construct(
            'PBP_radio_widget', // Base ID
            __( 'PBP Radio Widget' ), // Name
            array( 'description' => __( 'Radio player widget', 'Uses html5 audio element to play radio in sidebar' ),
            ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        $radio = get_option( 'pbp_radio_settings' );
        $radio = maybe_unserialize( $radio );
        $title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
        echo $args[ 'before_widget' ];
        ?>
        <div class="radio-widget">
            <div class="radio_block">
                <audio  src="<?php echo $instance[ 'default' ]; ?>" id="radio_player"
                        <?php echo ( $instance[ 'auto' ] ) ? 'autoplay="autoplay"': ''; ?> >
                </audio>
                <div id="radio_controls">
                    <div class="radio_cube">
                        <button type="button" id="radio_play">
                            <?php echo ( $instance[ 'auto' ] ) ? __( 'Pause' ) : __( 'Play' ); ?>
                        </button>
                        <button type="button" id="radio_mute"><?php echo __( 'Mute' ); ?></button>
                    </div>
                    <div class="radio_cube">
                        <input  type="range" id="radio_volume" min="0.1" max="1" step="0.1"
                                value="<?php echo $instance[ 'volume' ]; ?>">
                    </div>
                  </div>
            </div>
            <div class="radio_block">
                <select id="radio_stations">
                <?php 
                    foreach( $radio[ 'stations' ] as $station ){
                        $line = "<option value=\"{$station[ 'url' ]}\"";
                        $line = ( $station[ 'url' ] == $instance[ 'default' ] ) ? $line . " selected=\"selected\">" : $line . ">";
                        $line = $line."{$station[ 'name' ]}</option>\n\t\t\t\t";
                        echo $line;
                    }
                ?>
                </select>
            </div>
        </div>
        <?php
        echo $args['after_widget'];
    }

    /**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
    public function form( $instance ) {
        $radio = get_option( 'pbp_radio_settings' );
        $radio = maybe_unserialize( $radio );
        $instance = wp_parse_args( (array) $instance, $radio );
        ?>
        <p><fieldset class="basic-grey">
            <legend><?php echo __( 'Settings' ); ?>:</legend>
            <label>
                <span><?php echo __( 'Title' ); ?></span>
                <input  id="<?php echo $this->get_field_id( 'title' ); ?>" 
                        name="<?php echo $this->get_field_name('title'); ?>" type="text" 
                        value="<?php esc_attr_e($instance[ 'title' ]); ?>" />
            </label>
            <label>
                <span><?php echo __( 'Default' ); ?></span>
                <select id="<?php echo $this->get_field_id( 'default' ); ?>" 
                        name="<?php echo $this->get_field_name( 'default' ); ?>">
                    <?php 
                        foreach( $instance[ 'stations' ] as $station ){
                            $line = "<option value=\"{$station[ 'url' ]}\"";
                            $line = ( $station[ 'url' ] == $instance[ 'default' ] ) ? $line . " selected=\"selected\">" : $line . ">";
                            $line = $line."{$station[ 'name' ]}</option>\n\t\t\t\t";
                            echo $line;
                        }
                    ?>
                </select>
            </label>
            <label>
                <span><?php echo __( 'Volume' ); ?></span>
                0.1<input type="range" min="0.1" max="1" step="0.1"
                        id="<?php echo $this->get_field_id( 'volume' ); ?>" 
                        name="<?php echo $this->get_field_name( 'volume' ); ?>"
                        value="<?php echo $instance[ 'volume' ]; ?>">10
            </label>
            <label>
                <span><?php echo __( 'AutoPlay' ); ?></span>
                <input  type="checkbox" value="1" <?php if($instance[ 'auto' ]) echo 'checked="checked"'; ?>
                        id="<?php echo $this->get_field_id( 'auto' ); ?>" 
                        name="<?php echo $this->get_field_name( 'auto' ); ?>"
                        />
            </label>
        </fieldset></p>
		<?php
	}

    /**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags($new_instance[ 'title' ]);
        $instance[ 'default' ] = strip_tags($new_instance[ 'default' ]);
        $instance[ 'volume' ] = strip_tags($new_instance[ 'volume' ]);
        $instance[ 'auto' ] = ($new_instance[ 'auto' ]) ? strip_tags($new_instance[ 'auto' ]) : 0;
        return $instance;
    }
}

require_once dirname( __FILE__ ) . '/radio-widget-settings.php';
if( is_admin() )
    $my_settings_page = new PBP_Radio_Widget_Settings();

// Function registering the radio widget
function pbp_register_radio_widget() {
    register_widget( 'PBP_Radio_Widget' );
}
add_action( 'widgets_init', 'pbp_register_radio_widget');

// Function registering radio widget scripts
function pbp_register_radio_css_js() {
	wp_enqueue_script(
        'radio-script',
        plugins_url().'/pbp-radio-widget/radio-js.js',
        array( 'jquery' )
	);
    wp_enqueue_style( 
        'radio-style',
        plugins_url().'/pbp-radio-widget/radio-style.css'
    );
}
add_action( 'wp_enqueue_scripts', 'pbp_register_radio_css_js' );
add_action( 'widgets_init', 'pbp_register_radio_css_js');

// Function removing radio widget options
function pbp_remove_options() {
    delete_option( 'pbp_radio_settings' );
}
register_deactivation_hook( __FILE__, 'pbp_remove_options' );
