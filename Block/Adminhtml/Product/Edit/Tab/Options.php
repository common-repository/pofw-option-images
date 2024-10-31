<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionImages_Block_Adminhtml_Product_Edit_Tab_Options {

  protected $_oiValue;
  protected $_oiOption;
    
  protected $_productOptions;    
  protected $_oiValues;  
  protected $_baseImageDir;    
  protected $_baseImageUrl;    
  
  
	public function __construct() {
    include_once(Pektsekye_OI()->getPluginPath() . 'Model/Option.php' );
    $this->_oiOption = new Pektsekye_OptionImages_Model_Option();
    	
    include_once(Pektsekye_OI()->getPluginPath() . 'Model/Option/Value.php' );
    $this->_oiValue = new Pektsekye_OptionImages_Model_Option_Value();
  }



  public function getProductId() {
    global $post;    
    return (int) $post->ID;  
  }
  
  
  public function getProductOptions() {  
    if (!isset($this->_productOptions)){
      $this->_productOptions = $this->_oiOption->getProductOptions($this->getProductId());
    }    
    return $this->_productOptions;              
  }


  public function getOiValues()
  {
    if (!isset($this->_oiValues)){
      $this->_oiValues = $this->_oiValue->getValues($this->getProductId());
    }    
    return $this->_oiValues;  
  }
  
  
  public function getOptions()
  { 
    $options = array();
   
    $oiValues = $this->getOiValues();
    
    foreach($this->getProductOptions() as $optionId => $option){
    
      if (empty($option['values']))
        continue;    
    
      $values = array();
      foreach($option['values'] as $value){
        $vId = $value['value_id'];
        
        $image = isset($oiValues[$vId]) ? $oiValues[$vId]['image'] : '';        
        $imageUrl = !empty($image) ? $this->getImageUrl($image) : '';        
                  
        $values[$vId] = array(
         'oi_value_id' => isset($oiValues[$vId]) ? $oiValues[$vId]['oi_value_id'] : -1,             
         'title' => $value['title'],
         'image' => $image,  
         'image_url' => $imageUrl               
        );
      }

      $options[] = array(
       'title' => $option['title'],
       'values' => $values
      );    
    }    
    return $options;
  }  


  public function getImageUrl($image){  
    $imageUrl = '';

    if (!isset($this->_baseImageDir)){
      $upload_dir = wp_upload_dir();
      $this->_baseImageDir = $upload_dir['basedir'] . '/';
      $this->_baseImageUrl = $upload_dir['baseurl'] . '/';
    }
    
    $imagePath = $this->_baseImageDir . $image;
    if (file_exists($imagePath)){
      $fileName = basename($image);
      $thumbnailName = str_replace($fileName, '27x27_' . $fileName, $image);    
      $thumbnailPath = $this->_baseImageDir . $thumbnailName;
      if (!file_exists($thumbnailPath)){            
        $imageEd = wp_get_image_editor($imagePath);
        if (!is_wp_error($imageEd) ) {
          $imageEd->resize(27, 27, true);
          $imageEd->save($thumbnailPath);
        }
      }            
      $imageUrl = $this->_baseImageUrl . $thumbnailName;
    }
    
    return $imageUrl; 
  }  
  
  
  public function getProductOptionsPluginEnabled(){
    return function_exists('Pektsekye_PO');  
  }
   
  
  public function toHtml() {
  
    echo '<div id="pofw_oi_product_data" class="panel woocommerce_options_panel hidden">';
    
    include_once(Pektsekye_OI()->getPluginPath() . 'view/adminhtml/templates/product/edit/tab/options.php');
    
    echo ' </div>';
  }


}
