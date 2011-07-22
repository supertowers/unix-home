<?php

abstract class CommandLineProgram
{
    /**
     * getPrompt
     * 
     * @author Pablo Lopez Torres <pablolopeztorres@gmail.com> 
     * @return void
     */
    abstract protected function getPrompt();

    /**
     * getParamOptions
     * 
     * @author Pablo Lopez Torres <pablolopeztorres@gmail.com> 
     * @return void
     */
    abstract protected function getParamOptions();

    /**
     * run
     * 
     * @author Pablo Lopez Torres <pablolopeztorres@gmail.com> 
     * @return void
     */
    abstract protected function run($arguments, $params);

    private static function parseArgs($args, $options)
    {
        $argumentsToParse = $args;
        $parsedOptions = array();
        foreach ($options as $optionKey => $option)
        {
            foreach ($option as $value)
            {
                $parsedOptions[$value] = $optionKey;
            }
        }

        $params = array();
        while (! empty($argumentsToParse)) {

            $arg = array_shift($argumentsToParse);
            if (isset($parsedOptions[$arg]))
            {
                $parsedOption = $parsedOptions[$arg];
                switch ($parsedOption[0])
                {
                    case '+':
                        $params[substr($parsedOption, 1)] = TRUE;
                        break;
                    case '-':
                        $params[substr($parsedOption, 1)] = FALSE;
                        break;
                    default:
                        if (empty($argumentsToParse))
                        {
                            throw new UnableToParseArgs($args, $options);
                        }
                        $params[$parsedOption] = array_shift($argumentsToParse);
                        break;
                }
            }
            else
            {
                $arguments[] = $arg;
            }
        }

        return array($arguments, $params);
    }

    public static function main($className, $argv)
    {
        $p = new $className();
        $c = Console::i();
        $c->setProgram($p);

        list($arguments, $params) = self::parseArgs($argv, $p->getParamOptions());

        $p->run($arguments, $params);
    }
}

class UnableToParseArgs extends Exception
{
    private static $customMessage = "Unable to parse arguments: %s with options: %s";

    public function __construct($arguments, $options) {
        parent::__construct(sprintf(self::$customMessage, json_encode($arguments), json_encode($options)));
    }
}
