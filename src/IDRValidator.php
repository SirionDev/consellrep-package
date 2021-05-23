<?php

namespace Siriondev\ConsellRepublica;

use Carbon\Carbon;
use Illuminate\Http\Client\Response;

class IDRValidator
{
    const INVALID_STATUSES      = ['NOT_VALID'];
    const UNDERAGED_STATUSES    = ['VALID_UNDERAGED'];
    const ACTIVE_STATUSES       = ['VALID_ACTIVE','VALID_UNDERAGED'];
    const VALID_STATUSES        = ['VALID_ACTIVE','VALID_NOT_ACTIVE','VALID_UNDERAGED','VALID_UNKNOWN'];

    /**
     * @var string $idr: ID Republicana
     */
    private string $idr;

    /**
     * @var bool $status: Indicates request status
     */
    private bool $status = false;

    /**
     * @var bool $format: Indicates IDR format validity
     */
    private bool $format = false;

    /**
     * @var bool $valid: Indicates IDR validity
     */
    private bool $valid = false;

    /**
     * @var bool $active: Indicates IDR active status
     */
    private bool $active = false;

    /**
     * @var bool $underaged: Indicates if the member is underaged
     */
    private bool $underaged = false;

    /**
     * @var string $state: The treated response state
     */
    private ?string $state = null;

    /**
     * @var string $state: The treated response state
     */
    private ?string $message = null;

    /**
     * @var array $raw_response: CxR untreated response
     */
    private ?Response $raw_response = null;

    /**
     * @var Carbon $timestamp: Server validation time
     */
    private ?Carbon $timestamp;

    /**
     * IDR Constructor
     *
     * @return IDRResponse
     */
    public function __construct(string $idr)
    {
        $this->idr = $idr;

        $this->validateFormat();
    }

    /**
     * Parse CxR Response and load values into struct
     *
     * @return void
     */
    public function parse(object $response)
    {
        $this->raw_response = $response;

        $this->status = isset($response['state']) ? true : false;

        $this->state = isset($response['state']) ? $response['state'] : null;

        $this->timestamp = isset($response['req_time']) ? Carbon::createFromTimestamp($response['req_time']) : null;

        $this->valid = in_array($this->state, self::VALID_STATUSES);

        $this->underaged = in_array($this->state, self::UNDERAGED_STATUSES);

        $this->active = in_array($this->state, self::ACTIVE_STATUSES);
    }

    /**
     * Valid attribute getter
     *
     * @return bool $valid
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * Underaged attribute getter
     *
     * @return bool $underaged
     */
    public function isUnderaged(): bool
    {
        return $this->underaged;
    }

    /**
     * Active attribute getter
     *
     * @return bool $active
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Get format validity
     *
     * @return bool $format
     */
    public function isFormat(): bool
    {
        return $this->format;
    }

    /**
     * State attribute getter
     *
     * @return string $state
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * Timestamp attribute getter
     *
     * @return Carbon $timestamp
     */
    public function getTimestamp(): Carbon
    {
        return $this->timestamp;
    }

    /**
     * Message attribute getter
     *
     * @return string $message
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Message attribute getter
     *
     * @return string $message
     */
    public function getStatus(): bool
    {
        return $this->status;
    }

    /**
     * Validates IDR format
     *
     * @return bool $message
     */
    private function validateFormat(): void
    {
        $this->format = preg_match("/[A-Za-z]{1}\-[0-9]{3}\-[0-9]{5}/", $this->idr);
    }

    /**
     * Sets IDRValidator message
     *
     * @return bool $message
     */
    public function setMessage($message): void
    {
        $this->message = $message;
    }
}
