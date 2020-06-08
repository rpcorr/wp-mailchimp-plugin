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
        
        if ($result['status'] === 'pending') {
            //alert user that an email confirmation has been sent
            $msg = '<h3>Verification is Required</h3>' .
			       '<p>An email has been sent to <strong>' . $result['email_address'] . '</strong> for verification.</p> ' .
                   '<p><strong>Please note:</strong> the email may be in your spam folder if you cannot see it in your inbox.</p>' .
                   '<p>Follow the email instructions to subscribe yourself.</p>';
        } else if ( $result['title'] == "Member Exists") { 
            // alert user that s/he is already subscribed to the mailing list
            $msg = '<h3>Hooray</h3>' .
			       '<p>You are already subscribed to our mailing list.</p>';
        }
        
        return $msg;
    }
}