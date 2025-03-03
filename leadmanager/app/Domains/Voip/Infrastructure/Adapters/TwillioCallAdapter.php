<?php 
namespace App\Domains\Voip\Infrastructure\Adapters;

use App\Domains\Voip\Interfaces\VoipCallServiceInterface;
use Twilio\Rest\Client;
use Twilio\Rest\Api\V2010\Account\CallInstance;
use Twilio\Exceptions\RestException;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;
use Illuminate\Support\Facades\Log;
use Exception;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class TwillioCallAdapter implements VoipCallServiceInterface {

    protected $accountSid;
    protected $apiKey;
    protected $apiSecret;
    protected $twilioNumber;
    protected $appSid;

    public function __construct() {
        $config = config('services.voip.twilio');
        $this->accountSid = $config['sid'];
        $this->apiKey = $config['apiKey'];
        $this->apiSecret = $config['apiSecret'];
        $this->twilioNumber = $config['twilioNumber'];
        $this->appSid = $config['appSid'];
    }


    public function generateToken($identity) {
        
        try {
            $customKey = $this->apiKey."asdf";
            $token = new AccessToken(
                $this->accountSid,
                $customKey,
                $this->apiSecret,
                3600,
                $identity
            );

            $voiceGrant = new VoiceGrant();
            $voiceGrant->setOutgoingApplicationSid($this->appSid);
            $voiceGrant->setIncomingAllow(true);
            $token->addGrant($voiceGrant);

            $token = $token->toJWT();

            $valid = $this->isValidToken($token);
            
            if (isset($valid['status']) && $valid['status'] === 'invalid') {
                throw new Exception('Couldnt create a token for the credentials provided', 400);
            }

            return $token;

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    private function isValidToken($token) {
        
        if (empty($token)) {
            throw new Exception('No data found on token', 400);
        }
        try {
            $decoded = JWT::decode($token, new Key($this->apiSecret, 'HS256'));
            return ['status' => 'valid',
                'data' => (array) $decoded];
        } catch (Exception $e) {
            return ['status' => 'invalid', 
            'error' => $e->getMessage()];
        }

    }
}