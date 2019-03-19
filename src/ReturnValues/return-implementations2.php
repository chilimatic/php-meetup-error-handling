<?php
declare(strict_types=1);

interface IAmResult {}

abstract class AbstractResult implements ArrayAccess, Iterator, IAmResult {
    protected $success;
    protected $error;

    private $pos = 0;

    abstract public function unwrap();
    abstract public function unwrapError();

    public function offsetExists($offset)
    {
        return $offset <= 1;
    }

    public function offsetGet($offset)
    {
        if ($offset === 0) {
            return $this->success;
        }

        return $this->error;
    }

    public function offsetSet($offset, $value)
    {
        if ($offset === 0) {
            return $this->success = $value;
        }

        return $this->error = $value;
    }

    public function offsetUnset($offset)
    {
        if ($offset === 0) {
            return $this->success = null;
        }

        return $this->error = null;
    }

    /**
     * @return bool
     */
    public function hasSuccess(): bool {
        return !empty($this->success);
    }

    /**
     * @return bool
     */
    public function hasError(): bool {
        return !empty($this->error);
    }

    public function current()
    {
        if ($this->pos === 0) {
            return $this->success;
        }

        return $this->error;
    }

    public function next()
    {
        $pos = $this->pos;
        $this->pos++;
        if ($this->pos === 0) {
            return $this->success;
        }

        return $this->error;
    }

    public function key()
    {
        return $this->pos;
    }

    public function valid()
    {
        return $this->pos < 2;
    }

    public function rewind()
    {
        $this->pos = 0;
    }
}

class ExplicitResult extends AbstractResult {
    /**
     * Result constructor.
     * @param $success
     * @param $error
     */
    public function __construct(?int $success, ?string $error)
    {
        $this->success = $success;
        $this->error = $error;
    }

    /**
     * @param $error
     * @return IAmResult
     */
    public static function error(string $error): IAmResult {
        return new ExplicitResult(null, $error);
    }

    /**
     * @param $success
     * @return IAmResult
     */
    public static function success(int $success): IAmResult {
        return new ExplicitResult($success, null);
    }

    /**
     * @return mixed|null
     */
    public function unwrap() {
        return $this->success;
    }

    /**
     * @return mixed|null
     */
    public function unwrapError() {
        return $this->error;
    }
}

class GenericResult extends AbstractResult {

    /**
     * Result constructor.
     * @param $success
     * @param $error
     */
    public function __construct($success, $error)
    {
        $this->success = $success;
        $this->error = $error;
    }

    /**
     * @param $error
     * @return AbstractResult
     */
    public static function error($error): IAmResult {
        return new GenericResult(null, $error);
    }

    /**
     * @param $success
     * @return AbstractResult
     */
    public static function success($success): IAmResult {
        return new GenericResult($success, null);
    }

    public function unwrap()
    {
        return $this->success;
    }

    public function unwrapError()
    {
        return $this->error;
    }


}


class Handler {

    public function handle(AbstractResult $result): AbstractResult {
        if ($result->hasError()) {
            return $result;
        }

        if ($result->unwrap() > 0) {
            return GenericResult::error('omg');
        }

        return GenericResult::success('yaay');
    }

}

class ComplexHandler {

    public function handle(): AbstractResult {
        $result = (new Handler())->handle(GenericResult::success(1));

        if ($result->hasError()) {
            return GenericResult::error($result);
        }

        return GenericResult::success($result->unwrap());
    }

}

$handler = new Handler();
$result1 = $handler->handle(GenericResult::success(1));
var_dump($result1);

['success' => $success, 'error' => $error] = $result1;
var_dump($success);

$handler = new ComplexHandler();
$result2 = $handler->handle();
//var_dump($result2);

['success' => $success, 'error' => $error] = $result1;
//var_dump($success);