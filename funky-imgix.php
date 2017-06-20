<?php
/**
 * @package Funky_Imgix
 * @version 1.0
 */
/*
Plugin Name: Funky Imgix
Plugin URI: http://funkhaus.us
Description: Grab your Imgix images quickly and easily.
Author: Funkhaus
Version: 1.0
Author URI: http://funkhaus.us
*/

    /*
     *  Get the Imgix URL for an image. Returns false if no image found.
     *  $image_id can be the ID of the image itself, the ID of a page with a featured image, or null to use the current post.
     *  $parameters can be an array or string of effects to apply on Imgix.
     */
    function get_the_imgix_image( $parameters = '', $image_id = false, $image_size = 'thumbnail' ){

        if( $image_id == false ){
            $image_id = get_post();
        }

        // Check to see if we're looking for a featured image
        if( has_post_thumbnail($image_id) ){
            $image_id = get_post_thumbnail_id($image_id);
        }

        // Get the attachment URL
        $image_url = wp_get_attachment_image_src( $image_id, $image_size );

        // Abort if no URL
        if( empty($image_url) ) {
            return false;
        }

        // Turn parameters array into string
        if( gettype($parameters) == 'array' ){
            $parameters = '?' . http_build_query($parameters);
        }

        // Imgix blurred image generation
        // See https://github.com/imgix/imgix-php/blob/6caef36aec0a80ba5c79d1cc2859c703f12d1494/src/Imgix/UrlHelper.php for implementation
        $encoded_source = urlencode($image_url[0]);
        $secure_token = get_option('funky_imgix_token');
        $to_hash = $secure_token . '/' . $encoded_source . $parameters;
        $hashed_url = md5($to_hash);

        $imgix_src = get_option('funky_imgix_account') . '/' . $encoded_source . $parameters . '&s=' . $hashed_url;

        return $imgix_src;

    }

    function the_imgix_image( $parameters = '', $image_id = false, $image_size = 'thumbnail' ){
        echo get_the_imgix_image( $parameters, $image_id, $image_size );
    }

    function funky_imgix_settings_page(){
        ?>

        <div class="wrap">
            <h2>Imgix Settings</h2>
            <a href="https://docs.imgix.com/apis/url">Imgix API Reference</a>
            <form action="options.php" method="post" id="wshop_settings">
                <?php settings_fields('imgix_settings'); ?>
                <table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row"><label for="funky_imgix_account">Imgix Domain:</label></th>
							<td>
								<input name="funky_imgix_account" type="text" title="" id="funky_imgix_account" value="<?php echo get_option('funky_imgix_account'); ?>">
							</td>
                        </tr>
                        <tr valign="top">
							<th scope="row"><label for="funky_imgix_token">Secure URL Token:</label></th>
							<td>
								<input name="funky_imgix_token" type="text" title="" id="funky_imgix_token" value="<?php echo get_option('funky_imgix_token'); ?>">
							</td>
                        </tr>
					</tbody>
                </table>
                <p class="submit">
					<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
				</p>
            </form>
        </div>

        <?php
    }

    function add_funky_imgix_options() {
        add_options_page('Imgix Settings', 'Imgix Settings', 'manage_options', 'imgix_settings', 'funky_imgix_settings_page');
    }

    function imgix_settings_init(){
        register_setting('imgix_settings', 'funky_imgix_token');
        register_setting('imgix_settings', 'funky_imgix_account');
    }
    add_action('admin_init', 'imgix_settings_init');

    add_action('admin_menu', 'add_funky_imgix_options');

?>
