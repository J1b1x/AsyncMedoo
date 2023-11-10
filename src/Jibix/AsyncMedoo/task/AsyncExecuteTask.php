<?php
namespace Jibix\AsyncMedoo\task;
use Closure;
use Jibix\AsyncMedoo\MySQLCredentials;
use pocketmine\scheduler\AsyncTask;
use ReflectionFunction;


/**
 * Class AsyncExecuteTask
 * @package Jibix\AsyncMedoo\task
 * @author Jibix
 * @date 07.11.2023 - 18:01
 * @project AsyncMedoo
 */
class AsyncExecuteTask extends AsyncTask{

    private string $credentials;

    public function __construct(
        MySQLCredentials $credentials,
        private Closure $task,
        ?Closure $onComplete = null,
        ?Closure $onError = null,
    ){
        $this->credentials = json_encode($credentials);
        $this->storeLocal("onComplete", $onComplete ?? fn () => null); //would throw an exception if $onComplete was null
        $this->storeLocal("onError", $onError ?? fn () => null);
    }

    public function onRun(): void{
        $task = $this->task;
        if ((new ReflectionFunction($task))->getNumberOfParameters() == 0) {
            $result = ($task)();
        } else {
            $result = ($task)($medoo = MySQLCredentials::fromString($this->credentials)->createConnection());
            $medoo->pdo = null;
        }
        if (!is_object($result)) $this->setResult($result);
    }

    public function onCompletion(): void{
        $this->fetchLocal("onComplete")?->__invoke($this->getResult());
    }

    public function onError(): void{
        $this->fetchLocal("onError")?->__invoke();
    }
}