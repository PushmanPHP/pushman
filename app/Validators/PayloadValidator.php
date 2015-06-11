<?php namespace Pushman\Validators;

use Binput;
use Pushman\Exceptions\InvalidPayloadException;
use Pushman\Interfaces\EventObject;
use Pushman\Interfaces\Validator;

class PayloadValidator implements Validator
{

    protected $event;

    public function __construct(EventObject $event)
    {
        $this->event = $event;
    }

    /**
     * Handles the validation.
     *
     * @param $payload
     * @return mixed|string
     * @throws InvalidPayloadException
     */
    public function validate()
    {
        $payload = $this->event->getPayload();

        if (empty($payload)) {
            return false;
        }

        $payload = str_replace("\r", '', $payload); // remove carriage returns
        $payload = str_replace("\n", '', $payload); // remove new lines
        $payload = str_replace("\t", '', $payload); // remove tabs

        $payloadCharacters = str_split($payload);
        $characterArray = [];
        foreach ($payloadCharacters as $key => $character) {
            if (mb_check_encoding($character, 'UTF-8')) {
                $characterArray[] = $character;
            }
        }
        $payload = implode('', $characterArray);

        $payload = BInput::clean($payload);

        if (!isJson($payload) or $this->containsScripts($payload)) {
            throw new InvalidPayloadException('Payload given is not a valid JSON string.');
        }

        $this->event->setPayload($payload);
    }

    /**
     * Literally checks for bad script injections.
     *
     * @param $payload
     *
     * @return bool
     */
    private function containsScripts($payload)
    {
        if (str_contains($payload, '<script>')) {
            return true;
        }

        return false;
    }
} 