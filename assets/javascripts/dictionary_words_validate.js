
var FormValidator = $("#frmForm").validate({
    rules: {
        from_lang:{
             required: true
        },
        to_lang: {
            required: true
        }
    },
    messages: {
         from_lang: {
            required: "Please enter from language"
        },
        to_lang: {
            required: "Please enter to language"
        }
    }/*,
    submitHandler: function (form) {
       //form.submit();
    }*/
});
