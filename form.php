<?php
          if ($_SERVER['REQUEST_METHOD']=="POST"){

             // Set the "To" email address
             $to="carrot_afro@yahoo.com";

            //Subject of the mail
             $subject="[FORM TEST] Join Us E-mail with Resume attachment";

             // Get the sender's name and email address plug them a variable to be used later
             $from = stripslashes($_POST['name'])."<".stripslashes($_POST['email']).">";
            
            // Check for empty fields
             if(empty($_POST['name'])  || empty($_POST['email']) || empty($_POST['message']))
            {
              $errors .= "\n Error: all fields are required";
            }
            
            // Get all the values from input
            $name = $_POST['name'];
            $email_address = $_POST['email'];
            $message = $_POST['message'];

            // Check the email address
            if (!eregi( "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email_address))
            {
              $errors .= "\n Error: Invalid email address";
            }

             // Now Generate a random string to be used as the boundary marker
             $mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";

             // Now Store the file information to a variables for easier access
             $tmp_name = $_FILES['filename']['tmp_name'];
             $type = $_FILES['filename']['type'];
             $file_name = $_FILES['filename']['name'];
             $size = $_FILES['filename']['size'];

             // Now here we setting up the message of the mail
             $message = "\n\n Name: $name \n\n Email: $email_address \n\nMessage: \n\n $message \n\nHere is your file: $file_name";

             // Check if the upload succeded, the file will exist
             if (file_exists($tmp_name)){

                // Check to make sure that it is an uploaded file and not a system file
                if(is_uploaded_file($tmp_name)){

                   // Now Open the file for a binary read
                   $file = fopen($tmp_name,'rb');

                   // Now read the file content into a variable
                   $data = fread($file,filesize($tmp_name));

                   // close the file
                   fclose($file);

                   // Now we need to encode it and split it into acceptable length lines
                   $data = chunk_split(base64_encode($data));
               }

                // Now we'll build the message headers
                $headers = "From: $from\r\n" .
                   "MIME-Version: 1.0\r\n" .
                   "Content-Type: multipart/mixed;\r\n" .
                   " boundary=\"{$mime_boundary}\"";

                // Next, we'll build the message body note that we insert two dashes in front of the  MIME boundary when we use it
                $message = "This is a multi-part message in MIME format.\n\n" .
                   "--{$mime_boundary}\n" .
                   "Content-Type: text/plain; charset=\"iso-8859-1\"\n" .
                   "Content-Transfer-Encoding: 7bit\n\n" .
                   $message . "\n\n";

                // Now we'll insert a boundary to indicate we're starting the attachment we have to specify the content type, file name, and disposition as an attachment, then add the file content and set another boundary to indicate that the end of the file has been reached
                $message .= "--{$mime_boundary}\n" .
                   "Content-Type: {$type};\n" .
                   " name=\"{$file_name}\"\n" .
                   //"Content-Disposition: attachment;\n" .
                   //" filename=\"{$fileatt_name}\"\n" .
                   "Content-Transfer-Encoding: base64\n\n" .
                   $data . "\n\n" .
                   "--{$mime_boundary}--\n";

                // Thats all.. Now we need to send this mail... :)
                if (@mail($to, $subject, $message, $headers))
              {
                   ?>
                   <div><center><h1>Mail Sent successfully !!</h1></center></div>
                   <?php
              }else
              {
                   ?>
                   <div><center>
                     <h1>Error !! Unable to send Mail..</h1></center></div>
                   <?php
              }
             }
          }
          ?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <title>Submit CV form by humanattitude</title>

    <link rel="stylesheet" href="stylesheets/styles.css">
    <link rel="stylesheet" href="stylesheets/pygment_trac.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="wrapper">
      <header>
        <h1>Submit CV form</h1>
      </header>
      <section> 
          <div>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
          <div>
            <p><label for="email">Email:</label><br>
          <input id="email" name="email" type="text" />
            </p>
            <p>
              <label for="tele">Upload Your Resume:</label><br>
              <input id="tele" name="filename" type="file" />
            </p>
            <p>
              <label for="message">Message:<br></label>
              <textarea cols="71" rows="5" name="message"></textarea>
            </p>
          </div>
          <input class="formbtn" type="submit" value="Send Message" />
          </form>
          </div>
        </div>
      </section>
      <footer>
        <p>This form is made by <a href="https://github.com/reager-">Reager-</a></p>
      </footer>
    </div>    
  </body>
</html>