#!/usr/bin/php
<?php

namespace CoolestTaskManager;

use \CliFramework\CommandLineProgram;
use \CliFramework\Console;
use \CliFramework\CommandLineException;
use \Exception;

require_once __DIR__ . '/../bootstrap.php';

class CoolestTaskManagerProgram extends CommandLineProgram
{
    public function getOptionsTree()
    {
        $optionsTree = array(
            'new' => array(
                'task' => array($this, 'newTask'),
                'label' => array($this, 'notImplemented'),
//              'context' => array($this, 'nonImplemented'),
//              'project' => array($this, 'nonImplemented'),
//              'goal' => array($this, 'nonImplemented'),
            ),
//          'modify' => array(
//              'task' => array($this, 'nonImplemented'),
//              'label' => array($this, 'nonImplemented'),
//              'context' => array($this, 'nonImplemented'),
//              'project' => array($this, 'nonImplemented'),
//              'goal' => array($this, 'nonImplemented'),
//          ),
            'complete' => array(
                'task' => array($this, 'completeTask'),
            ),
            'work in' => array(
                'task' => array($this, 'workInTask'),
            ),
            'cancel' => array(
                'task' => array($this, 'cancelTask'),
            ),
            'export' => array(
                'tasks' => array($this, 'exportTasks'),
            ),
            'close' => array(
                'task' => array($this, 'closeTask'),
            ),
            'delete' => array(
                'task' => array($this, 'deleteTask'),
                'label' => array($this, 'notImplemented'),
//              'context' => array($this, 'nonImplemented'),
//              'project' => array($this, 'nonImplemented'),
//              'goal' => array($this, 'nonImplemented'),
            ),
            'list' => array(
                'tasks' => array($this, 'listTasks'),
                'labels' => array($this, 'notImplemented'),
                'closed tasks' => array($this, 'listClosedTasks'),
//              'contexts' => array($this, 'nonImplemented'),
//              'projects' => array($this, 'nonImplemented'),
//              'goals' => array($this, 'nonImplemented'),
            ),
            'quit' => array(
                'program' => array($this, 'quit'),
            ),
        );
        return $optionsTree;
    }


    protected function run($arguments, $params)
    {
        if (count($arguments) !== 1) // program name
        {
            throw new WrongParametersException();
        }

        $c = Console::i();
        $ot = $this->getOptionsTree();

        $configPath = getenv('HOME');
        $configFile = getenv('HOME') . '/' . '.tmrc';

        if (file_exists($configFile)) {
            $configPath = trim(file_get_contents($configFile));
        }

        if (isset($params['config_path']))
        {
            $configPath = $params['config_path'];
            if (! is_dir($configPath))
            {
                throw new InvalidConfigPathException('Invalid config path: ' . $configPath);
            }
            file_put_contents($configFile, $configPath);
        }

        $c->outl($c->color('blue') . "Using database: " . $c->color('gray') . $configPath . $c->color());
        $c->clearCurrentBuffer();
        // decuple from globals please
        $GLOBALS['task_manager_config_path'] = $configPath;

        while (TRUE)
        {
            $c->out($this->getPrompt());
            $action = $c->getChoice(array_keys($ot));
            if ($action !== NULL)
            {
                $c->out(" ");
                $element = $c->getChoice(array_keys($ot[$action]));
                if ($element !== NULL)
                {
                    $c->outl();
                    call_user_func($ot[$action][$element]);
                }
                else
                {
                    $c->outl();
                }
            }
            else
            {
                $c->outl();
            }
        }
    }

    public function nonImplemented()
    {
        $c = Console::i();
        $c->outl('SORRY: Not implemented yet');
        $c->clearCurrentBuffer();
    }

    public function quit()
    {
        die();
    }

