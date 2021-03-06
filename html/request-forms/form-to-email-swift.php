<?php

require_once './lib/swift_required.php';
debug_to_console( "got the required" );

//ini_set("include_path", '/home/uscedu5/php:' . ini_get("include_path")  );
if(!isset($_POST['submit']))
{
	//This page should not be accessed directly. Need to submit the form.
	echo "error; you need to submit the form!";
}

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
$snapchat_geofilter = $_POST['snapchat_geofilter'];
$shirt_design = $_POST['shirt_design'];
$other_artwork = $_POST['other_artwork'];

$description = $_POST['description'];
$other_notes = $_POST['other_notes'];
$deadline = $_POST['deadline'];


$materials = "";
if (!empty($facebook_cover_photo)) {
  $materials .= "- $facebook_cover_photo\n";
}
if (!empty($profile_picture)) {
  $materials .= "- $profile_picture\n";
}
if (!empty($a_11x17_poster)) {
  $materials .= "- $a_11x17_poster\n";
}
if (!empty($snapchat_geofilter)) {
  $materials .= "- $snapchat_geofilter\n";
}
if (!empty($shirt_design)) {
  $materials .= "- $shirt_design\n";
}
if (!empty($other_artwork)) {
  $materials .= "- $other_artwork\n";
}

$filename = $_FILES['file']['name'];

date_default_timezone_set('America/Los_Angeles');
//$date = date('m/d/Y h:i:s a', time());
$date = date('YmdHis');

//Validate first
if(empty($first_name)||empty($last_name)||empty($user_email)) 
{
    echo "Name and email are mandatory!";
    exit;
}

if(IsInjected($user_email))
{
    debug_to_console( "EMAIL INJECTED" );
    echo "Bad email value!";
    exit;
}

//$email_from = 'mcgartla@usc.edu';//<== update the email address
$email_subject = "Graphics Request Case $date";
$email_body = "Graphics Request Details:\n\n".
    "Name: $first_name $last_name\n".
    "Email: $user_email\n".
    "Organization: $organization\n\n".
    "Event Title: \n".
    "$event_title\n".
    "Event Time: \n".
    "$event_month $event_date $event_time\n".
    "Event Location: \n".
    "$event_location\n\n".
    "Required Materials:\n".
    "$materials\n".
    "Description\n".
    "$description\n\n".
    "Other Notes\n".
    "$other_notes\n".
    "Deadline: $deadline";
    


    
$to = "luke.mcgartland@gmail.com";//<== update the email address
// $boundary =md5(date('r', time())); 

// $headers = "From: $user_email \r\n";
// $headers .= "Reply-To: $user_email \r\nMIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"_1_$boundary\"";
// //$headers .= "\r\nMIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"_1_$boundary\"";

// $message="This is a multi-part message in MIME format.

// --_1_$boundary
// Content-Type: multipart/alternative; boundary=\"_2_$boundary\"

// --_2_$boundary
// Content-Type: text/plain; charset=\"iso-8859-1\"
// Content-Transfer-Encoding: 7bit

// $email_body

// --_2_$boundary--
// --_1_$boundary
// Content-Type: application/octet-stream; name=\"$filename\" 
// Content-Transfer-Encoding: base64 
// Content-Disposition: attachment 

// $attachment
// --_1_$boundary--";

// //$message = $email_body;
// //Send the email!
// mail($to,$email_subject,$message,$headers);




debug_to_console( "about to create message" );
$message = Swift_Message::newInstance()
  // Give the message a subject
  ->setSubject($email_subject)
  // Set the From address with an associative array
  ->setFrom(array('temp.web.pb@gmail.com' => 'PB Graphics'))
  ->setReplyTo(array('pbgraphics@usc.edu'=> 'PB Graphics'))
  // Set the To addresses with an associative array
  ->setTo(array($to))
  // Give it a body
  ->setBody($email_body)
  // And optionally an alternative body
  //->addPart('<q>Here is the message itself</q>', 'text/html')
 ;
#debug_to_console( "About to Null Check" );
if(file_exists($_FILES['file']['tmp_name']) || is_uploaded_file($_FILES['file']['tmp_name'])) {
  debug_to_console( "File Attachment" );
  $message->attach(
    Swift_Attachment::fromPath($_FILES['file']['tmp_name'])->setFilename($_FILES['file']['name'])
  );
}
/*if !is_null($_FILES['file']['tmp_name']){
  debug_to_console( "Attachment there" );
  $message->attach(
    Swift_Attachment::fromPath($_FILES['file']['tmp_name'])->setFilename($_FILES['file']['name'])
  );
}else{
  debug_to_console("Attachment not there");
}*/
#debug_to_console( "Null Check over" );





$receipt = "Here is your receipt for your graphics request. Please send any follow up emails to pbgraphics@usc.edu and include your case number in the subject.\n\n$email_body";
debug_to_console( "User email is [$user_email]" );
$receipt_message = Swift_Message::newInstance()
  // Give the message a subject
  ->setSubject($email_subject)
  // Set the From address with an associative array
  ->setFrom(array('temp.web.pb@gmail.com' => 'Do-not-reply-PB-Webmaster'))
  ->setReplyTo(array('pbgraphics@usc.edu'=> 'PB Graphics'))
  // Set the To addresses with an associative array
  ->setTo(array($user_email))
  // Give it a body
  ->setBody($receipt)
  // And optionally an alternative body
  //->addPart('<q>Here is the message itself</q>', 'text/html')
 ;





debug_to_console( "created message" );
$user = "temp.web.pb@gmail.com";
$pass = "iikwxuzlvqejlxic";

$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 587, 'tls');
$transport->setUsername($user);
$transport->setPassword($pass);
$mailer = Swift_Mailer::newInstance($transport);
try {
	$result = $mailer->send($message);
	debug_to_console($result);
}
catch (\Exception $e){
	debug_to_console( "Error" );
	debug_to_console("{$e->getMessage()}");
}


$mailer2 = Swift_Mailer::newInstance($transport);
try {
  $result2 = $mailer2->send($receipt_message);
  debug_to_console($result2);
}
catch (\Exception $e){
  debug_to_console( "Error" );
  debug_to_console("{$e->getMessage()}");
}




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
function debug_to_console( $data ) {

    /*if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;*/
}
?> 