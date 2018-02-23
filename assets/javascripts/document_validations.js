/*----------- validation library starts here ----------*/
jQuery('#frm_documents').validate({
    rules: {
        doc_name: {
            required: true
        },
        venue_id: {
            required: true
        },
        file_name: {
            required: function(element){ 
                return ($('#doc_file').val() == '')
                },
            extension: "pdf|xls|xlsx"
        }
    },
    messages: {
        doc_name: {
            required: 'Please enter document name'
        },
        venue_id: {
            required: 'Please select venue'
        },
        file_name: {
            required: 'Please choose a file to upload',
            extension: 'Please upload either excel or pdf file'
        }
    }
});
/*----------- validation library ends here ----------*/