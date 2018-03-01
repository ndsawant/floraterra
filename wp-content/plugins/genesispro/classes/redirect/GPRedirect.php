<?php
/*
 * Class : GPRedirect.php
 * Package: Genesis Tools
 * Description: Includes all the redirect features
 * Author: Swapnil Ghone
 * Since: 5-Feburary-2016
 * Last Modified: 5-Feburary-2016
 */

class GPRedirect extends GPCommon {

    var $gp_redirect_settings, $gp_404_page, $gp_redirect_data;

    function __construct() {

        $this->gp_redirect_settings = parent::get_gp_json_meta('gp_redirect_settings', true);
        $this->gp_redirect_data = parent::get_gp_meta('gp_redirect_data', true);

        add_action('wp_ajax_gp_redirect_options', array($this, 'gp_redirect_options_callback'));

        add_action('template_redirect', array($this, 'gp_handel_redirect'));

    }

    /*
     * returns the url type as per the admin selection
     */

    function gp_redirect_options_callback() {

        if (isset($_POST['command'])) {
            switch ($_POST['command']) {
                case 'get_url_type':
                    if (isset($_POST['url_type'])) {
                        switch ($_POST['url_type']) {
                            case 'page':
                                $page_args = array(
                                    'echo' => 1,
                                    'name' => 'destination_url'
                                );
                                wp_dropdown_pages($page_args);
                                break;
                            case 'post':
                                $args = array('numberposts' => -1);
                                $posts = get_posts($args);
                                ?>
                                <select name="destination_url" id="destination_url">
                                    <?php foreach ($posts as $post) : ?>
                                        <option value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <?php
                                break;
                            case 'custom':
                                ?>
                                <input type="text" name="destination_url" id="destination_url" size="30" />
                                <?php
                                break;
                        }
                    }
                    break;
                case 'save_redirect':
                    $gp_redirect_data = (parent::get_gp_meta('gp_redirect_data', true)) ? parent::get_gp_meta('gp_redirect_data', true) : array();
                    $source_url = preg_replace('/\p{C}+/u', "", $_POST['gp_source_url']);

                    if (!strrpos($source_url, "?")) {
                        if (substr($source_url, -1) != '/')
                            $source_url = $source_url . "/";
                    }
                    $_POST['destination_url'] = !empty($_POST['destination_url']) ? $_POST['destination_url'] : '/';
                    // remove invisible character from url
                    $destination_url = preg_replace('/\p{C}+/u', "", $_POST['destination_url']);
                    $gp_redirect_data[$source_url]['redirect_to'] = $destination_url;
                    $gp_redirect_data[$source_url]['url_type'] = $_POST['url_type'];
                    $redirect_to = ( $_POST['url_type'] == 'custom') ? $destination_url : get_permalink($destination_url);

                    $return_redirect_data['destination_url'] = $redirect_to;
                    $return_redirect_data['alt'] = $source_url;
                    if (is_array($this->gp_redirect_settings) && $this->gp_redirect_settings['remove_slash']) {
                        $return_redirect_data['source_url'] = site_url('/') . rtrim($source_url, '/');
                    } else {
                        $return_redirect_data['source_url'] = site_url('/') . $source_url;
                    }

                    $return_redirect_data['url_type'] = $_POST['url_type'];
                    parent::update_gp_meta('gp_redirect_data', maybe_serialize($gp_redirect_data));
                    header('Content-type: application/json');
                    echo json_encode($return_redirect_data);
                    break;
                case 'delete':
                    $gp_redirect_data = (parent::get_gp_meta('gp_redirect_data', true));
                    unset($gp_redirect_data[$_POST['target_url']]);
                    parent::update_gp_meta('gp_redirect_data', maybe_serialize($gp_redirect_data));
                    break;
                case 'delete_selected':
                    $gp_redirect_data = (parent::get_gp_meta('gp_redirect_data', true));
                    foreach ($_POST['target_url'] as $url) {
                        unset($gp_redirect_data[$url]);
                    }
                    parent::update_gp_meta('gp_redirect_data', maybe_serialize($gp_redirect_data));
                    break;
            }
        }
        exit();
    }

    function gp_handel_redirect() {
        
        /*
         * handel 301 redirect
         */
        if (is_array($this->gp_redirect_data) && !empty($this->gp_redirect_data)) {
            $new_gp_redirect_data = array();
            $pageURL = ( @$_SERVER["HTTPS"] == "on" ) ? "https://" : "http://";
            $pageURL .=( $_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443" ) ? $_SERVER["HTTP_HOST"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"] : $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];

            foreach ($this->gp_redirect_data as $key => $value) {
                if ($value['url_type'] != 'custom') {
                    $value['redirect_to'] = get_permalink($value['redirect_to']);
                    $new_gp_redirect_data[site_url('/') . $key] = $value;
                } else {
                    $new_gp_redirect_data[site_url('/') . $key] = $value;
                }
            }

            foreach ($new_gp_redirect_data as $k => $v) {

                $temp = $k;
                $k = preg_replace('/\p{C}+/u', "", $k);

                if ($k == urldecode($pageURL) || $k . '/' == urldecode($pageURL) || $k == urldecode($pageURL) . '/' || $k . '/' == urldecode($pageURL) . '/') {
                    if ($new_gp_redirect_data[$temp]['redirect_to'])
                        wp_redirect($new_gp_redirect_data[$temp]['redirect_to'], 301);
                    exit();
                }
            }
        }
        
        /*
         * handel 404 redirect
         */
        if(is_404()){
            
            if ($this->gp_redirect_settings && array_key_exists('not_found_page', $this->gp_redirect_settings)) {
                if ($this->gp_redirect_settings['not_found_page'] == '/') {
                    $_404 = site_url();
                } else {
                    $_404 = get_permalink($this->gp_redirect_settings['not_found_page']);
                }
            } else {
                $_404 = site_url();
            }
            wp_redirect($_404,302);
            exit();
        }
        
    }

}
