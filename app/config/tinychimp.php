<?php
/**
 * TinyChimp config file
 */
 
return array(
	// required
	// your secret MailChimp API key
	'api_key' => 'f289dca88a0d37980186233a219c7801-us6',
	
	// whether or not to use a secure connection
	// note: setting this to true might slow down your application
	'secure'  => false,
	
	// connection timeout in seconds
	'timeout' => 300,
	
	// optional
	// note: fill out only if you want to use a custom url
	'api_url' => ''
);

/* End of file tinychimp.php */