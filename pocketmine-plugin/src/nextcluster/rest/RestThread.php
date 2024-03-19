<?php

/*
* MIT License
*
* Copyright (c) 2024 nextCluster
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*/

namespace nextcluster\rest;

use pocketmine\Server;
use pocketmine\thread\NonThreadSafeValue;
use pocketmine\thread\Thread;

class RestThread extends Thread
{
    public function __construct(
        public NonThreadSafeValue $port,
        public NonThreadSafeValue $hostname,
        public NonThreadSafeValue $routes
    )
    {
    }

    private function handleRequest($conn): void
    {
        $headers = '';
        while ($line = rtrim(fgets($conn))) {
            $headers .= $line . "\n";
        }

        list($method, $uri) = explode(' ', explode("\n", $headers)[0]);

        $responseSent = false;
        foreach ($this->routes->deserialize() as $route) {
            if ($route->getMethod()->name === $method && $route->getPath() === $uri) {
                $responseFrame = $route->request();
                $responseContent = $responseFrame->getContent();
                $responseStatus = $responseFrame->getStatusCode()->value;
                $contentType = $route->getContentType()->value;

                $response = "HTTP/1.1 " . $responseStatus . "\r\nContent-Type: " . $contentType . "\r\n\r\n" . $responseContent;
                fwrite($conn, $response);
                $responseSent = true;
                break;
            }
        }

        if (!$responseSent) {
            $response = "HTTP/1.1 404 Not Found\r\nContent-Type: text/plain\r\n\r\nNot Found";
            fwrite($conn, $response);
        }

        fclose($conn);
    }


    public function quit(): void
    {
        $this->quit();
    }

    protected function onRun(): void
    {
        $socket = stream_socket_server("tcp://" . $this->hostname->deserialize() . ":" . $this->port->deserialize(), $errno, $errstr);
        if (!$socket) {
            Server::getInstance()->getLogger()->error("Socket could not be created: [" . $errno . "] " . $errstr);
            return;
        }

        while ($conn = @stream_socket_accept($socket, -1)) {
            $this->handleRequest($conn);
        }
        fclose($socket);
    }
}