<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionImages_Model_Option_Value {


  public function __construct() {
    global $wpdb;
    
    $this->_wpdb = $wpdb;   
    $this->_mainTable = "{$wpdb->base_prefix}pofw_optionimages_option_value";                        
  }    


  public function getValues($productId)
  {            
    $slots = array();
   
    $productId = (int) $productId;     
    $select = "SELECT oi_value_id, value_id, image FROM {$this->_mainTable} WHERE product_id={$productId}";
    $rows = $this->_wpdb->get_results($select, ARRAY_A);      
    
    foreach($rows as $r){
      $slots[$r['value_id']] = array('oi_value_id' => $r['oi_value_id'], 'image' => $r['image']); 
    }
    
    return $slots;                    
  }


  public function getAllImages()
  {            
    $images = array();
       
    $select = "SELECT value_id, image FROM {$this->_mainTable} WHERE image != ''";
    $rows = $this->_wpdb->get_results($select, ARRAY_A);      
    
    foreach($rows as $r){
      $images[$r['value_id']] = $r['image']; 
    }
    
    return $images;                    
  }    


  public function saveValues($productId, $values)
  { 
    $productId = (int) $productId;

    foreach ($values as $r){
      $oiValueId = isset($r['oi_value_id']) ? (int) $r['oi_value_id'] : 0;    
      $valueId = (int) $r['value_id'];    
      $image = esc_sql($r['image']);            

      if ($oiValueId > 0){             
        $this->_wpdb->query("UPDATE {$this->_mainTable} SET image = '{$image}' WHERE oi_value_id = {$oiValueId}");                        
      } else {
        $this->_wpdb->query("INSERT INTO {$this->_mainTable} SET product_id = {$productId}, value_id = {$valueId}, image = '{$image}'");           
      }    
    }                     
  }  
  

  public function deleteValues($productId)
  {  
    $productId = (int) $productId;
    $this->_wpdb->query("DELETE FROM {$this->_mainTable} WHERE product_id = {$productId}");  
  }      

}
