<?php
/**
 * Created by PhpStorm.
 * User: Sparrow
 * Date: 01/09/2018
 * Time: 18:48
 */

namespace App\Services\Clients\EventExternalServiceClient;

use Carbon\Carbon;

class ExternalEvent
{
    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $message;

    /**
     * @var Carbon
     */
    private $dateTime;

    /**
     * @return integer
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param integer $userId
     * @return void
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return void
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return Carbon
     */
    public function getDateTime(): Carbon
    {
        return $this->dateTime;
    }

    /**
     * @param Carbon $dateTime
     * @return void
     */
    public function setDateTime(Carbon $dateTime): void
    {
        $this->dateTime = $dateTime;
    }
}
