<?php
session_start();
if (!(isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''))
{
	header ("Location: index.php");
	exit();
}
?>
<html>
<head>
<link rel="stylesheet" type ="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"></link>
</head>
<body>
<div>
	<div class="jumbotron text-center">
          <h1>Email client</h1>
    </div>
	<div class = "col-xs-2">
	    <div class="list-group" id="vertical_menu">
	        <a href="#" class="list-group-item vertical_nav_tabs" onclick = "func_display_compose()">
	            compose
	        </a>
	        <a href="#" class="list-group-item vertical_nav_tabs" onclick = "func_display_inbox()">
	            inbox
	        </a>
	        <a href="#" class="list-group-item vertical_nav_tabs" onclick = "func_display_sentbox()">
	            sentbox
	        </a>
	    </div>
	</div>
	<div class = "col-xs-8">
		<div class="panel panel-default display_div" id="div_inbox" style="display:none;">
			<div class="panel-heading">
				<h3 class="panel-title">Inbox</h3>
			</div>
			<div class="panel-body">
				<div class="col-xs-offset-2 col-xs-8 col-xs-offset-2">
					<table id="table_inbox">
						<tr>
							<th>Thread id</th>
							<th>From</th>
							<th>Subject</th>
						</tr>
						<tbody id="tbody_inbox">
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="panel panel-default display_div" id="div_sentbox" style="display:none;">
			<div class="panel-heading">
				<h3 class="panel-title">Sentbox</h3>
			</div>
			<div class="panel-body">
				<div class="col-xs-offset-2 col-xs-8 col-xs-offset-2">
					<table id="table_sentbox">
						<tr>
							<th>Thread id</th>
							<th>From</th>
							<th>Subject</th>
						</tr>
						<tbody id="tbody_sentbox">
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="panel panel-default display_div" id="div_mail_thread" style="display:none;">
			<div class="panel-heading">
				<h3 class="panel-title" id="div_mail_thread_subject">Sentbox</h3>
			</div>
			<div class="panel-body">
				<div class="col-xs-offset-2 col-xs-8 col-xs-offset-2">
					<table id="table_sentbox">
						<tr>
							<th>Thread id</th>
							<th>From</th>
							<th>Subject</th>
						</tr>
						<tbody id="tbody_sentbox">
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="panel panel-default display_div" id="div_compose" style="display:none;">
			<div class="panel-heading">
				<h3 class="panel-title">Compose</h3>
			</div>
			<div class="panel-body">
				<div class="col-xs-offset-2 col-xs-8 col-xs-offset-2">
					<form method="post" role="form" onsubmit="compose_mail();return false;">
                        <div class = "row">
                            <div class="form-group " id="to_email_id_div">
                                <label for="to_email_id">
                                    To:
                                </label>
                                <input type="text" class="form-control" name="to_email_id" id="to_email_id" required>
                            </div>
                            <div class="form-group " id="cc_email_id_div">
                                <label for="cc_email_id">
                                    Cc:
                                </label>
                                <input type="text" class="form-control" name="cc_email_id" id="cc_email_id">
                            </div>
                            <div class="form-group " id="bcc_email_id_div">
                                <label for="bcc_email_id">
                                    Bcc:
                                </label>
                                <input type="text" class="form-control" name="bcc_email_id" id="bcc_email_id">
                            </div>
                            <div class="form-group " id="subject_div">
                                <label for="subject">
                                    subject:
                                </label>
                                <input type="text" class="form-control" name="content" id="subject"></input>
                            </div>
                            <div class="form-group " id="content_div">
                                <label for="content">
                                    content:
                                </label>
                                <textarea class="form-control" name="content" id="content"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
				</div>
			</div>
		</div>
	</div>
	<div class = "col-xs-2">
		<div class="list-group">
			<a href="#" class="list-group-item">
				Welcome <?php echo $_SESSION['user_id'];?>
			</a>
			<a href="logout.php" class="list-group-item">Logout</a>
		</div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script type="text/javascript">
    function func_display_inbox()
    {
        $(".display_div").css("display","none");
        $("#div_inbox").css("display","block");
        var return_array = fetch_inbox();
        //remove previous
        $("#tbody_inbox").html("");
        $.each(return_array , function(key, thread_object)
        {
        	$("#tbody_inbox").append("<tr><td>" + thread_object["fk_thread_id"] + "</td><td>" + thread_object["email_id"] + "</td><td>" + thread_object["subject"]+ "</td></tr>");
        });
    }
    function func_display_sentbox()
    {
        $(".display_div").css("display","none");
        $("#div_sentbox").css("display","block");
        var return_array = fetch_sentbox();
        //remove previous
        $("#tbody_sentbox").html("");
        $.each(return_array , function(key, thread_object)
        {
        	$("#tbody_sentbox").append("<tr><td>" + thread_object["fk_thread_id"] + "</td><td>" + thread_object["email_id"] + "</td><td>" + thread_object["subject"]+ "</td></tr>");
        });
    }
    function func_display_compose()
    {
        $(".display_div").css("display","none");
        $("#div_compose").css("display","block");
    }
    function fetch_inbox()
    {
    	var return_val = null;
        var request = $.ajax({
          type: "POST",
          url: "backend/fetch_inbox.php",
          async: false,
          dataType: 'json',
          success: function(response)
          {
          	return_val = response;
            },
	      error: function(xhr, status, error) {
	            console.log(xhr);
	            console.log(error);
	        }
        });
        request.fail(function( jqXHR, textStatus ) {
          alert( "Request failed to validate user: " + textStatus );
        });
        return return_val;
    }
    function fetch_sentbox()
    {
    	var return_val = null;
        var request = $.ajax({
          type: "POST",
          url: "backend/fetch_sentbox.php",
          async: false,
          dataType: 'json',
          success: function(response)
          {
          	return_val = response;
            },
	      error: function(xhr, status, error) {
	            console.log(xhr);
	            console.log(error);
	        }
        });
        request.fail(function( jqXHR, textStatus ) {
          alert( "Request failed to validate user: " + textStatus );
        });
        return return_val;
    }
    function compose_mail()
    {
		var content = $("#content").val().trim();
		var subject = $("#subject").val().trim();
    	var to_email_id = $("#to_email_id").val().trim();
		var cc_email_id = $("#cc_email_id").val().trim();
		var bcc_email_id = $("#bcc_email_id").val().trim();
    	if(content == "")
    	{
    		alert("Please insert mail body");
    		return false;
    	}
    	if(subject == "")
    	{
    		alert("Please insert subject");
    		return false;
    	}
    	if(to_email_id == "")
    	{
    		alert("Please insert to_email_id");
    		return false;
    	}
    	var successful = insert_mail(content, to_email_id, cc_email_id, bcc_email_id, 1, subject);
    	if(successful == 1)
    	{
    		alert("Mail inserted successfully");
    	}
    	else
    	{
    		alert("Mail inserted successfully");
    	}
    }
    /**
     * returns 1 on successful and -1 if not successful
     */
    function insert_mail(content, to_email_id, cc_email_id, bcc_email_id, new_thread, subject, thread_id)
    {
    	var return_val = null;
        var request = $.ajax({
          type: "POST",
          url: "backend/compose_mail.php",
          async: false,
          data: {content: content, subject:subject, thread_id:thread_id, to_email_id: to_email_id, cc_email_id : cc_email_id, bcc_email_id:bcc_email_id, new_thread: new_thread},
          success: function(response)
          {
          	return_val = response;
            },
	      error: function(xhr, status, error)
	      {
	            console.log(xhr);
	            console.log(error);
	       }
        });
        request.fail(function( jqXHR, textStatus )
        {
          alert( "Request failed to validate user: " + textStatus );
        });
        return return_val;
    }
</script>
</body>
</html>