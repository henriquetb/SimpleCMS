// /public/js/custom.js

jQuery(function($) {
	/*
	 * Shows the page using page_id
	 * */
	var showPage = function( page_id ) {
		$.post("pages/show", {
            page_id: page_id
        }, function(data){
                    if(data.response == true){
                    	$('#content').html("<h1>"+data.page.page_title+"</h1><hr>");
                    	$('#content').append(data.page.page_content);
                    	$('#content').append("<hr><a href='#' id='"+data.page.page_id+"' class='edit_page'>(edit page)</a><br>");
                    	$('.edit_page').data("page", data.page);
                    	// print success message
                    } else {
                        // print error message
                        console.log('could not show page');
                    }
                }, 'json');
	};
	
	/*
	 * Loads the page form
	 * */
	var loadForm = function( page ) {
		if (page!=null)
			bt = "Update";
		else
			bt = "Insert";
		
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
    	form += "    <textarea name='contentT' id='contentT' class='contentT' placeholder='Content'></textarea>";
    	//form += "    <input type='text' class='form-control' id='title' placeholder='Title'>";
    	form += "  </div>";
    	form += "  <button type='button' id='addSubmit' class='addSubmit btn btn-default'>"+bt+"</button>";
    	//form += "<a href='#' id=\"bt\" class='bg'>aa<\a>";
    	form += "</form>";
    	
    	$('#content').html(form);

		if (page!=null){
			$('#title').val(page.page_title);
			$('#title').data("page_id", page.page_id);
			if (page.page_is_home==1)
				$('#is_home').attr('checked', true);
			$('#contentT').val(page.page_content); 
			
		}
	};
	
	var validatePage = function( title, is_home, content ) {
		if (title.length<=0)
			return "Title can't be empty.";
		else if (is_home<0 || is_home>1)
			return "Invalid home value.";
		else if (content.length<=0)
			return "Content can't be empty.";
		
		return null;
	};
	
	/*
	 * Menu item onclick event
	 * */
    $("#menu").on('click', 'a.view_page',function(event){
    	event.preventDefault();
        var view_id = $(this).attr('id');
        showPage(view_id);
    });

    /*
     * Add/Edit page submit button onclick event
     * */
    $('#content').on('click', 'button.addSubmit', function(event){
    	event.preventDefault();
    	title = $('#title').val();
    	if ($('#is_home').is(":checked")){
    		is_home = 1;
    	}else{
    		is_home = 0;
    	}
    	content = $('#contentT').val();
    	page_id = $('#title').data("page_id");
    	
    	if (page_id>0){
			action = "update";
    	} else{
    		action = "add";
    		page_id = 0;
    	}
    	
    	//validate the client side Page data
        //empty inputs
        //size of inputs
        //sanitize content
    	validateReturn = validatePage(title, is_home, content);
    	if (validateReturn!=null){
    		alert (validateReturn);
    		return;
    	}
    	
    	
        $.post("pages/"+action, {
        	page_id: page_id,
        	page_title: title,
        	page_is_home: is_home,
        	page_content: content,
        }, function(data){
                if(data.response == true){
                	//insert page in the menu
                	if (page_id == 0)
                		$('#pagesList').append("<a href='#' id='"+data.new_page_id+"' class='view_page'>"+title+"</a><br>");
                	else{
                		$('#menu a#'+page_id).html(title);
                	}
                	showPage(data.page_id);
                	// print success message
                } else {
                    // print error message
                    console.log('could not add');
                    alert(data.error);
                }
            }, 'json');
    });
    
    /*
     * Add Page onclick event
     * */
    $('#addPage').on('click', function(event){
    	event.preventDefault();
    	loadForm(null);
    	
    });

    /*
     * Edit Page onclick event
     * */
    $("#content").on('click', 'a.edit_page', function(event){
    	event.preventDefault();
    	var page = $(this).data('page');
    	loadForm(page);
    });
    
    
    
});