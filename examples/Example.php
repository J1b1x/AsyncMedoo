<?php
use Jibix\AsyncMedoo\AsyncMedoo;
use Jibix\AsyncMedoo\MySQLCredentials;
use Medoo\Medoo;
use function Jibix\AsyncMedoo\util\async;


class Example{

    private const TABLE = "stats";

    public function __construct(){
        //Creating table
        $connection = AsyncMedoo::getCredentials()->createConnection();
        $connection->create(self::TABLE, [
            "name" => ["VARCHAR(32)", "NOT NULL", "PRIMARY KEY"],
            "kills" => ["INTEGER(10)",  "NOT NULL"],
            "deaths" => [  "INTEGER(10)", "NOT NULL"]
        ]);
        $connection->pdo = null;
    }

    //Getting stats
    public function getStats(string $playerName, Closure $onComplete): void{
        async(fn (Medoo $medoo): array => $medoo->get(self::TABLE, ["kills", "deaths"], ["name" => $playerName]), $onComplete);
    }

    //Updating stats
    public function setStats(string $playerName, array $stats, Closure $onComplete): void{
        async(fn (Medoo $medoo) => $medoo->update(self::TABLE, $stats, ["name" => $playerName]), $onComplete);
    }
}

//Initialization
AsyncMedoo::initialize(new MySQLCredentials(
    "ffa",
    "myPassword1234",
));

$example = new Example();
$example->setStats("Jibix YT", ["kills" => 2, "deaths" => 1], function (): void{
    var_dump("Updated stats!");
});
