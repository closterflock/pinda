<?php


namespace App\Http\Response;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class APIResponse implements Arrayable, Jsonable, \JsonSerializable, ToResponse
{
    /**
     * @var string The API status.
     */
    private $status;
    /**
     * @var string The API message.
     */
    private $message;
    /**
     * @var array The API data.
     */
    private $data;
    /**
     * @var int The HTTP status.
     */
    private $httpStatus;
    /**
     * @var array The headers.
     */
    private $headers;

    public function __construct($status = 'success', $message = 'Success', $data = [], $httpStatus = 200, $headers = [])
    {
        $this->status = $status;
        $this->message = $message;
        $this->data = $data;
        $this->httpStatus = $httpStatus;
        $this->headers = $headers;
    }

    /**
     * Retrieves the API status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Retrieves the message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Retrieves the data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Retrieves the HTTP Status.
     *
     * @return int
     */
    public function getHttpStatus()
    {
        return $this->httpStatus;
    }

    /**
     * Retrieves the headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Retrieves the message content.
     *
     * @return array
     */
    public function getContent()
    {
        return [
            'status' => $this->getStatus(),
            'message' => $this->getMessage(),
            'data' => $this->getData()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * {@inheritdoc}
     */
    function jsonSerialize()
    {
        return $this->toJson();
    }

    /**
     * {@inheritdoc}
     */
    public function toResponse()
    {
        return response($this->toArray(), $this->getHttpStatus(), $this->getHeaders());
    }
}