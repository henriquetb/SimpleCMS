// /public/js/custom.js

jQuery(function($) {
    $("#menu").on('click', 'a.view_page',function(event){
    	event.preventDefault();
        var view_id = $(this).attr('id');
        $.post("pages/show", {
            page_id: view_id
        }, function(data){
                    if(data.response == true){
                    	$('#content').append(data.view_page_content);
                    	// print success message
                    } else {
                        // print error message
                        console.log('could not show page');
                    }
                }, 'json');
    });
    
    //$('#menu').on('click', 'a.')
});