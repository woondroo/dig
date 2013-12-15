<?php
class CMail
{
	
	public static function sendMail( $from, $to, $subject, $body, $type = '')
	{
        $url = "https://www.superbiiz.com/intranet/postman.php";
		$data = array ("password" => "eMailWiz", "from" => $from, "to" => $to, "subject" => $subject, "body" => $body, "type" => $type );
		$mailch = curl_init ();
		curl_setopt ( $mailch, CURLOPT_URL, $url );
		curl_setopt ( $mailch, CURLOPT_POST, 1 );
		curl_setopt ( $mailch, CURLOPT_POSTFIELDS, $data );
		curl_setopt ( $mailch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $mailch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt ( $mailch, CURLOPT_SSL_VERIFYHOST, 0 );
		$mailRst = curl_exec ( $mailch );
		$mailErr = curl_error ( $mailch );
		curl_close ( $mailch );
		if( ( $mailErr != '' ) || ( $mailRst != 1 ) )
		{
			return $mailRst;
		}
		else
		{
			return true;
        }
	}
	
//end class
}