    //
    // Commands to be called
    //
    public function newTask()
    {
        $manager = TaskManager::i();
        $c = Console::i();
        $c->out($c->color('blue') . "Name: " . $c->color());
        $name = $c->getLine();
        $c->clearCurrentBuffer();

        // $labels = $manager->getLabels();
        // while (TRUE)
        // {
        //     $c->out($c->color('blue') . "Label: " . $c->color());
        //     $choice = $c->getChoice($labels);
        //     $c->clearCurrentBuffer();
        //     if ($choice == '')
        //     {
        //         break;
        //     }
        //     $choices[] = $choice;
        // }
        // $c->outl();

        if (trim($name) !== '')
        {
            $task = new Task(trim($name));
            $id = $manager->addTask($task);
            $c->outl($c->color('gray') . "Task " . $id . " created" . $c->color());
        }
        else
        {
            $c->outl($c->color('red') . "ERROR: " . $c->color() . "No name provided");
        }
    }

    public function completeTask()
    {
        $manager = TaskManager::i();
        $tasks = $manager->getTasks();

        $c = Console::i();

        if (empty($tasks))
        {
            $c->outl($c->color('gray') . "There are no tasks." . $c->color());
            $c->clearCurrentBuffer();
            return;
        }

        $c->out($c->color('blue') . "Id: " . $c->color());
        $choice = $c->getChoice(array_keys($tasks));
        $c->clearCurrentBuffer();
        $c->outl();

        if (trim($choice) !== '')
        {
            $task = $tasks[$choice];

            $c->out($c->color('blue') . "SELECTED TASK: " . $c->color());
            $c->outl($c->color('white') . "($choice) " . $c->color() . $task->getName());
            $c->outl();
            $manager->completeTask($choice);
            $c->outl($c->color('gray') . "Task " . $choice . " completed" . $c->color());
            $c->clearCurrentBuffer();
        }
        else
        {
            $c->outl($c->color('red') . "ERROR: " . $c->color() . "No id provided");
            $c->clearCurrentBuffer();
        }
    }

    public function workInTask()
    {
        $manager = TaskManager::i();
        $tasks = $manager->getTasks();
        $c = Console::i();

        if (empty($tasks))
        {
            $c->outl($c->color('gray') . "There are no tasks." . $c->color());
            $c->clearCurrentBuffer();
            return;
        }

        $c->out($c->color('blue') . "Id: " . $c->color());
        $choice = $c->getChoice(array_keys($tasks));
        $c->clearCurrentBuffer();
        $c->outl();

        if (trim($choice) !== '')
        {
            $task = $tasks[$choice];

            $c->out($c->color('blue') . "SELECTED TASK: " . $c->color());
            $c->outl($c->color('white') . "($choice) " . $c->color() . $task->getName());
            $c->clearCurrentBuffer();
            $c->out($c->color('blue') . "Are sure you wanna work again on it? " . $c->color() . "[yes/no] " . $c->color());

            if ($c->getChoice(array('yes', 'no')) == 'yes')
            {
                $c->clearCurrentBuffer();
                $c->outl();
                $manager->workInTask($choice);
                $c->outl($c->color('gray') . "Task " . $choice . " is in process again" . $c->color());
            }
            else
            {
                $c->clearCurrentBuffer();
                $c->outl();
                $c->outl($c->color('gray') . "Undoing the operation " . $c->color());
                $c->clearCurrentBuffer();
            }
        }
        else
        {
            $c->outl($c->color('red') . "ERROR: " . $c->color() . "No id provided");
            $c->clearCurrentBuffer();
        }
    }

