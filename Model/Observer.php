<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionImages_Model_Observer {  

  protected $_oiValue;        
                
      
  public function __construct(){           
    include_once(Pektsekye_OI()->getPluginPath() . 'Model/Option/Value.php' );
    $this->_oiValue = new Pektsekye_OptionImages_Model_Option_Value();
      
    add_action('woocommerce_process_product_meta', array($this, 'save_product_options'));    
    add_filter('wp_prepare_attachment_for_js', array($this, 'add_image_path'), 10, 3);   
    add_filter('pofw_csv_export_data_option_value_rows', array($this, 'add_images_to_csv_export_option_value_rows'), 10, 1);    
    add_action("pofw_csv_import_product_options_saved", array($this, 'save_product_options_from_csv'), 10, 2);           
		add_action('delete_post', array($this, 'delete_post'));    	          		
  }	  


 
  public function save_product_options($post_id){
    if (isset($_POST['pofw_oi_changed']) && $_POST['pofw_oi_changed'] == 1){
      $productId = (int) $post_id;  

      $values = array();
      
      if (isset($_POST['pofw_oi_values'])){                          
        foreach ($_POST['pofw_oi_values'] as $valueId => $v){      
          $values[] = array(
            'value_id' => (int) $valueId,          
            'oi_value_id' => (int) $v['oi_value_id'],                       
            'image' => sanitize_text_field(stripslashes($v['image']))
          );                         
        }        
      }      

      $this->_oiValue->saveValues($productId, $values);                     
    }
  }


  public function add_image_path($response, $attachment, $meta){
    $response['pofwOiImagePath'] = $meta['file'];
    return $response;                 
  }


  public function add_images_to_csv_export_option_value_rows($rows){  
    $images = $this->_oiValue->getAllImages();

    foreach ($rows as $k => $row){ 
      $valueId = $row['value_id']; 
      $rows[$k]['image'] = isset($images[$valueId]) ? $images[$valueId] : '';                           
    }
    
    return $rows;    
  }
  
  
  public function save_product_options_from_csv($productId, $optionsData){  
    $values = array();          
    foreach($optionsData as $o){
      if (isset($o['values'])){           
        foreach($o['values'] as $v){
          $values[] = array(
            'value_id' => (int) $v['value_id'],         
            'oi_value_id' => -1,                       
            'image' => isset($v['image']) ? $v['image'] : ''
          );
        }       
      }           
    }    
    $this->_oiValue->saveValues($productId, $values);                
  }      
      
	
	public function delete_post($id){
		if (!current_user_can('delete_posts') || !$id || get_post_type($id) != 'product'){
			return;
		}
		 		
    $this->_oiValue->deleteValues($id);             
	}		
		
}
