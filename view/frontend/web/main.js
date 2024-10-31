( function ($) {
  "use strict";

  $.widget("pektsekye.pofwOptionImages", {

    _create: function(){   		    

      $.extend(this, this.options);
                
      this.addImages();

      this._on({
        "change .pofw-option" : $.proxy(this.updateImages, this)
      });                     
    },
    
    
    addImages : function(){
      var ii,ll, oId, vId, div, html;
      var l = this.optionIds.length;
      for (var i=0;i<l;i++){ 
        oId = this.optionIds[i];
        
        div = this.element.find('[name^="pofw_option['+oId+']"]').first().closest('div.control');
        
        html = '<div class="pofw-option-images">';
        ll = this.vIdsByOId[oId].length;
        for (ii=0;ii<ll;ii++){        
          vId = this.vIdsByOId[oId][ii];
          html += '<img src="'+ this.imageDirUrl + this.images[vId] + '" class="pofw-oi-image"  id="pofw_oi_image_'+vId+'" style="display:none;" />';            
        }  
        html += '</div>';
                
        div.prepend(html);        
      }
   
    },
    
        
    updateImages : function(e){
      var el = $(e.target);
      var oId = el[0].name.match(/\[(\d+)\]/)[1];   

      if (!this.vIdsByOId || !this.vIdsByOId[oId])
        return;

      var selectedIds = this.getOptionValues(el);
      
      var vId,el;
      var l = this.vIdsByOId[oId].length;
      while(l--){
        vId = this.vIdsByOId[oId][l] + '';
        el = $('#pofw_oi_image_'+vId);
        if (selectedIds.indexOf(vId) != -1){
          el.show();
        } else {
          el.hide();        
        }
      }      
    },
       
             
    getOptionValues : function(el){   
      if (el[0].type == 'select-one'){
        var value = el.val();
        return value ? [value] : [];      
      } else if (el[0].type == 'select-multiple'){
        var values = el.val();
        if (values && values[0] == ''){
          values.shift();
        }
        return values ? values : [];
      } else if (el[0].type == 'radio' || el[0].type == 'checkbox'){
        var values = [];
        var inputs = el.closest('.control').find('.pofw-option:checked').each(function(ind,elm){
          if (elm.value)
            values.push(elm.value);
        });
        return values;
      }      
    }       
    
  });

})(jQuery);
    


