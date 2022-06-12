jQuery(document).ready(function($){

    var _custom_media = true,
    _orig_send_attachment = wp.media.editor.send.attachment;
    
      jQuery('body').on('click','.meta_upload',function(e) {
        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = $(this);
        var id = button.attr('id').replace('button_', '');
        _custom_media = true;
        wp.media.editor.send.attachment = function(props, attachment){
          if ( _custom_media ) {
            $("#"+id).val(attachment.url);
          } else {
            return _orig_send_attachment.apply( this, [props, attachment] );
          };
        }
    
        wp.media.editor.open(button);
        return false;
      });
      jQuery("#reset").on("click",function(){
        jQuery('.error').hide();
        jQuery("#prof_pic").val("");
        jQuery("#gkb_create_users").trigger("reset");
        
    });
    jQuery('#gkb_create_users').submit(function(e){
        e.preventDefault();
       var fd = new FormData();
        var f_name = jQuery("#first_name").val();
        var l_name = jQuery("#last_name").val();
        var email = jQuery("#email").val();
        var checkbox = jQuery("input[name=hobby]:checked").length;
        var hobbies = [];
        $.each($("input[name='hobby']:checked"), function(){
            hobbies.push($(this).val());
        });
        var gender = jQuery("input[name=gender]:checked").length;
        var gender_val = jQuery("input[name=gender]:checked").val();
       var prof_pic = jQuery("#prof_pic").val();
        var regEx = /^[A-Z0-9][A-Z0-9._%+-]{0,63}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;  
        var validEmail = regEx.test(email); 
        console.log(Theme.ajax_url);
        if(f_name.length<1){
            jQuery('#first_name').after('<span class="error">This field is required</span>');
        }else if(l_name.length<1){
            jQuery('#last_name').after('<span class="error">This field is required</span>');
        }else if(email.length<1){
            jQuery('#email').after('<span class="error">This field is required</span>');
        }else if(!validEmail){
            jQuery('#email').after('<span class="error">Enter Valid Email</span>');
        }else if(checkbox < 1){
            jQuery("#hobby_text").after('<span class="error">This field is required</span>');
        }else if(gender < 1){
            jQuery("#gen_text").after('<span class="error">This field is required</span>');
        }else{
            jQuery.ajax({
                type: "POST",
                dataType : "json",
                url: Theme.ajax_url,
                data: {"action":"add_users","f_name":f_name,'l_name':l_name,'email':email,'hobbies':hobbies,'gender':gender_val,'prof_pic':prof_pic},
                success: function(data){
                    if(data.status == 1){
                        jQuery("#prof_pic").val("");
                        jQuery("#first_name").val("");
                        jQuery("#last_name").val("");
                        jQuery("input[name=hobby]").val("");
                        jQuery("input[name=gender]").val("");
                        
                        if(data.error == 'User Added Successfully'){
                            jQuery(".info").show();
                        }else{
                            jQuery(".info").html(data.error);
                            jQuery(".info").show();
                        }
                    }
                }
            });
            return false;
        }
    });
    if(jQuery("#users_list").length > 0){
        jQuery('#users_list').DataTable();
    }
    jQuery('body').on('click', '.delete_action', function() {
        var rec_id = jQuery(this).attr('data-recid');
        console.log(rec_id);
        jQuery.ajax({
            type: "POST",
            dataType : "json",
            url: Theme.ajax_url,
            data: {"action":"delete_donation_rec","rec_id":rec_id},
            success: function(data){
                if(data.status == 1){
                    window.location.reload();
                }else{
                    console.log(data.error);
                }
            }
        });
        return false;
    });
});