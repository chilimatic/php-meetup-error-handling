<?php
declare(strict_types=1);

class Result extends ArrayObject {
    private const ERROR = 'error';
    private const SUCCESS = 'success';

    /**
     * Result constructor.
     * @param $success
     * @param $error
     */
    public function __construct($success = null, $error = null)
    {
        parent::__construct();

        $this->__set(self::SUCCESS, $success);
        $this->__set(self::ERROR, $error);
    }


    public function __get(string $name) {
        if (!$this->isValidAccess($name)) {
            return null;
        }

        return $this[$name];
    }

    /**
     * @param string $name
     * @param $value
     */
    public function __set(string $name, $value)
    {
        if (!$this->isValidAccess($name)) {
            return;
        }

        $this[$name] = $value;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset(string $name)
    {
        if (!$this->isValidAccess($name)) {
            return false;
        }

        return isset($this[$name]);
    }

    /**
     * @param string $name
     * @return bool
     */
    private function isValidAccess(string $name): bool {
        return $name === self::ERROR || $name === self::SUCCESS;
    }

    /**
     * @return bool
     */
    public function hasSuccess(): bool {
        return !empty($this->__get(self::SUCCESS));
    }

    /**
     * @return bool
     */
    public function hasError(): bool {
        return !empty($this->__get(self::ERROR));
    }

    /**
     * @return mixed|null
     */
    public function unwrap() {
        return $this->__get(self::SUCCESS);
    }

    /**
     * @return mixed|null
     */
    public function unwrapError() {
        return $this->__get(self::ERROR);
    }

    /**
     * @param $error
     * @return AbstractResult
     */
    public static function error($error): AbstractResult {
        return new AbstractResult(null, $error);
    }

    /**
     * @param $success
     * @return AbstractResult
     */
    public static function success($success): AbstractResult {
        return new AbstractResult($success, null);
    }
}

class Handler {

    public function handle(int $numberInput): AbstractResult {
        if ($numberInput > 0) {
            return AbstractResult::error('omg');
        }

        return AbstractResult::success('yaay');
    }

}

class ComplexHandler {

    public function handle(): AbstractResult {
        $result = (new Handler())->handle(0);

        if ($result->hasError()) {
            return AbstractResult::error($result);
        }

        return AbstractResult::success($result->unwrap());
    }

}

$handler = new Handler();
$result1 = $handler->handle(1);
var_dump($result1);

['success' => $success, 'error' => $error] = $result1;
var_dump($success);

$handler = new ComplexHandler();
$result2 = $handler->handle();
var_dump($result2);

['success' => $success, 'error' => $error] = $result1;
var_dump($success);


