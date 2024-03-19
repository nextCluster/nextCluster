<?php

namespace nextcluster\http\routes;

use nextcluster\http\NextClusterRoute;
use nextcluster\http\response\NextClusterResponseFrame;
use nextcluster\http\util\ContentType;
use nextcluster\http\util\Method;
use nextcluster\http\util\StatusCode;
use nextcluster\NextClusterPlugin;

class ServiceInformationRoute extends NextClusterRoute
{

    #[\Override] public function getContentType(): ContentType
    {
       return ContentType::APPLICATION_JSON;
    }

    #[\Override] public function getMethod(): Method
    {
        return Method::GET;
    }

    #[\Override] public function getPath(): string
    {
        return "/information";
    }

    #[\Override] public function request(): NextClusterResponseFrame
    {
        return new NextClusterResponseFrame(StatusCode::OK,
            json_encode(NextClusterPlugin::currentInformation()));
    }
}