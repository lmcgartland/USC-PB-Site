<?php
//ini_set("include_path", '/home/uscedu5/php:' . ini_get("include_path")  );
if(!isset($_POST['submit']))
{
	//This page should not be accessed directly. Need to submit the form.
	echo "error; you need to submit the form!";
}
//$name = $_POST['name'];
//$visitor_email = $_POST['email'];
$message = $_POST['message'];

$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$user_email = $_POST['user_email'];
$organization = $_POST['organization'];

$event_title = $_POST['event_title'];
$event_month = $_POST['event_month'];
$event_date = $_POST['event_date'];
$event_time = $_POST['event_time'];
$event_location = $_POST['event_location'];

$facebook_cover_photo = $_POST['facebook_cover_photo'];
$profile_picture = $_POST['profile_picture'];
$a_11x17_poster = $_POST['a_11x17_poster'];
$snapchat_geofilter = $_POST['a_11x17_poster'];
$shirt_design = $_POST['shirt_design'];
$other_artwork = $_POST['other_artwork'];

$description = $_POST['description'];
$other_notes = $_POST['other_notes'];

//Validate first
if(empty($first_name)||empty($last_name)||empty($user_email)) 
{
    echo "Name and email are mandatory!";
    exit;
}

if(IsInjected($user_email))
{
    echo "Bad email value!";
    exit;
}

$email_from = 'mcgartla@usc.edu';//<== update the email address
$email_subject = "Graphics Request Case #";
$email_body = "You have received a new request from $first_name $last_name.\n".
    "Here are the details:\n".
    "Name: $first_name $last_name\n".
    "Email: $user_email\n".
    "Organization: $organization\n".
    "Event Details: \n".
    "$event_title\n".
    "$event_month $event_date $event_time\n".
    "$event_location\n\n".
    "Required Materials\n".
    "$facebook_cover_photo\n".
    "$profile_picture\n".
    "$a_11x17_poster\n".
    "$snapchat_geofilter\n".
    "$shirt_design\n".
    "$other_artwork\n\n".
    "Description\n".
    "$description\n\n".
    "Other Notes\n".
    "$other_notes";

    
$to = "luke.mcgartland@gmail.com";//<== update the email address
$headers = "From: $user_email \r\n";
$headers .= "Reply-To: $user_email \r\n";
//Send the email!
mail($to,$email_subject,$email_body,$headers);
//done. redirect to thank-you page.
header('Location: thank-you.html');


// Function to validate against any email injection attempts
function IsInjected($str)
{
  $injections = array('(\n+)',
              '(\r+)',
              '(\t+)',
              '(%0A+)',
              '(%0D+)',
              '(%08+)',
              '(%09+)'
              );
  $inject = join('|', $injections);
  $inject = "/$inject/i";
  if(preg_match($inject,$str))
    {
    return true;
  }
  else
    {
    return false;
  }
}
   
?> 