<?php
if(!class_exists('flo_plugin_auto_update')){
    class flo_plugin_auto_update{

        /**
         * The plugin current version
         * @var string
         */
        public $current_version;

        /**
         * The plugin remote update path
         * @var string
         */
        public $update_path;

        /**
         * Plugin Slug (plugin_directory/plugin_file.php)
         * @var string
         */
        public $plugin_slug;

        /**
         * Plugin name (plugin_file)
         * @var string
         */
        public $slug;

        /**
         * Pruduct name
         * @var string
         */
        public $product_name;

        /**
         * Initialize a new instance of the WordPress Auto-Update class
         * @param string $current_version
         * @param string $update_path
         * @param string $plugin_slug
         */
        function __construct($current_version, $update_path, $plugin_slug, $product_name, $the_plugin)
        {
            // Set the class public variables
            $this->current_version = $current_version;
            $this->update_path = $update_path;
            $this->plugin_slug = $plugin_slug;
            list ($t1, $t2) = explode('/', $plugin_slug);
            $this->slug = str_replace('.php', '', $t2);

            $this->product_name = $product_name;

            $this->plugin = $the_plugin;

            // define the alternative API for updating checking
            add_filter('pre_set_site_transient_update_plugins', array(&$this, 'check_update'));

            // Define the alternative response for information checking
            add_filter('plugins_api', array($this, 'check_info'), 10, 3);
        }

        /**
         * Add our self-hosted autoupdate plugin to the filter transient
         *
         * @param $transient
         * @return object $ transient
         */
        public function check_update($transient)
        {
// echo '<pre>';
//             var_dump($transient);
//             echo '</pre>';
            if (empty($transient->checked)) {
                return $transient;
            }

            // Get the remote version
            $remote_version = $this->getRemote_version();

            // If a newer version is available, add the updateb
            if (version_compare($this->current_version, $remote_version, '<')) {
                $obj = new stdClass();
                $obj->slug = $this->slug;
                $obj->new_version = $remote_version;
                $obj->plugin = $this->plugin;
                $obj->url = $this->update_path;  /////////
                $obj->package = 'http://flothemes.com/recommended_plugins/plugins/'.$this->product_name.'/'.$remote_version.'.zip';
                $transient->response[$this->plugin_slug] = $obj;
            }
            // echo '<pre>';
            // var_dump($transient);
            // echo '</pre>';
            return $transient;
        }

        /**
         * Add our self-hosted description to the filter
         *
         * @param boolean $false
         * @param array $action
         * @param object $arg
         * @return bool|object
         */
        public function check_info($false, $action, $arg)
        {
            if (isset($arg->slug) && isset($this->slug) && $arg->slug === $this->slug) {
                $information = $this->getRemote_information();
                return $information;
            }
            return $false;
        }

        /**
         * Return the remote version
         * @return string $remote_version
         */
        public function getRemote_version()
        {

            if ( FALSE == $request = get_transient( $this->product_name.'_updates' ) ) {
                $request = wp_remote_post($this->update_path, array('body' => array('action' => 'version', 'product' => $this->product_name)));

                set_transient( $this->product_name.'_updates', $request, 12*60 * 60 ); // 12 hours transient
            }

            if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
                return $request['body'];
            }
            return false;
        }

        /**
         * Get information about the remote version
         * @return bool|object
         */
        public function getRemote_information()
        {
            $request = wp_remote_post($this->update_path, array('body' => array('action' => 'info', 'product' => $this->product_name)));

            if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {

                return maybe_unserialize($request['body']);
            }
            return false;
        }

        /**
         * Return the status of the plugin licensing
         * @return boolean $remote_license
         */
        public function getRemote_license()
        {
            $request = wp_remote_post($this->update_path, array('body' => array('action' => 'license', 'product' => $this->product_name)));
            if (!is_wp_error($request) || wp_remote_retrieve_response_code($request) === 200) {
                return $request['body'];
            }
            return false;
        }

    }
}

?>