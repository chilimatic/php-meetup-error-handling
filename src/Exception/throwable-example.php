<?php
declare(strict_types=1);

trait HandleThrowableTrait {
    public function handleThrowable(\Throwable $t) {
        switch (true) {
            case \InvalidArgumentException::class instanceof $t:
                break;
            case \LogicException::class instanceof $t:
                break;
            case \RuntimeException::class instanceof $t:
                break;
            case \Error::class instanceof $t:
                break;
            case \Exception::class instanceof $t:
                break;
            case \Throwable::class instanceof $t:
                break;
        }

        $this->logThrowable($t);
    }

    private function logThrowable($t) {
        var_dump($t);
    }
}


class SomeBusinessLogicFacade {
    use HandleThrowableTrait;

    public function action1(): void {
        try {
            $var = 1;
            if ($var === 1) {
                throw new LogicException('Var is one');
            }

        } catch (\Throwable $t) {
            $this->handleThrowable($t);
        }
    }

    public function action2(): void {
        try {
          $this->littleHelper("test");

        } catch (\Throwable $t) {
            $this->handleThrowable($t);
        }
    }

    private function littleHelper(int $i): int {
        return $i;
    }

}

$test = new SomeBusinessLogicFacade();
$test->action1();
$test->action2();