<?php

//use MailChimp;
require_once('mailchimp.php');

class mcMaintenance {

    private function getAuthKey() {
		
		//Retrieve plugin configuration options from database
		$options = get_option( 'rcMC_options' );
		
		//$key = 'f13265bfdafd2de7e09cf0ac8252cb7f-us14';
		$key = esc_html( $options[ 'api_key' ] );
		return $key;
	}

    public function getSpecificList($listID) { 
        $mc = new MailChimp($this->getAuthKey());
        
        $specificList = $mc->get('/lists/' . $listID . '');
        return $specificList;
    }
}