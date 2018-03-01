<?php
/*
 * Class : GPSecureLoginHelper
 * Package: Genesis Tools
 * Description: Includes all supporting functions of login
 * Author: Swapnil Ghone
 * Since: 27-January-2016
 * Last Modified: 27-January-2016
 */

class GPSecureLoginHelper extends GPCommon {

    var $secure_settings, $secure_login_attempt, $secure_lockout_time, $secure_lockout_time_unit;
    var $helperobj;
    var $login_nonempty_credentials = false; // user and pwd nonempty

    function __construct() {

        $this->secure_settings = parent::get_gp_json_meta('gp_secure_settings', true);
        $this->secure_login_attempt = ($this->secure_settings['login_attempt']) ? $this->secure_settings['login_attempt'] : 5;
        $this->secure_lockout_time = ($this->secure_settings['time_lockout']) ? $this->secure_settings['time_lockout'] : 15;
        $this->secure_lockout_time_unit = ($this->secure_settings['time_lockout_unit']) ? $this->secure_settings['time_lockout_unit'] : 'min';


        /*
         * Filter and actions for user lockout
         */
        add_filter('wp_authenticate_user', array(&$this, 'gp_login_wp_authenticate_user'), 99999, 2);

        add_filter('login_errors', array(&$this, 'gp_limit_login_fixup_error_msg'));

        add_filter('wp_login_errors', array(&$this, 'gp_show_block_error_msg'));

        add_action('wp_login_failed', array(&$this, 'gp_limit_login_failed'));

        add_action('wp_authenticate', array(&$this, 'gp_limit_login_track_credentials'), 10, 2); // Keep track of if user or password are empty, to filter errors correctly.

        add_action('wp_login', array(&$this, 'reorder_login_attempt'));

        add_action('login_footer', array($this, 'disable_login_on_lockout'));
        /*
         * 
         */

        add_action('init', array(&$this, 'remove_user_lockout'));


        /*
         * Filters and actions to modify emails
         */

        add_filter('update_welcome_user_email', array($this, 'gp_welcome_mail_loginlink'), 10, 4);
        // modify the email change request mail content
        if ($this->secure_settings['secure_login']) {
            add_filter('new_user_email_content', array(&$this, 'gp_modify_confirm_mail_content'), 10, 2);
            add_filter('new_admin_email_content', array(&$this, 'gp_modify_admin_mail_content'), 10, 2);
        }

        /*
         * action to add captcha to login form
         */
        if ($this->secure_settings['login_form_captcha']) {
            add_action('login_form', array($this, 'gp_add_login_captcha'));
            //add_action('wp_enqueue_scripts',array($this,'gp_enqueue_recaptcha'));
        }
    }

    /* function gp_enqueue_recaptcha()
      {
      ?>
      <script src="//www.google.com/recaptcha/api.js" async defer></script>
      <?php
      }
     */

