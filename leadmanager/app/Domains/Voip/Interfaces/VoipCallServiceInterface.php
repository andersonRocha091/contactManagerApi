<?php

namespace App\Domains\Voip\Interfaces;

use Illuminate\Http\Request;

interface VoipCallServiceInterface {
    
    /**
     * Generates a token that will enable front end application conect to twilio voice service
     *
     * @param string $identity - unique id for token generation
     * @return string token generated
     * @throw exception case its an invalid token
     * 
     */
    public function generateToken($identity);

}