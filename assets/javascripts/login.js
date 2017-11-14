$('#frm_login').submit(function (e) {
    e.preventDefault();
    $("#frm_login").validate();
});

var loginFormValidator = $("#frm_login").validate({
    rules: {
        email: {
            required: true,
            email:true
        },
        password: {
            required: true
        },
    },
    messages: {
        email: {
            required: "The email is required"
        },
        password: {
            required: "The password is required"
        },
    },
    submitHandler: function (form) {
       form.submit();
    }
});