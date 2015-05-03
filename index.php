<?php
//check if session exists then redirect it to home.php
session_start();
if (isset($_SESSION['user_id']))
{
    header ("Location: home.php");
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
    <div class="row">
        <form method="post" role="form" name="inputForm" action="home.php" onsubmit="submit_validate_user();">
              <div class="col-xs-offset-3 col-xs-6 col-xs-offset-3">
                <div class="form-group">
                    <label for="UserName">
                        UserName:
                    </label>
                     <input type="text" class="form-control" name="username" id="username" placeholder="Enter User Name:" required>
                </div>
                <div class="form-group">
                    <label for="Password">
                        Password:
                    </label>
                     <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password:" required>
                </div>
                <button type="submit" class="btn btn-primary col-xs-6 col-xs-6 col-xs-6">Submit</button>
            </div>
          </form>
      </div>
  </div>
  </body>
<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script type="text/javascript">
  /**
       * validate_user send an ajax request to backend file validate_user.php
       * send a ajax request to backend file valid_user.php and alert the relavant message on ui
       *  if successfult display path of sql file where all the update/insert queries will be stored and navigate to form submission by return true
       *  else display relevant message without navigating to form submission with return false
       * @param {String} user username
       * @param {String} pass password
       * @return {boolean} false if username and password are valid else true 
       */
      function validate_user(user, pass)
      {
        var valid = 0;
        var message = '';
        var request = $.ajax({
          type: "POST",
          url: "backend/validate_user.php",
          async: false,
          dataType: 'json',
          data: {email_id: user, pass: pass},
          success: function(response){
                        valid = parseInt(response[0]);
                        message = response[1];
                    },
                  error: function(xhr, status, error) {
                        console.log(xhr);
                        console.log(error);
                    }
        });
        request.fail(function( jqXHR, textStatus ) {
          alert( "Request failed to validate user: " + textStatus );
        });
        if(valid == 1)
        {
          alert("logged in successfully");
          return true;
        }
        else
        {
          alert("can not login due to error: " + message);
          return false;
        }
      }
      function submit_validate_user()
      {
        return validate_user($("#username").val(), $("#password").val());
      }
</script>
</html>