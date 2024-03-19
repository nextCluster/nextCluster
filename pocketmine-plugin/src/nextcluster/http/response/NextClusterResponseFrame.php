<?php

namespace nextcluster\http\response;

use nextcluster\http\util\StatusCode;

class NextClusterResponseFrame
{
    public function __construct(
        public StatusCode $statusCode,
        public String $content
    ) {}

    /**
     * @return String
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return StatusCode
     */
    public function getStatusCode(): StatusCode
    {
        return $this->statusCode;
    }
}
