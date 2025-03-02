<?php

namespace App\Domains\Voip\Interfaces;

use Twilio\Rest\Api\V2010\Account\CallInstance;

interface VoipCallServiceInterface {
 
    /**
     * Initiates a VOIP call to the given phone number using the specified TwiML (or equivalent) URL.
     *
     * @param string $toPhoneNumber Destination phone number in E.164 format.
     * @param string $instructionUrl URL that returns call instructions (e.g., TwiML for Twilio).
     * @return mixed 
     */
    public function initiateCall(string $toPhoneNumber, string $instructionUrl);
}