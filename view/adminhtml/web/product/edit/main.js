( function ($) {
  "use strict";

$.widget("pektsekye.pofwOptionImages", {

  _create: function(){   		    

    $.extend(this, this.options); 

    this._on({
      "change select, input": $.proxy(this.setChanged, this),
      "click .pofw-oi-image-upload": $.proxy(this.uploadImage, this),
      "click .pofw-oi-image-delete": $.proxy(this.deleteImage, this)       
    });
     
  },
  
  uploadImage : function(e){

    if (wp.media) {
      e.preventDefault();

      if (!this.uploader) {
        var uploader = wp.media({
          title: this.selectImageText,
          button: {
            text: this.selectImageText
          },
          multiple: false
        });
        uploader.on('select', function() {
          var attachment = uploader.state().get('selection').first().toJSON();
          uploader.pofwInput.val(attachment.pofwOiImagePath).change();        
          var div = uploader.pofwInput.closest('div');
          div.find('.pofw-oi-image-upload').hide();
          var url = attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;         
          div.find('img').attr('src', url).show();                      
          div.find('.pofw-oi-image-delete').show();                  
        });          
        this.uploader = uploader;
      }        
     
      this.uploader.pofwInput = $(e.target).closest('div').find('input[name$="[image]"]');
      this.uploader.open();    
    } 
    return false;         
  },


  deleteImage : function(e){       
    var div = $(e.target).closest('div');
    div.find('.pofw-oi-image-delete').hide();      
    div.find('img').hide();
    div.find('input[name$="[image]"]').val('').change(); 
    div.find('.pofw-oi-image-upload').show();
    return false;          
  },
    
    
  setChanged : function(){
    $('#pofw_oi_changed').val(1);     
  }   

});

})(jQuery);