    public function cancelTask()
    {
        $manager = TaskManager::i();
        $tasks = $manager->getTasks();

        $c = Console::i();

        if (empty($tasks))
        {
            $c->outl($c->color('gray') . "There are no tasks." . $c->color());
            $c->clearCurrentBuffer();
            return;
        }

        $c->out($c->color('blue') . "Id: " . $c->color());
        $choice = $c->getChoice(array_keys($tasks));
        $c->clearCurrentBuffer();
        $c->outl();

        if (trim($choice) !== '')
        {
            $task = $tasks[$choice];

            $c->out($c->color('blue') . "SELECTED TASK: " . $c->color());
            $c->outl($c->color('white') . "($choice) " . $c->color() . $task->getName());
            $manager->cancelTask($choice);
            $c->outl($c->color('gray') . "Task " . $choice . " was cancelled" . $c->color());
            $c->clearCurrentBuffer();
        }
        else
        {
            $c->outl($c->color('red') . "ERROR: " . $c->color() . "No id provided");
            $c->clearCurrentBuffer();
        }
    }

    public function deleteTask()
    {
        $c = Console::i();
        $manager = TaskManager::i();
        $tasks = $manager->getAllTasks();

        if (empty($tasks))
        {
            $c->outl($c->color('gray') . "There are no tasks." . $c->color());
            $c->clearCurrentBuffer();
            return;
        }

        $c->out($c->color('blue') . "Id: " . $c->color());
        $choice = $c->getChoice(array_keys($tasks));
        $c->clearCurrentBuffer();
        $c->outl();

        if (trim($choice) !== '')
        {
            $task = $tasks[$choice];

            $c->out($c->color('blue') . "SELECTED TASK: " . $c->color());
            $c->outl($c->color('white') . "($choice) " . $c->color() . $task->getName());
            $c->clearCurrentBuffer();
            $c->out($c->color('red') . "Are sure you wanna delete it? " . $c->color() . "[yes/no] " . $c->color());

            if ($c->getChoice(array('yes', 'no')) == 'yes')
            {
                $c->clearCurrentBuffer();
                $c->outl();
                $manager->deleteTask($choice);
                $c->outl($c->color('gray') . "Task " . $choice . " deleted" . $c->color());
            }
            else
            {
                $c->clearCurrentBuffer();
                $c->outl();
                $c->outl($c->color('gray') . "Undoing the operation " . $c->color());
                $c->clearCurrentBuffer();
            }
        }
        else
        {
            $c->outl($c->color('red') . "ERROR: " . $c->color() . "No id provided");
            $c->clearCurrentBuffer();
        }
    }

    public function closeTask()
    {
        $c = Console::i();
        $manager = TaskManager::i();
        $tasks = $manager->getTasks();

        if (empty($tasks))
        {
            $c->outl($c->color('gray') . "There are no tasks" . $c->color());
            $c->clearCurrentBuffer();
            return;
        }

        $c->out($c->color('blue') . "Id: " . $c->color());
        $choice = $c->getChoice(array_keys($tasks));
        $c->clearCurrentBuffer();
        $c->outl();

        if (trim($choice) !== '')
        {
            $task = $tasks[$choice];

            if (! $task->isCompleted() && ! $task->isCancelled())
            {
                $c->outl($c->color('gray') . "The task should be " . $c->color('grey', 'light') . "completed" . $c->color('grey') . " or " . $c->color('grey', 'light') . "cancelled" . $c->color('grey') . " for closing it" . $c->color());
                $c->clearCurrentBuffer();
                return;
            }

            $c->out($c->color('blue') . "SELECTED TASK: " . $c->color());
            $c->outl($c->color('white') . "($choice) " . $c->color() . $task->getName());
            $c->clearCurrentBuffer();

            $c->outl();
            $manager->closeTask($choice);
            $c->outl($c->color('gray') . "Task " . $choice . " closed" . $c->color());
            $c->clearCurrentBuffer();
        }
        else
        {
            $c->outl($c->color('red') . "ERROR: " . $c->color() . "No id provided");
            $c->clearCurrentBuffer();
        }
    }


