<?php
if (!defined('ABSPATH')) exit;

class Pektsekye_OptionImages_Block_Product_Js {

  protected $_oiValue;
  protected $_oiOption;
    
  protected $_productOptions;    
  protected $_oiValues;  
  protected $_baseImageDir;    
  protected $_baseImageUrl; 
  

	public function __construct(){
    include_once(Pektsekye_OI()->getPluginPath() . 'Model/Option.php');
    $this->_oiOption = new Pektsekye_OptionImages_Model_Option();
    	
    include_once(Pektsekye_OI()->getPluginPath() . 'Model/Option/Value.php');
    $this->_oiValue = new Pektsekye_OptionImages_Model_Option_Value();			 		  			
	}


  public function getProductId(){
    global $product;
    return (int) $product->get_id();              
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
  
  
  public function initImagePath()
  { 
    $upload_dir = wp_upload_dir();
    $this->_baseImageDir = $upload_dir['basedir'] . '/';
    $this->_baseImageUrl = $upload_dir['baseurl'] . '/';    
  }
  
  
  public function getOptionsJson()
  { 
    $optionIds = array();
    $vIdsByOId = array();          
    $images = array();
            
    $oiValues = $this->getOiValues();
    
    foreach($this->getProductOptions() as $option){
    
      if (empty($option['values']))
        continue; 
           
      $oId = (int) $option['option_id'];
      
      $hasImages = false;
      
      $values = array();
      foreach($option['values'] as $value){
        $vId = (int) $value['value_id'];
        
        $image = isset($oiValues[$vId]) ? $oiValues[$vId]['image'] : '';        
        if (!empty($image)){
          $hasImages = true;                
          $images[$vId] = $this->getImageUrl($image);
          $vIdsByOId[$oId][] = $vId;                
        }       
      }
         
      if ($hasImages){     
        $optionIds[] = $oId;
      }       
    }    
    
    return json_encode(array('optionIds' => $optionIds, 'vIdsByOId' => $vIdsByOId, 'images' => $images));
  }  


  public function getImageUrl($image){  
    $imageUrl = '';
    
    $w = 100;
    $h = 100;
    
    $imagePath = $this->_baseImageDir . $image;
    if (file_exists($imagePath)){
      $fileName = basename($image);
      $thumbnailName = str_replace($fileName, $w . 'x' . $h . '_' . $fileName, $image);    
      $thumbnailPath = $this->_baseImageDir . $thumbnailName;
      if (!file_exists($thumbnailPath)){            
        $imageEd = wp_get_image_editor($imagePath);
        if (!is_wp_error($imageEd) ) {
          $imageEd->resize($w, $h, true);
          $imageEd->save($thumbnailPath);
        }
      }            
      $imageUrl = $thumbnailName;
    }
    
    return $imageUrl; 
  }  
 
 
  public function getBaseImageUrl(){  
     return $this->_baseImageUrl;
  }
   
    
  public function toHtml(){
    $this->initImagePath();  
    include_once(Pektsekye_OI()->getPluginPath() . 'view/frontend/templates/product/js.php');
  }


}