    function gp_get_user_ip() {

        if (isset($_SERVER['REMOTE_ADDR'])) {

            return $_SERVER['REMOTE_ADDR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {

            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {

            return '';
        }
    }

    function gp_is_user_lockout() {

        $login_failed_ips_record = parent::get_gp_meta('login_failed_ips_record', TRUE);
        $user_ip = $this->gp_get_user_ip();

        if (is_array($login_failed_ips_record) && isset($login_failed_ips_record[$user_ip]['lockout_time'])) {

            $diff = ( time() - $login_failed_ips_record[$user_ip]['lockout_time'] );
            if (($this->secure_lockout_time_unit == 'min') && ( ($diff / 60) < $this->secure_lockout_time)) {
                return true;
            } elseif ($this->secure_lockout_time_unit == 'hour' && ( ($diff / (60 * 60))) < $this->secure_lockout_time) {
                return true;
            }
        }
        return false;
    }

    function gp_is_limit_login_ok() {

        $login_failed_ips_record = parent::get_gp_meta('login_failed_ips_record', TRUE);
        $user_ip = $this->gp_get_user_ip();
        if (is_array($login_failed_ips_record)) {
            if (isset($login_failed_ips_record[$user_ip])) {
                if ($login_failed_ips_record[$user_ip]['attempt'] >= ( $this->secure_login_attemp )) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        } else {
            return true;
        }
        return (is_array($login_failed_ips_record) && isset($login_failed_ips_record[$user_ip]) && $login_failed_ips_record[$user_ip]['attempt'] < ( $this->secure_login_attemp ));
    }

    function gp_is_temp_IPLocked($ip) {

        $login_failed_ips_record = parent::get_gp_meta('login_failed_ips_record', TRUE);

        if (is_array($login_failed_ips_record) && isset($login_failed_ips_record[$ip])) {

            $timestamp = $login_failed_ips_record[$ip]['lockout_time'];

            if (!$timestamp)
                return false;
            $diff = ( time() - $timestamp );

            if (($this->secure_lockout_time_unit == 'min') && ( ($diff / 60) >= $this->secure_lockout_time)) {
                return true;
            } elseif ($this->secure_lockout_time_unit == 'hour' && ( ($diff / (60 * 60))) >= $this->secure_lockout_time) {
                return true;
            }
        }
        return false;
    }

    function gp_get_lockout_time_remaining() {

        $login_failed_ips_record = parent::get_gp_meta('login_failed_ips_record', TRUE);
        $user_ip = $this->gp_get_user_ip();
        $time_diff = 0;
        if (is_array($login_failed_ips_record) && isset($login_failed_ips_record[$user_ip])) {
            if (isset($login_failed_ips_record[$user_ip]['lockout_time'])) {

                $diff = ( time() - $login_failed_ips_record[$user_ip]['lockout_time'] );

                if ($this->secure_lockout_time_unit == 'min') {

                    $time_diff = $this->secure_lockout_time - ($diff / 60);
                } elseif ($this->secure_lockout_time_unit == 'hour') {
                    $time_in_min = $this->secure_lockout_time * 60;
                    $time_diff = $time_in_min - ($diff / 60);
                }
            }
        }

        return round(abs($time_diff));
    }

    // Lockout notification mail
    function gp_lockout_notify($username) {

        $redirect_to = urlencode(admin_url('admin.php?page=genesis-pro&block_user_hash=' . base64_encode($_SERVER['REMOTE_ADDR']) . ''));

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

        $subject = 'User locked out from signing in';
        $message = '<b>Genesis Pro Security Alter!!</b><br/>';
        $message .= 'Too many failed login attemps on site <a href="' . site_url() . '">' . site_url() . '</a> From IP: <b>' . $this->gp_get_user_ip() . '</b><br/>';
        $message .= 'Last user name tried: ' . $username . '<br/>';
        $message .= 'The IP was blocked for: ' . $this->secure_lockout_time . ' ' . $this->secure_lockout_time_unit . '<br/>';
        $message .= '<a href="' . site_url('/genesis-login?redirect_to=' . $redirect_to) . '">Click here</a>, if you want to unlock this user <br/>';

        $admin_email = is_multisite() ? get_site_option('admin_email') : get_option('admin_email');
        wp_mail($admin_email, $subject, $message, $headers);
    }

    // Login Attemp TRYING

    public function gp_login_wp_authenticate_user($user, $password) {

        $login_failed_ips_record = parent::get_gp_meta('login_failed_ips_record', TRUE);
        $user_ip = $this->gp_get_user_ip();

        // case when user IP was blocked but his block time has been passedout
        if ($this->gp_is_temp_IPLocked($user_ip)) {
            if (is_array($login_failed_ips_record) && array_key_exists($user_ip, $login_failed_ips_record)) {
                unset($login_failed_ips_record[$user_ip]);
                parent::update_gp_meta('login_failed_ips_record', maybe_serialize($login_failed_ips_record));
            }
        }

        /*
         * when user is lockout
         */
        if ($this->gp_is_user_lockout()) {

            $user = new WP_Error('gp_user_lockout', __("'Please try after '.$this->gp_get_lockout_time_remaining().' mins'"));
        } else { // if user is not lockout then check for captcha
            if ($this->secure_settings['login_form_captcha'] == 1) {
                //Verify recaptcha response server side
                if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
                    $url = 'https://www.google.com/recaptcha/api/siteverify';
                    $data = array(
                        'secret' => $this->secure_settings['reCAPTCHA_Secret_Key'],
                        'response' => $_POST["g-recaptcha-response"],
                        'remoteip' => $_SERVER['REMOTE_ADDR']
                    );
                    $verify = curl_init();
                    curl_setopt($verify, CURLOPT_URL, $url);
                    curl_setopt($verify, CURLOPT_POST, true);
                    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
                    curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($verify);
                    $check = json_decode($response);
                    if (!($check->success)) {
                        $user = new WP_Error('gp_empty_captcha', 'Robot verification failed, please try again.');
                    }
                } else {
                    $user = new WP_Error('gp_empty_captcha', 'Please click on the reCAPTCHA box');
                }
            }
        }
        return $user;
    }

    //reset user IP block entries when user is sucessfully login
    public function reorder_login_attempt() {

        $login_failed_ips_record = parent::get_gp_meta('login_failed_ips_record', TRUE);

        if (is_array($login_failed_ips_record) && array_key_exists($this->gp_get_user_ip(), $login_failed_ips_record)) {

            unset($login_failed_ips_record[$this->gp_get_user_ip()]);
            parent::update_gp_meta('login_failed_ips_record', maybe_serialize($login_failed_ips_record));
        }
    }

    public function gp_limit_login_failed($username) {

        $login_failed_ips_record = parent::get_gp_meta('login_failed_ips_record', TRUE);
        $login_failed_ips_record = ( $login_failed_ips_record ) ? $login_failed_ips_record : array();
        $user_ip = $this->gp_get_user_ip();

        if (is_array($login_failed_ips_record) && array_key_exists($user_ip, $login_failed_ips_record)) {

            if ($login_failed_ips_record[$user_ip]['attempt'] < ($this->secure_login_attempt - 1)) {

                $login_failed_ips_record[$user_ip]['attempt'] = $login_failed_ips_record[$user_ip]['attempt'] + 1;
            } elseif ($login_failed_ips_record[$user_ip]['attempt'] == ($this->secure_login_attempt - 1)) { // user has reached the max attempt than block user
                $login_failed_ips_record[$user_ip]['attempt'] = $login_failed_ips_record[$user_ip]['attempt'] + 1;
                $login_failed_ips_record[$user_ip]['lockout_time'] = time();
                if ($this->secure_settings['admin_notify_lockout']) {
                    $this->gp_lockout_notify($username);
                }
            } elseif ($login_failed_ips_record[$user_ip]['attempt'] > $this->secure_login_attempt - 1) {

                // IF user is already blocked but his lockout time has passed
                if ($this->gp_is_temp_IPLocked($user_ip)) {
                    unset($login_failed_ips_record[$user_ip]);
                    parent::update_gp_meta('login_failed_ips_record', maybe_serialize($login_failed_ips_record));
                }
                return;
            }
        } else {
            $login_failed_ips_record[$user_ip]['attempt'] = 1;
        }

        parent::update_gp_meta('login_failed_ips_record', maybe_serialize($login_failed_ips_record));
    }

    public function gp_limit_login_fixup_error_msg($error) {
        //echo '<pre>';print_r($error); die;
        $login_failed_ips_record = parent::get_gp_meta('login_failed_ips_record', TRUE);
        $user_ip = $this->gp_get_user_ip();

        if (is_array($login_failed_ips_record) && array_key_exists($user_ip, $login_failed_ips_record)) {

            if ($login_failed_ips_record[$user_ip]['attempt'] >= $this->secure_login_attempt) {
                $error = __('Too many failed login attempts.<br/>', 'limit-login-attempts');
                $error .= __('Please try after ' . $this->gp_get_lockout_time_remaining() . ' mins', 'limit-login-attempts');
                return $error;
            } else {

                if ($this->secure_settings['login_form_captcha']) {

                    if (strpos($error, 'Invalid CAPTCHA') !== false) {
                        $error = __('Invalid CAPTCHA!!<br/>' . ( $this->secure_login_attempt - $login_failed_ips_record[$user_ip]['attempt'] ) . ' Attempts are Remaining', 'limit-login-attempts');
                        return $error;
                    }
                    if (strpos($error, 'Please click on the reCAPTCHA box') !== false) {
                        $error = __('Please click on the reCAPTCHA box!!<br/>' . ( $this->secure_login_attempt - $login_failed_ips_record[$user_ip]['attempt'] ) . ' Attempts are Remaining', 'limit-login-attempts');
                        return $error;
                    }
                }
                $error = __('Wrong Username or password<br/>' . ( $this->secure_login_attempt - $login_failed_ips_record[$user_ip]['attempt'] ) . ' Attempts are Remaining', 'limit-login-attempts');
                return $error;
            }
        } else {
            if ($this->secure_settings['login_form_captcha']) {

                if (strpos($error, 'Invalid CAPTCHA') !== false) {
                    $error = __('Invalid CAPTCHA!!<br/>' . ( $this->secure_login_attempt - $login_failed_ips_record[$user_ip]['attempt'] ) . ' Attempts are Remaining', 'limit-login-attempts');
                    return $error;
                }
            }
            $error = __('Incorrect username or password.', 'limit-login-attempts') . "<br />\n";

            return $error;
        }

        return $error;
    }

    function gp_show_block_error_msg($error) {

        if ($this->gp_is_user_lockout()) {
            $error = new WP_Error('gp_user_lockout', __("'Please try after '.$this->gp_get_lockout_time_remaining().' mins'"));
        }
        return $error;
    }

    public function gp_limit_login_track_credentials($user, $password) {

        $this->login_nonempty_credentials = (!empty($user) && !empty($password));
        return $this->login_nonempty_credentials;
    }

    // Function for chnging default login url
    function gp_welcome_mail_loginlink($welcome_email, $user_id, $password, $meta) {

        $login_page = parent::get_gp_meta('gp_login_theme_page_id');

        if ($login_page && $this->secure_settings['secure_login']) {
            $post_data = get_post($login_page);
            $login_url == get_site_url() . '/' . $post_data->post_name . '/';
            $welcome_email = str_replace('LOGINLINK', $login_url, $welcome_email);
        }
        return $welcome_email;
    }

    // modify user email change request mail content
    function gp_modify_confirm_mail_content($email_text, $new_admin_email) {

        $redirect_to = urlencode(admin_url('profile.php?newuseremail=' . $new_admin_email['hash']));
        $email_text = __('Dear user,

You recently requested to have the email address on your account changed.
If this is correct, please click on the following link to change it:
' . site_url('?redirect_to=' . $redirect_to . '#genesis-login') . '

You can safely ignore and delete this email if you do not want to
take this action.

This email has been sent to ###EMAIL###

Regards,
All at ###SITENAME###
###SITEURL###');

        return $email_text;
    }

    // modify admin email change request mail content
    function gp_modify_admin_mail_content($email_text, $new_admin_email) {

        $redirect_to = urlencode(admin_url('options.php?adminhash=' . $new_admin_email['hash']));
        $email_text = __('Dear user,

You recently requested to have the administration email address on
your site changed.
If this is correct, please click on the following link to change it:
' . site_url('?redirect_to=' . $redirect_to . '#genesis-login') . '

You can safely ignore and delete this email if you do not want to
take this action.

This email has been sent to ###EMAIL###

Regards,
All at ###SITENAME###
###SITEURL###');

        return $email_text;
    }

    /*
     * Add captcha to login form
     */

    function gp_add_login_captcha() {
        ?>
        <script src="//www.google.com/recaptcha/api.js" async defer></script>
        <?php
        if ($this->secure_settings['login_form_captcha'] != 1)
            return false;
        ?>
        <div class="uk-form-row" id="gp-secure-captcha1">
            <div class="g-recaptcha" data-sitekey="<?php echo $this->secure_settings['reCAPTCHA_Site_Key']; ?>"></div>
        </div>
        <?php
    }

    /*
     * remove user block when admin clicks the unblock link
     */

    function remove_user_lockout() {
        if (isset($_GET['block_user_hash'])) {
            $user_ip = base64_decode($_GET['block_user_hash']);

            $login_failed_ips_record = parent::get_gp_meta('login_failed_ips_record', TRUE);

            if (is_array($login_failed_ips_record) && array_key_exists($user_ip, $login_failed_ips_record)) {
                unset($login_failed_ips_record[$user_ip]);
                parent::update_gp_meta('login_failed_ips_record', maybe_serialize($login_failed_ips_record));
            }
            wp_redirect(admin_url('admin.php?page=genesis-pro&block_msg=1'));
        }
    }

    function disable_login_on_lockout() {
        if ($this->gp_is_user_lockout()) {
            ?>
            <script>
                document.getElementById("wp-submit").disabled = true
            </script>
        <?php
        }
    }

}