    public function listTasks()
    {
        $c = Console::i();

        $c->outl($c->color('blue') . " TASKS " . $c->color());
        $c->outl($c->color('blue') . "=======" . $c->color());

        $tasks = TaskManager::i()->getTasks();
        foreach ($tasks as $id => $task)
        {
            if ($task->isCompleted())
            {
                $taskData = $c->color('green') . "($id) " . $c->color() . $task->getName();
            }
            else if ($task->isCancelled())
            {
                $taskData = $c->color('red') . "($id) " . $c->color() . $task->getName();
            }
            else if ($task->isWorkingOn())
            {
                $taskData = $c->color('yellow') . "($id) " . $c->color() . $task->getName();
            }
            else
            {
                $taskData = $c->color('white') . "($id) " . $c->color() . $task->getName();
            }
            $c->outl($taskData);
        }
        $c->clearCurrentBuffer();
    }

    public function listClosedTasks()
    {
        $c = Console::i();

        $c->outl($c->color('blue') . " TASKS " . $c->color());
        $c->outl($c->color('blue') . "=======" . $c->color());

        $tasks = TaskManager::i()->getAllTasks();
        foreach ($tasks as $id => $task)
        {
            if ($task->isCompleted())
            {
                $taskData = $c->color('green') . "($id) " . $c->color() . $task->getName();
            }
            else if ($task->isCancelled())
            {
                $taskData = $c->color('red') . "($id) " . $c->color() . $task->getName();
            }
            else if ($task->isWorkingOn())
            {
                $taskData = $c->color('yellow') . "($id) " . $c->color() . $task->getName();
            }
            else if ($task->isClosed())
            {
                $taskData = $c->color('gray') . "($id) " . $task->getName() . $c->color();
            }
            else
            {
                $taskData = $c->color('white') . "($id) " . $c->color() . $task->getName();
            }
            $c->outl($taskData);
        }
        $c->clearCurrentBuffer();
    }


    public function exportTasks()
    {
        $c = Console::i();

        $tasks = TaskManager::i()->getAllTasks();
        $tasksData = array();
        foreach ($tasks as $id => $task)
        {
            $tasksData[$id] = $task->toArray();
        }

        $c->outl("<?=\n" . var_export($tasksData, TRUE));

        $c->clearCurrentBuffer();
    }

    public function getPrompt()
    {
        return Console::i()->color('blue') . ' > ' . Console::i()->color();
    }

    //
    // Abstract method implementations
    //
    final protected function getParamOptions()
    {
        return array(
            'config_path' => array('--config', '-c'), // param
            '+verbose' => array('--verbose', '-v'), // flag # NON USED YET
            '-verbose' => array('--silent', '-s'), // flag # NON USED YET
            '+help' => array('--help', '-h'), // flag
            '+version' => array('--version', '-V'), // flag # NON USED YET
        );
    }

    final protected function getVersion()
    {
        return 1.0;
    }

    final protected function getHelp($arguments, $params)
    {
        $c = Console::i();
        return $c->color('blue') . 'Usage: ' . $c->color() . $arguments[0] . ' [(--config|-c) <config-path>] [-h|--help]';
    }

}

class Task
{
    const STATUS_IDLE = 1;
    const STATUS_WORKING = 2;
    const STATUS_COMPLETED = 4;
    const STATUS_CANCELLED = 8;
    const STATUS_CLOSED = 16;

    private $id;
    private $name;
    private $project = NULL;
    private $contexts = array();
    private $status = self::STATUS_IDLE;
    private $completedDate = NULL;

    public function getId() {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function __construct($name = NULL)
    {
        if ($name !== NULL)
        {
            $this->setName($name);
        }
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function complete()
    {
        $this->completedDate = time();
        $this->status = self::STATUS_COMPLETED;
    }
    public function workOnIt()
    {
        $this->completedDate = NULL;
        $this->status = self::STATUS_WORKING;
    }
    public function cancel()
    {
        $this->completedDate = time();
        $this->status = self::STATUS_CANCELLED;
    }
    public function close()
    {
        $this->closedDate = time();
        $this->status = self::STATUS_CLOSED;
    }
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }
    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }
    public function isWorkingOn()
    {
        return $this->status === self::STATUS_WORKING;
    }
    public function isClosed()
    {
        return $this->status === self::STATUS_CLOSED;
    }

