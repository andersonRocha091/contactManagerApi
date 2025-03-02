<?php 
namespace App\Domains\Voip\Infrastructure\Adapters;

use App\Domains\Voip\Interfaces\VoipCallServiceInterface;
use Twilio\Rest\Client;
use Twilio\Rest\Api\V2010\Account\CallInstance;
use Illuminate\Support\Facades\Log;
use Exception;

class TwillioCallAdapter implements VoipCallServiceInterface {

    private Client $client;
    private string $fromPhone;

    public function __construct() {
        $config = config('services.voip.twilio');
        $this->client = new Client($config['sid'], $config['token']);
        $this->fromPhone = $config['from'];
    }

    /**
     * Function that starts everything needed to execute a call
     * @param string $toPhoneNumber - destination phone
     * @param string $instructionUrl - TwiML URL
     * @return CallInstance
     */
    public function initiateCall(string $toPhoneNumber, string $instructionUrl): CallInstance {
        try {
            return $this->client->calls->create(
                $toPhoneNumber,
                $this->fromPhone,
                ['url' => $instructionUrl]
            );
        } catch (Exception $e) {
            Log::error('Failed to initiate call: ' . $e->getMessage(), [
                'toPhoneNumber' => $toPhoneNumber,
                'instructionUrl' => $instructionUrl,
                'error' => $e
            ]);
            throw $e;
        }
    }

    public function setClient(Client $client): void {
        $this->client = $client;
    }
}