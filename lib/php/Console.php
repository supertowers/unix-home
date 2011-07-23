<?php

class Console
{
    private static $instance;

    private $currentBuffer = '';

    /**
     * Defines if the console has started writting data to the user.
     * 
     * @var boolean
     */
    private $outputing = FALSE;

    private static $inResource = STDIN;

    /**
     * Singleton method.
     * 
     * @author Pablo Lopez Torres <pablolopeztorres@gmail.com> 
     * @return Console
     */
    public static function i()
    {
        if (self::$instance === NULL)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Class constructor made private for assuring singletoness.
     * 
     * @author Pablo Lopez Torres <pablolopeztorres@gmail.com> 
     * @return void
     */
    private function __construct()
    {

    }

    public function getChar($hidden = FALSE)
    {
        $correct = TRUE;

        do {
            if ($hidden)
            {
                system('stty -echo');
            }
            system('stty -icanon');
            $char = fgetc(self::$inResource);
            system('stty icanon');
            if ($hidden)
            {
                system('stty echo');
            }

            if (ord($char) === 12) // CTRL+L (clean screen)
            {
                $this->clearScreen();
                $char = NULL;
            }
        } while ($char === NULL);

        return $char;
    }

    public function getLine()
    {
        $line = fgets(self::$inResource, 4096);
        return trim($line);
    }

    public function outl($data = '')
    {
        $this->currentBuffer = $data;
        return $this->out($data . "\r\n");
    }

    public function out($data)
    {
        $this->outputing = TRUE;

        $this->currentBuffer .= $data;
        print($data);
    }

    public function clearScreen()
    {
        system('tput clear');
        $currentBuffer = $this->getCurrentBuffer();
        $this->clearCurrentBuffer();
        $this->out($currentBuffer);

    }

    private $colorCodes = array(
        'gray' => 30,
        'red' => 31,
        'green' => 32,
        'yellow' => 33,
        'blue' => 34,
        'magenta' => 35,
        'cyan' => 36,
        'white' => 37,
    );

    public function color($color = 'reset', $flag = 'light')
    {
        switch (strtolower($flag))
        {
            case 'light':
                $modifier = 1;
                break;
            case 'underline':
                $modifier = 4;
                break;
            case 'blink':
                $modifier = 5;
                break;
            default:
                $modifier = 3;
                break;
        }

        $outputValue = '';
        if (isset($this->colorCodes[strtolower($color)]))
        {
            $outputValue = "\033[" . $this->colorCodes[strtolower($color)] . ';' . $modifier .'m';
        }

        switch (strtolower($color))
        {
            case 'reset':
                $outputValue = "\033[0m";
                break;
        }

        return $outputValue;
    }

    public function getChoice($choiceList) {
        if (! is_array($choiceList))
        {
            throw new Exception('$choiceList is not an array');
        }
        assert('! empty($choiceList)');

        $outputPrint = TRUE;

        $prevTyped = '';
        $found = NULL;
        while (! $found)
        {
            $char = '';
            if (count($choiceList) > 1)
            {
                $char = $this->getChar(TRUE);
            }

            if (ord($char) === 27) // ESC
            {
                return NULL;
            }

            $tabMode = FALSE;
            if (ord($char) === 9) // TAB
            {
                $tabMode = TRUE;
            }
            $detections = array();

            if (ord($char) === 10) // ENTER
            {
                $typed = $tabMode ? $prevTyped : $prevTyped;

                foreach ($choiceList as $choice)
                {
                    if ($choice == $typed)
                    {
                        $detections[] = $choice;
                        break;
                    }
                }
            }
            else
            {
                $typed = $tabMode ? $prevTyped : $prevTyped . $char;

                foreach ($choiceList as $choice)
                {
                    if (strpos(' ' . $choice, ' ' . $typed) === 0)
                    {
                        $detections[] = $choice;
                    }
                }
            }


            if ($tabMode)
            {
                $line = $this->getCurrentBuffer();

                $this->outl();
                foreach ($detections as $detection)
                {
                    $this->outl($detection);
                }
                $this->clearCurrentBuffer();

                $this->out($line);
            }
            else
            {
                if (count($detections) === 0)
                {
                    // do something, play a song or similar
                }
                else
                {
                    $prevTyped = $typed;
                    $output = $this->color('cyan') . $char . $this->color();

                    if (ord($char) === 13)
                    {
                        die('aaaa');
                    }


                    $this->out($output);
                    if (count($detections) == 1)
                    {
                        $found = current($detections);
                        $this->out(substr($found, strlen($typed)));
                        break;
                    }
                }
            }

        }
        return $found;
    }

    public function getCurrentBuffer() {
        return $this->currentBuffer;
    }

    public function clearCurrentBuffer() {
        $this->currentBuffer = '';
    }


    /**
     * hasStartedOutputing
     * 
     * @author Pablo Lopez Torres <pablolopeztorres@gmail.com> 
     * @return void
     */
    public function hasStartedOutputing()
    {
        return $this->outputing;
    }

    public function endProgram($returnValue = 0)
    {
        exit();
    }

    public function setProgram (CommandLineProgram $program) {
        $this->program = $program;
    }
}

