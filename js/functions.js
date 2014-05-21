$(document).ready(function() {
    
       $(document).on('click', '#addQuery', function(e){
        var inp=$('.queryinput').length;
          //<div class="input-group">
          inp++;
          $('.navbar-form > div').eq(-3).after("<div class='input-group queryinput'><span class='input-group-addon'>Query "+inp+"</span><input type='text' name='queryarr[]' class='form-control' placeholder='Enter Query'></div>");
         //e.stopPropagation()
       // alert(inp);
    });
        $(document).on('click', '#addVendorExp', function(e){
        var inp=$('.vendorinput').length;
          //<div class="input-group">
          inp++;
          
          $('.navbar-form > div').eq(-2).find('.panel-body').append("<div class='input-group vendorinput'><span class='input-group-addon'>Vendor "+inp+"</span><input type='text' name='vendorarr[]' class='form-control' placeholder='Enter Vendor'></div>");
          $(".vendorpane").slideDown();
         
       // alert(inp);
         // e.stopPropagation()
    });
    
    $(document).on('click', '#submitbtn', function(e){
       // e.stopPropagation();
       if(validateForm()){
           $('.alert-danger').html('Oh snap! Some Queries/Vendors are Empty.').slideUp();
           $('form#procform').submit();
       }else{
           $('.alert-danger').html('Oh snap! Some Queries/Vendors are Empty.').slideDown();
       }
         // e.stopPropagation();
    });
    function validateForm(){
        success=true;
        if ($('#inp_file').val()=="")
         success=false;
         $('.queryinput').each(function(){
         if ($(this).find('input').val().trim()==""){
             $(this).addClass("has-error");
             success=false;
         }
         else 
             $(this).removeClass("has-error");
          }); 
      $('.vendorinput').each(function(){
         if ($(this).find('input').val().trim()==""){
             $(this).addClass("has-error");
             success=false;
         }
         else 
             $(this).removeClass("has-error");
        });
        return(success);
    }
    
});


