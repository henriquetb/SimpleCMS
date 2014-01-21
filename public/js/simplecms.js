// /public/js/custom.js

jQuery(function($) {
	var showPage = function( page_id ) {
		$.post("pages/show", {
            page_id: page_id
        }, function(data){
                    if(data.response == true){
                    	$('#content').html(data.view_page_content);
                    	// print success message
                    } else {
                        // print error message
                        console.log('could not show page');
                    }
                }, 'json');
	};
	
    $("#menu").on('click', 'a.view_page',function(event){
    	event.preventDefault();
        var view_id = $(this).attr('id');
        showPage(view_id);
    });

    $('#content').on('click', 'button.addSubmit', function(event){
    	event.preventDefault();
    	title = $('#title').val();
    	if ($('#is_home').is(":checked")){
    	  is_home = 1;
    	}else{
    		is_home = 0;
    	}
    	content = $('#contentT').val();
    	
    	//validate the client side Page data
        //empty inputs
        //size of inputs
        //unique title
        //sanitize content
    	
    	
        $.post("pages/add", {
        	page_title: title,
        	page_is_home: is_home,
        	page_content: content,
        }, function(data){
                if(data.response == true){
                	$('#pagesList').append("<a href='#' id='"+data.new_page_id+"' class='view_page'>"+title+"</a><br>");
                	showPage(data.new_page_id);
                	// print success message
                } else {
                    // print error message
                    console.log('could not add');
                    alert(data.error);
                }
            }, 'json');
    });
    
    $('#addPage').on('click', function(event){
    	
    	event.preventDefault();
    	    	 
    	/*form = "<form>";
    	form += "<fieldset>";
    	form += "<label for='title'>Title</label>";
    	form += "<input type='text' name='title' id='title'>";
    	form += "<label for='is_home'>Home</label>";
    	form += "<input type='checkbox' name='is_home' id='is_home'>";
    	form += "<label for='content'>Content</label>";
    	form += "<textarea name='content' id='content'></textarea>";
    	//form += "<input type='text' name='email' id='email' value=''>";
    	form += "</fieldset>";
    	form += "</form>";*/
    	
    	form = "<form role='form' id=\"frm\">";
    	form += "  <div class='form-group'>";
    	form += "    <input type='text' class='title form-control' name='title' id=\"title\" placeholder='Title'>";
    	form += "  </div>";
    	form += " <div class='checkbox'>";
    	form += "   <label>";
    	form += "     Home <input type='checkbox' class='is_home' id='is_home'>";
    	form += "   </label>";
    	form += " </div>";
    	form += "  <div class='form-group'>";
    	form += "    <textarea name='content' id='contentT' class='contentT' placeholder='Content'></textarea>";
    	//form += "    <input type='text' class='form-control' id='title' placeholder='Title'>";
    	form += "  </div>";
    	form += "  <button type='button' id='addSubmit' class='addSubmit btn btn-default'>Submit</button>";
    	//form += "<a href='#' id=\"bt\" class='bg'>aa<\a>";
    	form += "</form>";
    	
    	$('#content').html(form);
    });
    
});