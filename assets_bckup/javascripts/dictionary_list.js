//translate_list
var FilesHelper = {

    searchText: '',
    perPage : 10,
    page : 0,
    url :'',
    postData:'',

    init : function(){
       this.setValue('searchText','');
       this.getDataAjax();
    }, // end function  init
    

    getDataAjax : function(){
        var url = SITE_URL+'dictionary/dicAjax/'
            var obj = this;
            $.ajax({
            url:  url+this.page,
            type: 'POST',
            data: {earchText:this.searchText,page:this.page,perPage:this.perPage},
            dataType: 'json',
            success: function(data) {
                obj.postData = data;
                obj.setDataTable();
                obj.setPagination();
            },
            error: function(jqXHR, textStatus, errorThrown) {
               // console.log(errorThrown);
            }
        });
    },

    setValue : function(name,value){
        this[name] = value;
    },//end function  setValue

    setDataTable: function(){
        $('#tbl-word>tbody').empty();
        $('#tbl-word>tbody').html(this.postData.data);
        // tooltops();
    },

    setPagination: function(){
        $('.paginationdiv').empty();
        $('.paginationdiv').html(this.postData.pagination);
    },
}

$(document).on('change', '.project-filter', function(){
    var id = this.id;
    var value = $(this).val();
    FilesHelper.setValue(id,value);
    FilesHelper.setValue('page',0);
    FilesHelper.getDataAjax();
});

$(document).on('click','#reset-filter',function(){
    FilesHelper.init();
    $('#pagination_offset').val(FilesHelper.perPage);
});

$(document).on('change', '#pagination_offset',function(){
    var value = $(this).val();
    FilesHelper.setValue('page',0);
    FilesHelper.setValue('perPage',value);
    FilesHelper.getDataAjax();
});


$(document).on('keyup', '#searchText',function(){
    var value = $(this).val();
    if(value.length >= 3)
    {
        FilesHelper.setValue('page',0);
        FilesHelper.setValue('searchText',value);
        FilesHelper.getDataAjax();
    }
    else if(value.length == 0)
    {
        FilesHelper.init();
    }
});

$(function(){
    FilesHelper.init();
});

function getData(page)
{
     FilesHelper.setValue('page',page);
     FilesHelper.getDataAjax();
}



$(document).on('click','.delete-word',function(){
   var id = $(this).attr('data-id');
   if(confirm("Are you sure to delete this dictionary and all words?"))
   {
        $.ajax({
            url:  SITE_URL+'dictionary/delete_dictionary/',
            type: 'POST',
            data: {id:id},
            dataType: 'json',
            success: function(data) {
                if(data.error == '0')
                {
                    alert('Dictionary deleted successfully.');
                    FilesHelper.init();
                }
                else
                {
                    alert(data.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
               // console.log(errorThrown);
            }
        });
   }
});
