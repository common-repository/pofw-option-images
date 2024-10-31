<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionImages_Model_Option {
           
  protected $_wpdb;          
   
      
  public function __construct(){
    global $wpdb;    
    $this->_wpdb = $wpdb;            		
  }	


  public function getProductOptions($productId)
  {
    $productOptions = array();
    if (function_exists('Pektsekye_PO')){
      include_once(Pektsekye_PO()->getPluginPath() . 'Model/Option.php' );
      $optionModel = new Pektsekye_ProductOptions_Model_Option();
      $productOptions = $optionModel->getProductOptions($productId);
    }
    return $productOptions;  
  }


}
