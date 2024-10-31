<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionImages_Controller_Product {


	public function __construct() {
    add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts')); 	
    add_action('woocommerce_before_add_to_cart_button', array($this, 'display_options_on_product_page'), 12);	//after product options							  				
	}


  public function enqueue_frontend_scripts(){
    wp_enqueue_script('pofw_oi_product_view', Pektsekye_OI()->getPluginUrl() . 'view/frontend/web/main.js', array('jquery', 'jquery-ui-widget', 'wp-util'));      
    wp_enqueue_style('pofw_oi_product_view', Pektsekye_OI()->getPluginUrl() . 'view/frontend/web/main.css');		  		  			
  }
  
  
	public function display_options_on_product_page() { 
    include_once(Pektsekye_OI()->getPluginPath() . 'Block/Product/Js.php');
    $block = new Pektsekye_OptionImages_Block_Product_Js();
    $block->toHtml();
  }
  

}
