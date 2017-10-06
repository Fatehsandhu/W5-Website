<?php

require_once('phpmailer/PHPMailerAutoload.php');

$toemails = array();

$toemails[] = array(
				'email' => 'info@wwwwiw.org', // Your Email Address
				'name' => 'W5' // Your Name
			);

// Confirm Message
$message_success = 'We have <strong>successfully</strong> received your Application and will get Back to you as soon as possible.';

$mail = new PHPMailer();

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {

	$fname = $_POST['template-jobform-fname'];
	$lname = $_POST['template-jobform-lname'];
	$email = $_POST['template-jobform-email'];
	$position = $_POST['template-jobform-position'];
	$salary = $_POST['template-jobform-salary'];
	$start = $_POST['template-jobform-start'];
	$experience = $_POST['template-jobform-experience'];
	$application = $_POST['template-jobform-application'];

	$name = $fname . ' ' . $lname;

	$subject = 'New Job Application';

	$botcheck = $_POST['template-jobform-botcheck'];

	if( $botcheck == '' ) {

		$mail->SetFrom( 'doNotReply@wwwwiw.org' , 'Job Form' );

        foreach( $toemails as $toemail ) {
			$mail->AddAddress( $toemail['email'] , $toemail['name'] );
		}
		$mail->Subject = $subject;

		$name = isset($name) ? "Name: $name<br><br>" : '';
		$email = isset($email) ? "Email: $email<br><br>" : '';
		$position = isset($position) ? "Position: $position<br><br>" : '';
		$salary = isset($salary) ? "Salary: $salary<br><br>" : '';
		$start = isset($start) ? "Start: $start<br><br>" : '';
		$experience = isset($experience) ? "Experience: $experience<br><br>" : '';
		$application = isset($application) ? "Application: $application<br><br>" : '';

		$referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>This Form was submitted from: ' . $_SERVER['HTTP_REFERER'] : '';

		$body = "$name $email $position $salary $start $experience $application $referrer";

		// For files
		if ( isset( $_FILES['template-jobform-cvfile'] ) && $_FILES['template-jobform-cvfile']['error'] == UPLOAD_ERR_OK ) {
			$mail->IsHTML(true);
			$mail->AddAttachment( $_FILES['template-jobform-cvfile']['tmp_name'], $_FILES['template-jobform-cvfile']['name'] );
		}

	

		$mail->MsgHTML( $body );
		$sendEmail = $mail->Send();

        
        //Please, Do not touch this.
		if( $sendEmail == true ):
			echo '{ "alert": "success", "message": "' . $message_success . '" }';
		else:
			echo '{ "alert": "error", "message": "Email <strong>could not</strong> be sent due to some Unexpected Error. Please Try Again later.<br /><br /><strong>Reason:</strong><br />' . $mail->ErrorInfo . '" }';
		endif;
	} else {
		echo '{ "alert": "error", "message": "Bot <strong>Detected</strong>.! Clean yourself Botster.!" }';
	}
    
} else {
	echo '{ "alert": "error", "message": "An <strong>unexpected error</strong> occured. Please Try Again later." }';
}

?>