    public function toArray()
    {

        $contextIds = array();
        foreach ($this->contexts as $context)
        {
            $contextIds[] = $context->getId();
        }

        return array(
            'id' => $this->id,
            'name' => $this->name,
            'project' => NULL, // $this->project->getId(),
            'contexts' => $contextIds,
            'status' => $this->status,
            'completedDate' => $this->completedDate,
        );
    }

    public function fromArray($data)
    {
        $contexts = array();
//      foreach ($data['contexts'] as $contextId)
//      {
//          $contexts[$contextId] = ContextManager::get($contextId);
//      }

        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->project = NULL; // ProjectManager::get($data['project'])
        $this->contexts = $contexts;
        $this->status = $data['status'];
        $this->completedDate = $data['completedDate'];
    }
}

class Label
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}
class Context
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}

class Project
{
    private $name;

    public function __construct($name)
    {
        $this->name = $name;
    }
}


interface IDataContainer
{
//    public function getTasks();
//    public function getLabels();

//    public function addTask(Task $task);
//    public function deleteTask(Task $task);

//    public function addLabel(Label $label);
//    public function deleteLabel(Label $label);
}

class DataContainer implements IDataContainer
{
    private $lastTaskId = 0;
    private $tasks;
    private $closedTasks;
    private $labels;
    private $contexts;
    private $projects;

    public function __construct()
    {
        $this->tasks = array();
        $this->closedTasks = array();
        $this->contexts = array();
        $this->projects = array();
    }

    public function getTasks()
    {
        return $this->tasks;
    }

    public function getClosedTasks()
    {
        return $this->closedTasks;
    }

    public function getLabels()
    {
        return $this->labels;
    }


    public function addTask(Task $task)
    {
        if ($task->getId() === NULL)
        {
            $taskId = ++$this->lastTaskId;
            $task->setId($taskId);
        }
        else
        {
            $taskId = $task->getId();
            if ($this->lastTaskId < $taskId)
            {
                $this->lastTaskId = $taskId;
            }
        }

        if ($task->isClosed())
        {
            $this->closedTasks[$taskId] = $task;
        }
        else
        {
            $this->tasks[$taskId] = $task;
        }

        return $taskId;
    }
    public function closeTask($taskId)
    {
        if (! isset($this->tasks[$taskId]))
        {
            throw new TaskNotFoundException($taskId);
        }

        $task = $this->tasks[$taskId];
        unset($this->tasks[$taskId]);
        $task->close();
        $this->closedTasks[$taskId] = $task;

    }
    public function getTask($taskId)
    {
        if (! isset($this->tasks[$taskId]))
        {
            throw new TaskNotFoundException($taskId);
        }

        return $this->tasks[$taskId];
    }

    public function deleteTask($taskId)
    {
        if (! isset($this->tasks[$taskId]) && ! isset($this->closedTasks[$taskId]))
        {
            throw new TaskNotFoundException($taskId);
        }

        if (isset($this->tasks[$taskId]))
        {
            unset($this->tasks[$taskId]);
        }
        else
        {
            unset($this->closedTasks[$taskId]);
        }
    }


    public function addLabel(Label $label)
    {
        throw new NotImplemented();
    }
    public function getLabel($labelId)
    {
        if (! isset($this->labels[$labelId]))
        {
            throw new LabelNotFoundException($labelId);
        }

        return $this->labels[$labelId];
    }
    public function deleteLabel(Label $label)
    {
        throw new NotImplemented();
    }
}

class TaskManager
{
    private static $instance;

