<?php
namespace Jibix\AsyncMedoo\task;
use Closure;
use pocketmine\scheduler\AsyncTask;


/**
 * Class AsyncClosureTask
 * @package Jibix\AsyncMedoo\task
 * @author Jibix
 * @date 26.05.2023 - 01:04
 * @project AsyncMedoo
 */
class AsyncClosureTask extends AsyncTask{

    public function __construct(
        private Closure $onRun,
        private ?Closure $onComplete = null,
        private ?Closure $onError = null,
        array $storeLocal = [],
    ){
        foreach ($storeLocal as $key => $value) {
            $this->storeLocal($key, $value);
        }
        $this->storeLocal("locals", array_keys($storeLocal));
    }

    public function onRun(): void{
        $result = ($this->onRun)();
        if (!is_object($result)) $this->setResult($result);
    }

    public function onCompletion(): void{
        if ($this->onComplete !== null) {
            $locals = [];
            foreach ($this->fetchLocal("locals") as $local) {
                $locals[$local] = $this->fetchLocal($local);
            }
            ($this->onComplete)($this->getResult(), $locals);
        }
    }

    public function onError(): void{
        $this->onError?->__invoke();
    }
}