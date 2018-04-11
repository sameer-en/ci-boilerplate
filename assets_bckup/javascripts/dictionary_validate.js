
var FormValidator = $("#frmForm").validate({
    rules: {
        dic_name:{
             required: true
        },
        file: {
            extension:ext
        },
        priority:{
            required: true,
            positiveNumber: true
        }
    },
    messages: {
         dic_name: {
            required: "Please enter dictionary name"
        },
        file: {
            extension:"Please upload {0} file only"
        },
        priority: {
            required: "Please enter priority"
        }
    }/*,
    submitHandler: function (form) {
       //form.submit();
    }*/
});

$.validator.addMethod('positiveNumber',
    function (value) { 
        var pattern = /^[0-9]$/;
        return value.match(pattern);
        //return Number(value) > 0;
    }, 'Enter a positive number.');