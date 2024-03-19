<?php

namespace nextcluster\http\routes;

use nextcluster\http\NextClusterRoute;
use nextcluster\http\response\NextClusterResponseFrame;
use nextcluster\http\util\ContentType;
use nextcluster\http\util\Method;
use nextcluster\http\util\StatusCode;
use nextcluster\NextClusterPlugin;

class DefaultRoute extends NextClusterRoute {

    #[\Override] public function getMethod(): Method
    {
        return Method::GET;
    }

    #[\Override] public function getPath(): string
    {
        return "/";
    }

    #[\Override] public function getContentType(): ContentType
    {
        return ContentType::APPLICATION_JSON;
    }

    #[\Override] public function request(): NextClusterResponseFrame
    {
        $content = "";
        return new NextClusterResponseFrame(StatusCode::Accepted, $content);
    }
}