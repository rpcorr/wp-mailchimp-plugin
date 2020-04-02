<?php

//use MailChimp;
require_once('mailchimp.php');

class mcMaintenance {

    private function getAuthKey() {
		
		//Retrieve plugin configuration options from database
		$options = get_option( 'rcMC_options' );
		
		//return API key
		return esc_html( $options[ 'api_key' ] );
	}

    public function getSpecificList($listID) { 

        // create a MailChimp object
        $mc = new MailChimp($this->getAuthKey());
        
        // return a specific list
        return $mc->get('/lists/' . $listID . '');
    }
}