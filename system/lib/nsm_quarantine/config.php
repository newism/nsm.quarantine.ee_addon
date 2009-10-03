<?php

return array(

	"developer_key"		=> "NSM",
	"addon_key"			=> "Quarantine",
	"author"  			=> "Leevi Graham",
	"author_url"		=> "http://leevigraham.com",
	"company_url"		=> "http://newism.com.au",
	"description"		=> "NSM Quarantine - Community powered, peer review comment and weblog entry monitoring system",
	"docs_url"			=> "http://newism.com.au/docs/",
	"version" 			=> "1.0.0",

	"components" => array(
		"extensions" => array("Nsm_quarantine_ext"),
		"modules" => array("Nsm_quarantine")
	),

	// NSM Addon Updater support
	"versions_xml_url"	=> "http://newism.com.au/versions/",
	
	// Javascript promos
	"promos"			=> array(
		"script_url"		=> "http://leevigraham.com/promos/ee.php"
	),
	
	// Donate button
	"paypal" 			=>  array(
		"account"				=> "sales@newism.com.au",
		"donations_accepted"	=> TRUE,
		"donation_amount"		=> "20.00",
		"currency_code"			=> "USD",
		"return_url"			=> "http://leevigraham.com/donate/thanks/",
		"cancel_url"			=> "http://leevigraham.com/donate/cancel/"
	)
	
);