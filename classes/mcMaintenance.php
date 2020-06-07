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

    public function subscribeToList($data) {

        ///assign array data to variables
		$email = trim($data[0]);
		$firstName = trim($data[1]);
        $lastName = trim($data[2]);
        
        // create an instance of the MailChimp class
        $mc = new MailChimp($this->getAuthKey());

        //Retrieve plugin configuration options from database
        $options = get_option( 'rcMC_options' );
        
        // grab the list_id
        $list_id = esc_html( $options[ 'list_id' ] );

        // insert subscriber in MailChimp list
        $result = $mc->post('/lists/' . $list_id . '/members', array(
            'email_address' => $email,
            'merge_fields' => array('FNAME' => $firstName, 'LNAME' => $lastName),
            'status' => 'pending',
            'language' => 'en'
        ));
        
        $msg = $result['detail'];
        
        if ($result['status'] === 'pending') {
            //alert user that an email confirmation has been sent
			$msg = "<p>An email has been sent to <strong>" . $result['email_address'] . "</strong> for verification. " .
            $msg .= "<br/><strong>Please note:</strong> the email may be in your spam folder if you cannot see it in your inbox. <br/>";
            $msg .= "<br/>Follow the email instructions to subscribe yourself. </p>";
        }
        
        return $msg;
    }
}