<?php

namespace nextcluster\misc;


class ServiceInformation implements \JsonSerializable
{
    private string $name;
    private int $onlinePlayers;
    private int $maxPlayers;
    private string $motd;
    private string $platform;
    private array $players;
    private float $cpu;
    private int $memoryUsage;
    private int $maxMemory;

    public function __construct(int $onlinePlayers, int $maxPlayers, String $motd, array $players)
    {
        $this->name = gethostname();
        $this->onlinePlayers = $onlinePlayers;
        $this->maxPlayers = $maxPlayers;
        $this->motd = $motd;

        $this->platform = "POCKETMINE";

        $this->players = $players;

        $this->cpu = sys_getloadavg()[0];
        $this->memoryUsage = 0; //Todo: Implement memory usage
        $this->maxMemory = intval(ini_get('memory_limit')) * 1024 * 1024;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getOnlinePlayers(): int
    {
        return $this->onlinePlayers;
    }

    public function setOnlinePlayers(int $onlinePlayers): void
    {
        $this->onlinePlayers = $onlinePlayers;
    }

    public function getMaxPlayers(): int
    {
        return $this->maxPlayers;
    }

    public function setMaxPlayers(int $maxPlayers): void
    {
        $this->maxPlayers = $maxPlayers;
    }

    public function getMotd(): string
    {
        return $this->motd;
    }

    public function setMotd(string $motd): void
    {
        $this->motd = $motd;
    }

    public function getPlatform(): string
    {
        return $this->platform;
    }

    public function setPlatform(string $platform): void
    {
        $this->platform = $platform;
    }

    public function getPlayers(): array
    {
        return $this->players;
    }

    public function setPlayers(array $players): void
    {
        $this->players = $players;
    }

    public function getCpu(): float
    {
        return $this->cpu;
    }

    public function setCpu(float $cpu): void
    {
        $this->cpu = $cpu;
    }

    public function getMemoryUsage(): int
    {
        return $this->memoryUsage;
    }

    public function setMemoryUsage(int $memoryUsage): void
    {
        $this->memoryUsage = $memoryUsage;
    }

    public function getMaxMemory(): int
    {
        return $this->maxMemory;
    }

    public function setMaxMemory(int $maxMemory): void
    {
        $this->maxMemory = $maxMemory;
    }

    #[\Override] public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}