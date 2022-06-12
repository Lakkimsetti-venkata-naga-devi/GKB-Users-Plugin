<?php
 
/*
Plugin Name: Demo Plugin
Plugin URI: http://localhost/wordpress
Description: Plugin to create users and manage users. If you have CSV file, you can easily import the users by clicking import option. You can see the list of users
Version: 1.0.0
Author: Devi
Author URI: http://localhost/wordpress
License: GPLv2 or later
Text Domain: demo_plugin
 
*/

if(! defined('ABSPATH')){
    exit;
}

class demo_plugin{
    function __construct(){
        add_action('admin_menu',array($this,'demo_plugin_menus'),99); 
        add_action('wp_ajax_add_users', array($this,'add_users'));
        add_action('wp_ajax_nopriv_add_users', array($this,'add_users'));
        // add_action( 'admin_post_import_users', array($this,'import_users') );
        // add_action( 'admin_post_nopriv_import_users', array($this,'import_users') );
        //add_action('wp_footer', array($this,'import_users'));

        add_action('init', array($this,'import_users'));
    }
    function activate(){
        $this->demo_plugin_menus();
        $this->register();
        flush_rewrite_rules();
    }
    function deactivate(){

    }
    function register(){
        add_action('admin_enqueue_scripts',array($this,'enqueue_scripts_styles'));
    }
    function demo_plugin_menus() { 
        add_menu_page( 'Demo-Plugin','Demo-Plugin','manage_options','demo_plugin',array($this,'demo_plugin_settings'),'dashicons-buddicons-buddypress-logo',18);
        add_submenu_page( 'demo_plugin','Create user','Create user','manage_options','demo_create',array($this,'demo_create_settings'));
        add_submenu_page( 'demo_plugin','Import users','Import users','manage_options','demo_import',array($this,'demo_import_settings'));
        add_submenu_page( 'demo_plugin','List users','List users','manage_options','demo_list',array($this,'demo_list_settings'));
        add_action('admin_menu',array($this,'demo_plugin_menus'),99);
    } 

