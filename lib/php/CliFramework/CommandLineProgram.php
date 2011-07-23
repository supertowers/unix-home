<?php

namespace CliFramework;

use \Exception;

abstract class CommandLineProgram
{
    /**
     * getParamOptions
     * 
     * @author Pablo Lopez Torres <pablolopeztorres@gmail.com> 
     * @return void
     */
    abstract protected function getParamOptions();

    /**
     * getVersion
     * 
     * @author Pablo Lopez Torres <pablolopeztorres@gmail.com> 
     * @return string
     */
    abstract protected function getVersion();
    /**
     * getHelp
     * 
     * @author Pablo Lopez Torres <pablolopeztorres@gmail.com> 
     * @return void
     */
    abstract protected function getHelp($arguments, $params);

    /**
     * run
     * 
     * @author Pablo Lopez Torres <pablolopeztorres@gmail.com> 
     * @param mixed $arguments 
     * @param mixed $params 
     * @return void
     */
    abstract protected function run($arguments, $params);


    private static function parseArgs($argv, $options)
    {
        $argumentsToParse = $argv;
        $parsedOptions = array();
        $params = array();
        $arguments = array();

        foreach ($options as $optionKey => $optionValues)
        {
            if ($optionKey[0] === '-' || $optionKey[0] === '+')
            {
                $params[substr($optionKey, 1)] = FALSE;
            }
            else
            {
                $params[substr($optionKey, 1)] = NULL;
            }
            foreach ($optionValues as $value)
            {
                $parsedOptions[$value] = $optionKey;
            }
        }

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
                            throw new UnableToParseArgs($argv, $options);
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

        try
        {
            $p->run($arguments, $params);
        }
        catch (CommandLineException $e)
        {
            $outputing = $c->hasStartedOutputing();
            $c->outl($c->color('gray') . "[" . $c->color('red') . " ERROR " . $c->color('gray') . "] " . $c->color() . $e->getMessage());
            if (! $outputing)
            {
                $c->outl($p->getHelp($arguments, $params));
            }
            $c->endProgram(2);
        }
    }
}

abstract class CommandLineException extends Exception
{
}

class UnableToParseArgs extends Exception
{
    private static $customMessage = "Unable to parse arguments: %s with options: %s";

    public function __construct($arguments, $options) {
        parent::__construct(sprintf(self::$customMessage, json_encode($arguments), json_encode($options)));
    }
}
