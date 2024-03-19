<?php

namespace nextcluster\http;

use nextcluster\http\response\NextClusterResponseFrame;
use nextcluster\http\util\ContentType;
use nextcluster\http\util\Method;

abstract class NextClusterRoute {

    public abstract function getContentType(): ContentType;

    public abstract function getMethod(): Method;
    #
    public abstract function getPath(): string;

    public abstract function request(): NextClusterResponseFrame;

}