    function demo_plugin_settings(){
        if(! defined('MY_PLUGIN_PATH')){
            define( 'MY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
        }
        include MY_PLUGIN_PATH . 'includes/main.php';
    }
    function demo_create_settings(){
        if(! defined('MY_PLUGIN_PATH')){
            define( 'MY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
        }
        include MY_PLUGIN_PATH . 'includes/create.php';
    }
    function demo_import_settings(){
        if(! defined('MY_PLUGIN_PATH')){
            define( 'MY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
        }
        include MY_PLUGIN_PATH . 'includes/import.php';
        
    }
    function demo_list_settings(){
        if(! defined('MY_PLUGIN_PATH')){
            define( 'MY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
        }
        include MY_PLUGIN_PATH . 'includes/list.php';
    }

    function enqueue_scripts_styles(){
        //enqueue styles and scripts for plugin
        wp_enqueue_style('my_plugin_styles',plugins_url('/assets/style.css',__FILE__));
        wp_enqueue_script("my_plugin_scripts",plugins_url('/assets/main.js',__FILE__));
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'datatables', '//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js', array( 'jquery' ) );
        wp_enqueue_style( 'datatables-style', '//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css' );
        
        wp_enqueue_media();
        $theme_array = array( 'ajax_url' => admin_url( 'admin-ajax.php' ));
        wp_localize_script( 'my_plugin_scripts', 'Theme', $theme_array );
    }
    function admin_tables(){
        
        global $wpdb;

        $tblname = 'gkb_users';
        $wp_track_table = $wpdb->prefix . "$tblname";

        $sql = "CREATE TABLE IF NOT EXISTS $wp_track_table ( ";
        $sql .= "  `id`  int(11)   NOT NULL auto_increment,";
        $sql .= "  `f_name` varchar(20),";
        $sql .= "  `l_name` varchar(20),";
        $sql .= "  `email` varchar(20),";
        $sql .= "  `hobbies` varchar(60),";
        $sql .= "  `gender` varchar(20),";
        $sql .= "  `prop_pic` varchar(100),";
        $sql .= "  PRIMARY KEY `order_id` (`id`) "; 
        $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }
    function add_users(){
        $result = array();
        $result["status"] = 0;
        $f_name = $_POST['f_name'];
        $l_name = $_POST["l_name"];
        $email = $_POST["email"];
        $hobbies = $_POST['hobbies'];
        $push_hobby = implode(" ",$hobbies);
        $gender = $_POST['gender'];
        $prop_pic = $_POST['prof_pic'];
        if($f_name ==''){
            echo "<p>Please Enter First Name</p>"; exit;
        }else if($l_name == ''){
            echo "<p>Please Enter Last Name</p>"; exit;
        }else if($email == ''){
            echo "<p>Please Enter Email</p>"; exit;
        }else if(! preg_match('/^[A-Z0-9][A-Z0-9._%+-]{0,63}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i',$email)){
            echo "<p>Please Enter Valid Email</p>"; exit;
        }else if($hobbies == ''){
            echo "<p>Please Select Atlease one Hobby</p>";exit;
        }else if(!$gender){
            echo "<p>Please Select Gender</p>";exit;
        }else{
            global $wpdb;
            $post_id = $wpdb->get_results("SELECT id FROM wp_gkb_users WHERE email = '" . $email . "'");
            $rowcount = $post_id->num_rows;
            if ($rowcount > 0){
                $status = 1;
                $error = "Email Already taken";
            }else{
                $status = $wpdb->insert($wpdb->prefix."gkb_users",array("f_name"=>$f_name,"l_name"=>$l_name,"email"=>$email,"hobbies"=>$push_hobby,"gender"=>$gender,"prop_pic"=>$prop_pic));
                $error = "User Added Successfully";
            }
        }
        if($status){
            $result['status'] = 1;
            $result['error'] = $error;
            
        }else{
            $result["status"] = 0;
            $result["error"] = "Something went wrong.. try again after some time";
        }
        echo json_encode($result);    
        die();
    }
    function import_users(){
        global $wpdb;
        $tablename = $wpdb->prefix."gkb_users";
        if (isset($_POST['butimport'])){

            $extension = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);
            if (!empty($_FILES['import_file']['name']) && $extension == 'csv'){
                
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['import_file']['tmp_name'], 'r');
 
            // Skip the first line
            fgetcsv($csvFile);
 
            // Parse data from CSV file line by line
             // Parse data from CSV file line by line
            while (($getData = fgetcsv($csvFile, 10000, ",")) !== FALSE)
            {
                // Get row data
                $f_name = $getData[0];
                $l_name = $getData[1];
                $email =$getData[2];
                $hobbies = $getData[3];
                $gender = $getData[4];
                $prop_pic =$getData[5];
                
                // If user already exists in the database with the same email
                $post_id = $wpdb->get_results("SELECT count(*) as count  FROM {$tablename} WHERE email = '" . $email . "'",OBJECT);
                if ($post_id[0]->count == 0){
                    $status = $wpdb->insert($wpdb->prefix."gkb_users",array("f_name"=>$f_name,"l_name"=>$l_name,"email"=>$email,"hobbies"=>$hobbies,"gender"=>$gender,"prop_pic"=>$prop_pic));
                    $error = "User Added Successfully";
                    if($wpdb->insert_id > 0){
                        $totalInserted++;
                    }
                }
            }
                    
            }
            else
            {
                echo "Please select valid file";
            }
            }
    }
}
if(class_exists('demo_plugin')){
    $demo_plugin = new demo_plugin();
    $demo_plugin->register();
    $demo_plugin->admin_tables();
}
//activation
register_activation_hook(__FILE__,array($demo_plugin, 'activate')); 
//deacctivation
register_deactivation_hook(__FILE__,array($demo_plugin, 'deactivate')); 