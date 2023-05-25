# AsyncMedoo

![php](https://img.shields.io/badge/php-8.0-informational)
![api](https://img.shields.io/badge/pocketmine-4.0-informational)

A PocketMine-MP virion to execute PDO statements async using [Medoo](https://github.com/catfan/Medoo/)

## Initialization
### First you need to initialize the MySQL credentials, so just do:
```php
\Jibix\AsyncMedoo\AsyncMedoo::initialize(new \Jibix\AsyncMedoo\MySQLCredentials(
    "database",
    "password",
    "user",
    "address",
    3306 //port
));
```

## Using the [AsyncExecutor](https://github.com/J1b1x/AsyncMedoo/blob/master/src/Jibix/AsyncMedoo/AsyncExecuter.php)
### Instead of manually starting an async task, you can simply use the [async]() function, which automatically executes the provided task in the [AsyncExecutor](https://github.com/J1b1x/AsyncMedoo/blob/master/src/Jibix/AsyncMedoo/AsyncExecuter.php)
```php
private function dumpCoins(string $playerName): void{
    \Jibix\AsyncMedoo\util\async(
        fn (\Medoo\Medoo $medoo): int => $medoo->get("users", ["coins"], ["name" => $playerName]),
        function (int $coins) use ($playerName): void{
            var_dump("$playerName has $coins coins!");
        }
    );
}

$this->dumpCoins("Jibix YT"); //Output: "Jibix YT has 100 coins!"
```