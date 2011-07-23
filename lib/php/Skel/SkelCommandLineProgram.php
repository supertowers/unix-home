#!/usr/bin/php
<?php

namespace Skel;

require_once dirname(__DIR__) . '/bootstrap.php';

use \Cli\CommandLineProgram;
use \Cli\Console;

/**
 * Example Command Line Program 
 * 
 * @uses CommandLineProgram
 * @author Pablo Lopez Torres <pablolopeztorres@gmail.com> 
 */
class SkelCommandLineProgram extends CommandLineProgram {

    /**
     * getParamOptions
     * 
     * @author Pablo Lopez Torres <pablolopeztorres@gmail.com> 
     * @return void
     */
    protected function getParamOptions()
    {
        return array(
            '+help' => array('--help', '-h'),
            '+version' => array('--version', '-v'),
        );
    }

    /**
     * run
     * 
     * @author Pablo Lopez Torres <pablolopeztorres@gmail.com> 
     * @return void
     */
    protected function run($arguments, $params)
    {
        $c = Console::i();

        // parse params
        if ($params['help'] === TRUE)
        {
            $c->outl($this->getHelp($arguments, $params));
            $c->endProgram(1);
        }

        if ($params['version'] === TRUE)
        {
            $c->outl('VERSION: ' . $this->getVersion());
            $c->endProgram(1);
        }

        // do here stuff
        $colors = array('red', 'grey', 'blue', 'cyan', 'magenta', 'yellow');
        foreach (str_split('HELLO WORLD') as $char)
        {
            shuffle($colors);
            $c->out($c->color($colors[0], 'blink') . $char);
        }
        $c->outl($c->color());
    }

    protected function getHelp($arguments, $params)
    {
        return 'Usage: ' . $arguments[0] . ' [-h|--help] [-v|--version]';
    }

    protected function getVersion()
    {
        return 0.1;
    }
}

CommandLineProgram::main('\Skel\SkelCommandLineProgram', $argv);
