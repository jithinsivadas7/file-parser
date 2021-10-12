<?php

/**
 * Command line argument handler
 */
class PraseArguments
{
    private $arguments;
    private $parsed;
    public function __construct(array $argv = null)
    {
        $this->arguments = $argv ?? [];
        $this->parse();
    }

    /**
     * parse individual inputs
     */
    public function parse(): void
    {
        if (count($this->arguments) <= 1) {
            throw new Exception('Missing Parser Input');
        }
        while (null !== $parameter = array_shift($this->arguments)) {
            if (substr($parameter, 0, 2) === '--' &&  strpos($parameter, '=') === FALSE) {
                $this->parseParameter($parameter);
            } else if (substr($parameter, 0, 2) === '--') {
                $this->parseValue($parameter);
            }
        }
    }

    /**
     * Prase parameter having value along with the input
     * @string $parameter
     */
    private function parseValue(String $parameter): void
    {
        $name = substr($parameter, 2);
        list($name, $parameter) = explode('=', $name);

        $this->pushArgument($name, $parameter);
    }

    /**
     * Prase parameter having value as separate argument
     * @string $parameter
     */
    private function parseParameter(String $parameter): void
    {
        $name = substr($parameter, 2);
        if (strpos($name, '=') !== FALSE) {
            list($name, $parameter) = explode('=', $name);
        } else {
            $parameter = array_shift($this->arguments);
        }
        $this->pushArgument($name, $parameter);
    }

    /**
     * Push Argument to the system
     * @string $key
     * @string $value
     */
    private function pushArgument(String $key, String $value): void
    {
        $this->parsed[$key] = $value;
    }


    /**
     * Get Argument 
     * @string $key
     */

    public function getArgument(String $key): String
    {
        if (isset($this->parsed[$key]) && !empty($this->parsed[$key])) {
            return $this->parsed[$key];
        }
        throw new Exception('Missing Input/Value: ' . $key);
    }
}
