$(function() {
    $('.dropify').dropify();

    var drEvent = $('.dropify').dropify();
    drEvent.on('dropify.beforeClear', function(event, element) {
             
        var resp =  confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        if (resp) {
           var code = $(this).closest('#file_name').data("code");
           $.ajax({
            type: 'POST',
            url: base_url + 'user/delete_pro_pic',
            cache: false,
            async: false,
            data: "code="+code,
            dataType:"html",
            success: function(response) {              
              var obj = $.parseJSON(response);		          
	          if(obj.status==1) {
                 toaster('success',obj.msg);               
              } else {
                 toaster('error',obj.msg);     
              }
            },
            error: function(error) {	      
              toaster('error',obj.msg);
            }
         });           
        }
        
    });

    //drEvent.on('dropify.afterClear', function(event, element) {
    //    alert('File deleted');
    //});
    
});