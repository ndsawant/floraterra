<?php

/*
 * Class : GPCommon
 * Package: Genesis Pro
 * Description: Includes all genesis Pro helper functions
 * Author: Swapnil Ghone
 * Since: 23-November-2016
 * Last Modified: 23-November-2016
 */

class GPCommon {
    /*
     * Returns the meta value based on meta key passed
     * @meta_key (string) - the meta key whoes value to be fetched
     * @isserialize (bool) - return meta value is serialized or not
     */

    public function get_gp_meta($meta_key, $isserialize = false) {

        global $wpdb;
        $meta_value = $wpdb->get_row("SELECT gp_meta_value FROM " . GENESIS_PRO_TABLE_NAME . "  WHERE gp_meta_key = '$meta_key'");

        if (is_null($meta_value)) {
            return 0;
        } else {
            if ($isserialize) {
                return maybe_unserialize($meta_value->gp_meta_value);
            } else {
                return $meta_value->gp_meta_value;
            }
        }
    }

    public function update_gp_meta($meta_key, $meta_value) {
        global $wpdb;
        $isexits = $wpdb->get_row("SELECT gp_meta_value FROM " . GENESIS_PRO_TABLE_NAME . "  WHERE gp_meta_key = '$meta_key'");
        
        if (!is_null($isexits)) {
            return $wpdb->update(GENESIS_PRO_TABLE_NAME, array('gp_meta_value' => $meta_value), array('gp_meta_key' => $meta_key));
        } else {
            $data = array(
                'gp_meta_key' => $meta_key,
                'gp_meta_value' => $meta_value
            );
            return $wpdb->insert(GENESIS_PRO_TABLE_NAME, $data);
        }
    }
    
    public function delete_gp_meta($meta_key) {

        global $wpdb;
        $wpdb->delete( GENESIS_PRO_TABLE_NAME, array( 'gp_meta_key' => $meta_key) );
    }
    
     public function get_gp_json_meta($meta_key) {
         
        if (!file_exists(GENESIS_PRO_CONFIG)) {
            $this->gp_create_deafult_file_copy();
        }
        $config_file = file_get_contents(GENESIS_PRO_CONFIG);
        
        $content = (json_decode($config_file, true))?json_decode($config_file, true):array();
        
        if (isset($content[$meta_key])) {
            return $content[$meta_key];
        } else {
            return $this->get_gp_json_default_meta($meta_key);
        }
        return 0;
    }

    public function update_gp_json_meta($meta_key, $meta_value) {
       
        if (!file_exists(GENESIS_PRO_CONFIG)) {
            $this->gp_create_deafult_file_copy();
        }
        
        $config_file = file_get_contents(GENESIS_PRO_CONFIG);
        
        $content = (json_decode($config_file, true))?json_decode($config_file, true):array();

        $content[$meta_key] = $meta_value;

        return file_put_contents(GENESIS_PRO_CONFIG, json_encode($content,JSON_PRETTY_PRINT | JSON_FORCE_OBJECT));
    }

   

    /*
     * retrive the content from default file
     */

    function get_gp_json_default_meta($meta_key) {

        if (!file_exists(GENESIS_PRO_DEFAULT_CONFIG)) {
            $this->gp_create_deafult_file();
        }

        $config_file = file_get_contents(GENESIS_PRO_DEFAULT_CONFIG);
        $content = (json_decode($config_file, true))?json_decode($config_file, true):array();
        
        if (isset($content[$meta_key])) {
            return $content[$meta_key];
        } else {
            return 0;
        }
        return 0;
    }

    public function gp_create_deafult_file_copy() {

        if (file_exists(GENESIS_PRO_DEFAULT_CONFIG)) {
            $copy = copy(GENESIS_PRO_DEFAULT_CONFIG, GENESIS_PRO_CONFIG);
        } else {
            $this->gp_create_deafult_file();
        }

        $this->restore_default_settings();
    }

    /*
     * create default file
     */

    public function gp_create_deafult_file() {
        
        $default_config = array(
            'gp_config_data' => array(
                "plugin_version" => "1.0.1",
                "confi_version" => "1.0.0"
            ),
            'gp_general_settings' => $this->get_gp_meta('gp_general_settings', true),
            'gp_backup_settings' => $this->get_gp_meta('gp_backup_settings', true),
            'gp_secure_settings' => $this->get_gp_meta('gp_secure_settings', true),
            'gp_redirect_settings' => $this->get_gp_meta('gp_redirect_settings', true),
            'gp_social_share_settings' => $this->get_gp_meta('gp_social_share_settings', true),
            'gp_social_icon_settings' => $this->get_gp_meta('gp_social_icon_settings', true),
        );
        
        $dcf = fopen(GENESIS_PRO_DEFAULT_CONFIG, 'w' );
        fwrite($dcf,   json_encode($default_config,JSON_PRETTY_PRINT | JSON_FORCE_OBJECT));
        fclose($dcf);
       
    }
    
    /*
     * update database version if it does not matches default config version
     */
    public function gp_update_db_version(){
        
        if (!file_exists(GENESIS_PRO_DEFAULT_CONFIG)) {
            $this->gp_create_deafult_file();
        }

        $config_file = file_get_contents(GENESIS_PRO_DEFAULT_CONFIG);
        $content = (json_decode($config_file, true))?json_decode($config_file, true):array();
        
        if(!empty($content)){
            foreach ($content as $key=>$val){
                $this->update_gp_meta($key,maybe_serialize($val));
            }
        }
        $config_data = $this->get_gp_json_default_meta('gp_config_data');
        update_option('wsi_config_version',$config_data['config_version']);
    }
    /*
     * restore default settings
     */

    function restore_default_settings() {
       /*
        * Restore force ssl if it was enable
        */ 
        $cnf_file = ABSPATH. 'wp-config.php';
        $fr = fopen( $cnf_file , 'r' );
        $content = fread( $fr, filesize( $cnf_file ) );
        fclose($fr);
        $pos = strpos($content,"define('FORCE_SSL_ADMIN', true);");
        if($pos !== FALSE){ // if line is not found

            if(is_writable($cnf_file)){ // check if wp-config is writable or not
                $fw = fopen( $cnf_file, 'w' );
                $new_con  = str_replace("define('FORCE_SSL_ADMIN', true);", " ", $content);
                fwrite($fw,$new_con);
                fclose($fw);

                //modify database fields
                if(strpos(get_option('siteurl'), 'https')!==FALSE){
                    update_option('siteurl',str_replace('https','http',get_option('siteurl')));
                }

                if(strpos(get_option('home'), 'https')!==FALSE){
                    update_option('home',str_replace('https','http',get_option('home')));
                }
            } 
        }
        
        /*
         * disable comment and pingback
         */
        global $wpdb;
        update_option('default_pingback_flag','');
        update_option('default_ping_status','closed');
        update_option('default_comment_status','closed');
      $wpdb->query( $wpdb->prepare("UPDATE ".$wpdb->posts." SET comment_status = %s , ping_status = %s WHERE 1",'closed','closed'));
    }

    /*
     * check if current user is popadmin or not
     * return bool
     */

    public function is_super_admin() {
        global $user_ID;
        if (is_user_logged_in() && $user_ID === 1) {
            return true;
        } else {
            return false;
        }
    }

}
