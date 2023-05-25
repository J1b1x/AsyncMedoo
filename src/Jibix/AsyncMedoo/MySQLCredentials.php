<?php
namespace Jibix\AsyncMedoo;
use JsonSerializable;
use Medoo\Medoo;
use Throwable;


/**
 * Class MySQLCredentials
 * @package Jibix\AsyncMedoo
 * @author Jibix
 * @date 26.05.2023 - 00:57
 * @project AsyncMedoo
 */
class MySQLCredentials implements JsonSerializable{

    public function __construct(
        protected string $database,
        protected string $password,
        protected string $user = "root",
        protected string $address = "127.0.0.1",
        protected int $port = 3306,
    ) {
        $actualAddress = $this->address;
        try {
            $this->address = gethostbyname($actualAddress);
        } catch (Throwable $e) {
            $this->address = $actualAddress;
        }
    }

    public function getDatabase(): string{
        return $this->database;
    }

    public function getPassword(): string{
        return $this->password;
    }

    public function getUser(): string{
        return $this->user;
    }

    public function getAddress(): string{
        return $this->address;
    }

    public function getPort(): int{
        return $this->port;
    }

    public function createConnection(): Medoo{
        return new Medoo([
            'type' => 'mysql',
            'database' => $this->database,
            'server'   => $this->address,
            'port'     => $this->port,
            'username' => $this->user,
            'password' => $this->password,
        ]);
    }

    public function jsonSerialize(): array{
        return [
            "database" => $this->database,
            "password" => $this->password,
            "user" => $this->user,
            "address" => $this->address,
            "port" => $this->port
        ];
    }

    public static function fromString(string $string): self{
        $data = json_decode($string, true);
        return new self(
            $data["database"],
            $data["password"],
            $data["user"] ?? "root",
            $data["address"] ?? "127.0.0.1",
            $data["port"] ?? 3306,
        );
    }
}