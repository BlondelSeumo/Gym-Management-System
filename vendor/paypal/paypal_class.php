<?php
define('LOG_FILE', '.ipn_results.log');
define('SSL_P_URL', 'https://www.paypal.com/cgi-bin/webscr');
define('SSL_SAND_URL','https://www.sandbox.paypal.com/cgi-bin/webscr');

class Gmgt_paypal_class {
	
	private $ipn_status;
	public $admin_mail; 				
	public $paypal_mail;				
	public $txn_id;						
	public $ipn_log;                   
	private $ipn_response;               
	public $ipn_data = array();      
	private $fields = array();     
	private $ipn_debug; 		
	
	function __construct() {
		$this->ipn_status = '';
		$this->admin_mail = null;
		$this->paypal_mail = null;
		$this->txn_id = null;
		$this->tax = null;
		$this->ipn_log = true;
		$this->ipn_response = '';
		$this->ipn_debug = false;
	}
	public function add_field($field, $value) {
		$this->fields["$field"] = $value;
	}
	
	
	public function submit_paypal_post($enable_sandbox) {
		$paypal_account_type = 0;
		if($enable_sandbox)
		{
			$paypal_account_type = 1;
		}

		$paypal_url = $paypal_account_type ? SSL_SAND_URL : SSL_P_URL;
		echo "<html>\n";
		echo "<head><title>Processing Payment...</title></head>\n";
		echo "<body onLoad=\"document.forms['paypal_form'].submit();\">\n";
		echo "<center><p>Please wait, your order is being processed and you";
		echo " will be redirected to the paypal website.</p></center>\n";
		echo "<form method=\"post\" name=\"paypal_form\" ";
		echo "action=\"".$paypal_url."\">\n";
		if (isset($this->paypal_mail))echo "<input type=\"hidden\" name=\"business\" value=\"$this->paypal_mail\"/>\n";
		foreach ($this->fields as $name => $value) {
			echo "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
		}
		echo "<center><br/><br/>If you are not automatically redirected to ";
		echo "paypal within 5 seconds...<br/><br/>\n";
		echo "<input type=\"submit\" value=\"Click Here\"></center>\n";
		
		echo "</form>\n";
		echo "</body></html>\n";
		
	}

	public function validate_ipn() {
		
		$hostname = gethostbyaddr ( $_SERVER ['REMOTE_ADDR'] );
		if (! preg_match ( '/paypal\.com$/', $hostname )) {
			$this->ipn_status = 'Validation post isn\'t from PayPal';
			$this->log_ipn_results ( false );
			return false;
		}
		
		if (isset($this->paypal_mail) && strtolower ( $_POST['receiver_email'] ) != strtolower(trim( $this->paypal_mail ))) {
			$this->ipn_status = "Receiver Email Not Match";
			$this->log_ipn_results ( false );
			return false;
		}
		
		if (isset($this->txn_id)&& in_array($_POST['txn_id'],$this->txn_id)) {
			$this->ipn_status = "txn_id have a duplicate";
			$this->log_ipn_results ( false );
			return false;
		}
		
		$paypal_url = ($_POST['test_ipn'] == 1) ? SSL_SAND_URL : SSL_P_URL;
		$url_parsed = parse_url($paypal_url);        
		
		
		$post_string = '';    
		foreach ($_POST as $field=>$value) { 
			$this->ipn_data["$field"] = $value;
			$post_string .= $field.'='.urlencode(stripslashes($value)).'&'; 
		}
		$post_string.="cmd=_notify-validate"; 
		
		
		if (isset($_POST['test_ipn']) )
			$fp = fsockopen ( 'ssl://www.sandbox.paypal.com', "443", $err_num, $err_str, 60 );
		else
			$fp = fsockopen ( 'ssl://www.paypal.com', "443", $err_num, $err_str, 60 );
 
		if(!$fp) {
			
			$this->ipn_status = "fsockopen error no. $err_num: $err_str";
			$this->log_ipn_results(false);       
			return false;
		} else { 
			
			fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n"); 
			fputs($fp, "Host: $url_parsed[host]\r\n"); 
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n"); 
			fputs($fp, "Content-length: ".strlen($post_string)."\r\n"); 
			fputs($fp, "Connection: close\r\n\r\n"); 
			fputs($fp, $post_string . "\r\n\r\n"); 
		
			
			while(!feof($fp)) { 
		   	$this->ipn_response .= fgets($fp, 1024); 
		   } 
		  fclose($fp); 
		}

		if (! eregi("VERIFIED",$this->ipn_response)) {
			$this->ipn_status = 'IPN Validation Failed';
			$this->log_ipn_results(false);   
			return false;
		} else {
			$this->ipn_status = "IPN VERIFIED";
			$this->log_ipn_results(true); 
			return true;
		}
	} 
   
