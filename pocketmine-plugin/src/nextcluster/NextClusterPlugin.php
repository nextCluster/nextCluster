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

namespace nextcluster;

use nextcluster\http\NextClusterRoute;
use nextcluster\http\routes\DefaultRoute;
use nextcluster\http\routes\ServiceInformationRoute;
use nextcluster\misc\ServiceInformation;
use nextcluster\rest\RestThread;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\AsyncTask;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\thread\NonThreadSafeValue;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\EventLoop\Loop;
use React\Http\HttpServer;
use RingCentral\Psr7\Response;


class NextClusterPlugin extends PluginBase
{
    private static NextClusterPlugin $instance;
    private RestThread $thread;

    /**
     * @var NextClusterRoute[]
     */
    public array $routes = [];

    protected function onEnable(): void
    {
        self::$instance = $this;

        $this->getLogger()->info("§e[nextCluster] PocketMine-MP plugin was successfully loaded!");

        $this->routes = [
            new DefaultRoute(),
            new ServiceInformationRoute()
        ];

                $loop = Loop::get();

                $server = new HttpServer($loop, function (ServerRequestInterface $request) use ($loop) {
                    // Verarbeite die Anfrage
                    return new Response(200, ['Content-Type' => 'text/plain'], Server::getInstance()->getMotd());
                });

                $socket = new \React\Socket\SocketServer('0.0.0.0:8080', [], $loop);
                $server->listen($socket);

                $loop->run();


    }

    protected function onLoad(): void
    {
        $autoloaderPath = $this->findComposerAutoloader();
        if ($autoloaderPath !== null) {
            require_once $autoloaderPath;
        } else {
            $this->getLogger()->error("Konnte den Composer-Autoloader nicht finden.");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }

    }

    private function findComposerAutoloader(): ?string {
        // Der Basispfad deines Plugins.
        $pluginPath = $this->getFile(); // oder __DIR__, abhängig von der Struktur

        while (!file_exists($pluginPath . '/vendor/autoload.php')) {
            $pluginPath = dirname($pluginPath);
            if ($pluginPath === dirname($pluginPath)) {
                return null;
            }
        }

        return $pluginPath . '/vendor/autoload.php';
    }


    public static function currentInformation(): ServiceInformation
    {
        return new ServiceInformation(
            count(Server::getInstance()->getOnlinePlayers()),
            Server::getInstance()->getMaxPlayers(),
            Server::getInstance()->getMotd(),
            array_map(fn($player) => $player->getName(), Server::getInstance()->getOnlinePlayers())
        );
    }

    public static function dispatchCommand(string $command): void
    {
        Server::getInstance()->dispatchCommand(new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage()), $command);
    }

    /**
     * @return NextClusterPlugin
     */
    public static function getInstance(): NextClusterPlugin
    {
        return self::$instance;
    }

    /**
     * @return NextClusterRoute[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    protected function onDisable(): void
    {
        $this->thread->quit();
    }
}