    public static function i()
    {
        if (self::$instance === NULL)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private $dataContainerClassName = '\CoolestTaskManager\DataContainer';
    private $dataContainer;

    private $configPath = NULL;
    private $configFile = '.tasks';

    private $loaded = FALSE;

    private function load()
    {
        if (! $this->loaded)
        {
            $this->loadContainerFromFile($this->getConfigPath() . "/" . $this->configFile);
            $this->loaded = TRUE;
        }
    }

    private function save()
    {
        $this->saveContainerToFile($this->getConfigPath() . "/" . $this->configFile);
    }

    private function loadContainerFromFile($path)
    {
        $className = $this->dataContainerClassName;
        if (! class_exists($className))
        {
            throw new InvalidDataContainerClassName($className);
        }
        $this->dataContainer = new $className();

        if (! $this->dataContainer instanceof IDataContainer)
        {
            throw new ImplementationException();
        }

        if (file_exists($path))
        {
            $data = include($path);
            $tasksData = $data['tasks'];

            foreach ($tasksData as $id => $taskData)
            {
                $task = new Task();
                $task->fromArray($taskData);
                $this->dataContainer->addTask($task);
            }
        }
    }
    private function saveContainerToFile($path)
    {
        $tasks = $this->dataContainer->getTasks() + $this->dataContainer->getClosedTasks();
        $tasksData = array();
        foreach ($tasks as $id => $task)
        {
            $tasksData[$id] = $task->toArray();
        }

        $data = array(
            'tasks' => $tasksData,
        );

        $phpFileData = var_export($data, TRUE);
        $phpFileData = preg_replace("/array \(\n/", "array(\n", $phpFileData);
        $phpFileData = preg_replace("/=> \n +/", "=> ", $phpFileData);

        file_put_contents($path, "<?php return " . $phpFileData . ";");
    }


    public function getConfigPath() {
        if ($this->configPath === NULL)
        {
            // decuple from globals please
            if (! isset($GLOBALS['task_manager_config_path']))
            {
                throw new NotConfiguredTaskManagerException('Please set the config path in $GLOBALS["task_manager_config_path"] before using it');
            }
            $this->configPath = $GLOBALS['task_manager_config_path'];
        }
        return $this->configPath;
    }

    private function __construct()
    {
        $this->load();
    }

    public function addTask(Task $task)
    {
        $id = $this->dataContainer->addTask($task);
        $this->save();
        return $id;
    }
    public function deleteTask($taskId)
    {
        $this->dataContainer->deleteTask($taskId);
        $this->save();
    }
    public function completeTask($taskId)
    {
        $task = $this->dataContainer->getTask($taskId);
        $task->complete();
        $this->save();
    }
    public function closeTask($taskId)
    {
        $task = $this->dataContainer->closeTask($taskId);
        $this->save();
    }
    public function cancelTask($taskId)
    {
        $task = $this->dataContainer->getTask($taskId);
        $task->cancel();
        $this->save();
    }
    public function workInTask($taskId)
    {
        $task = $this->dataContainer->getTask($taskId);
        $task->workOnIt();
        $this->save();
    }
    public function getTasks()
    {
        return $this->dataContainer->getTasks();
    }
    public function getAllTasks()
    {
        return $this->dataContainer->getTasks() + $this->dataContainer->getClosedTasks();
    }
}

class DataCorruptionFound extends CommandLineException
{
    public function __construct() {
        return parent::__construct('Data corruption was found trying to load ' .
            'tasks database. Please try again with an older version of the ' .
            'program.');
    }
}
class InvalidDataContainerClassName extends Exception
{
}
class TaskNotFoundException extends Exception
{
}
class NotConfiguredTaskManagerException extends Exception
{
}

class WrongParametersException extends CommandLineException
{
    public function __construct() {
        return parent::__construct('Wrong parameters');
    }
}

function debug($data)
{
    $c = Console::i();
    $c->outl($c->color('cyan') . "DEBUG: " . $c->color() . $data);
}


CommandLineProgram::main('\CoolestTaskManager\CoolestTaskManagerProgram', $argv);