	private function log_ipn_results($success) {
		$hostname = gethostbyaddr ( $_SERVER ['REMOTE_ADDR'] );
	
		$text = '[' . date ( 'm/d/Y g:i A' ) . '] - ';
		
		if ($success)
			$this->ipn_status = $text . 'SUCCESS:' . $this->ipn_status . "!\n";
		else
			$this->ipn_status = $text . 'FAIL: ' . $this->ipn_status . "!\n";
			
		$this->ipn_status .= "[From:" . $hostname . "|" . $_SERVER ['REMOTE_ADDR'] . "]IPN POST Vars Received By Paypal_IPN Response API:\n";
		foreach ( $this->ipn_data as $key => $value ) {
			$this->ipn_status .= "$key=$value \n";
		}
		
		$this->ipn_status .= "IPN Response from Paypal Server:\n" . $this->ipn_response;
		$this->write_to_log ();
	}
	
	private function write_to_log() {
		if (! $this->ipn_log)
			return;
		
		$fp = fopen ( LOG_FILE , 'a' );
		fwrite ( $fp, $this->ipn_status . "\n\n" );
		fclose ( $fp ); 
		chmod ( LOG_FILE , 0600 );
	}
	public function send_report($subject) {
		$body .= "from " . $this->ipn_data ['payer_email'] . " on " . date ( 'm/d/Y' );
		$body .= " at " . date ( 'g:i A' ) . "\n\nDetails:\n" . $this->ipn_status;
		mail ( $this->admin_mail, $subject, $body );
	}
	public function print_report(){
		$find [] = "\n";
		$replace [] = '<br/>';
		$html_content = str_replace ( $find, $replace, $this->ipn_status );
		echo $html_content;
	}
	
	public function dump_fields() {
 
		echo "<h3>paypal_class->dump_fields() Output:</h3>";
		echo "<table width=\"95%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\">
            <tr>
               <td bgcolor=\"black\"><b><font color=\"white\">Field Name</font></b></td>
               <td bgcolor=\"black\"><b><font color=\"white\">Value</font></b></td>
            </tr>"; 
		ksort($this->fields);
		foreach ($this->fields as $key => $value) {echo "<tr><td>$key</td><td>".urldecode($value)."&nbsp;</td></tr>";}
		echo "</table><br>"; 
	}
	private function debug($msg) {
		
		if (! $this->ipn_debug)
			return;
		
		$today = date ( "Y-m-d H:i:s " );
		$myFile = ".ipn_debugs.log";
		$fh = fopen ( $myFile, 'a' ) or die ( "Can't open debug file. Please manually create the 'debug.log' file and make it writable." );
		$ua_simple = preg_replace ( "/(.*)\s\(.*/", "\\1", $_SERVER ['HTTP_USER_AGENT'] );
		fwrite ( $fh, $today . " [from: " . $_SERVER ['REMOTE_ADDR'] . "|$ua_simple] - " . $msg . "\n" );
		fclose ( $fh );
		chmod ( $myFile, 0600 );
	}
}         
 