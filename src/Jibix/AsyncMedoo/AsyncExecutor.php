<?php
namespace Jibix\AsyncMedoo;
use Closure;
use Jibix\AsyncMedoo\task\AsyncExecuteTask;
use pocketmine\Server;
use ReflectionFunction;
use function Jibix\AsyncMedoo\util\async;


/**
 * Class AsyncExecutor
 * @package Jibix\AsyncMedoo
 * @author Jibix
 * @date 26.05.2023 - 00:55
 * @project AsyncMedoo
 */
final class AsyncExecutor{

    public static function execute(Closure $task, ?Closure $onComplete = null, ?Closure $onError = null, ?MySQLCredentials $credentials = null): void{
        $credentials ??= AsyncMedoo::getCredentials();
        if (!Server::getInstance()->isRunning()) {
            $result = (new ReflectionFunction($task))->getNumberOfParameters() == 0 ? ($task)() : ($task)($credentials->createConnection());
            if ($onComplete !== null && !is_object($result)) ($onComplete)($result);
            return;
        }
        async(new AsyncExecuteTask($credentials, $task, $onComplete, $onError));
    }
}