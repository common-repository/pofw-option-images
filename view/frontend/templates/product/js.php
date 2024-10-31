<?php
if (!defined('ABSPATH')) exit;
?>
<?php if (count($this->getProductOptions()) > 0): ?>   
<script type="text/javascript"> 
    var config = {  
      imageDirUrl : "<?php echo $this->getBaseImageUrl(); ?>"     
    };
    
    var pofwOiData = <?php echo $this->getOptionsJson(); ?>;
    
    jQuery.extend(config, pofwOiData);
      
    jQuery("#pofw_product_options").pofwOptionImages(config);
    
</script>        
<?php endif; ?>
