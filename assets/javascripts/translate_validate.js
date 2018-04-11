
var FormValidator = $("#frmForm").validate({
    rules: {
        fileStatus:{
             required: true
        },
        file: {
            required: function(){
                return ($('#doc_file').val() == '')
            },
            extension:ext
        },
        'arrDic[]': {
            required: true
        },
    },
    messages: {
         file_id: {
            required: "Invalid"
        },
        file: {
            required:"Please upload file",
            extension:"Please upload {0} file only"
        },
        'arrDic[]': {
            required: "Please select atleast one dictionary"
        },
    }/*,
    submitHandler: function (form) {
       //form.submit();
    }*/
});
