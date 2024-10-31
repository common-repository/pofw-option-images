<?php
if (!defined('ABSPATH')) exit;
?>
<div class="pofw-oi-container">
<?php if (!$this->getProductOptionsPluginEnabled()): ?>
  <div class="pofw_optionimages-create-ms"><?php echo __('Please, install and enable the <a href="https://wordpress.org/plugins/product-options-for-woocommerce/" target="_blank">Product Options</a> plugin.', 'pofw-option-images'); ?></div>
<?php else: ?>
  <div id="pofw_oi_options">
    <?php foreach ($this->getOptions() as $option): ?>
      <div>
        <div class="pofw-oi-option-title">
          <span><?php echo htmlspecialchars($option['title']); ?></span>
        </div>  
        <div class="pofw-oi-values">
          <?php foreach ($option['values'] as $valueId => $value): ?>
            <div class="pofw-oi-value">
              <div class="pofw-oi-value-title">
                <span><?php echo htmlspecialchars($value['title']); ?></span>
              </div>
              <div class="pofw-oi-value-image">
                <img id="pofw_oi_values_<?php echo $valueId; ?>_image_preview" src="<?php echo htmlspecialchars($value['image_url']); ?>" <?php echo empty($value['image_url']) ? 'style="display:none;"' : ''; ?>/>
                <input id="pofw_oi_values_<?php echo $valueId; ?>_image" name="pofw_oi_values[<?php echo $valueId; ?>][image]" type="hidden" value="<?php echo htmlspecialchars($value['image']); ?>"> 
                <span class="pofw-oi-image-upload" title="<?php echo __('Click to choose an image', 'pofw-option-images'); ?>" <?php echo !empty($value['image_url']) ? 'style="display:none;"' : ''; ?>><?php echo __('Choose Image...', 'pofw-option-images'); ?></span>
                <span class="pofw-oi-image-delete" title="<?php echo __('Click to delete the image', 'pofw-option-images'); ?>" <?php echo empty($value['image_url']) ? 'style="display:none;"' : ''; ?>><?php echo __('Delete', 'pofw-option-images'); ?></span>              
                <input type="hidden" name="pofw_oi_values[<?php echo $valueId; ?>][oi_value_id]" value="<?php echo $value['oi_value_id']; ?>"/>                            
              </div>                        
            </div>          
          <?php endforeach; ?>        
        </div>                  
      </div>    
    <?php endforeach; ?>           
    <input type="hidden" id="pofw_oi_changed" name="pofw_oi_changed" value="0">        
  </div> 
   <script type="text/javascript">
    jQuery('#pofw_oi_options').pofwOptionImages({
      selectImageText : "<?php echo __('Insert Image', 'pofw-option-images') ?>"    
    });        
  </script>                 
<?php endif; ?>     
</div>

    