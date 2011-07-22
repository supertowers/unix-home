<?php

require_once __DIR__ . '/bootstrap.php';

class ExampleTree extends CommandLineProgram
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


    public static function main($argv)
    {
        $p = new self();
        $c = Console::i();
        $c->setProgram($p);

        $ot = $p->getOptionsTree();

        while (TRUE)
        {
            $c->out($p->getPrompt());
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

    public function getPrompt()
    {
        return Console::i()->color('blue') . ' > ' . Console::i()->color();
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
    private $project = array();
    private $contexts = array();
    private $status = self::STATUS_IDLE;
    private $completedDate = NULL;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function __construct($name)
    {
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
    public function isClosed()
    {
        return $this->status === self::STATUS_CLOSED;
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
        $taskId = ++$this->lastTaskId;
        $this->tasks[$taskId] = $task;
        $task->setId($taskId);
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

    private $dataContainerClassName = 'DataContainer';
    private $dataContainer;

    private $configPath = '/Users/pablo/dev/identity.redcore.es/xtructs/app/fingerprint';
    private $configFile = '.tasks';
    private function load()
    {
        $this->loadContainerFromFile($this->configPath . "/" . $this->configFile);
    }

    private function save()
    {
        $this->saveContainerToFile($this->configPath . "/" . $this->configFile);
    }

    private function loadContainerFromFile($path)
    {
        if (file_exists($path))
        {
            $this->dataContainer = unserialize(file_get_contents($path));

            if (! $this->dataContainer instanceof IDataContainer)
            {
                throw new DataCorruptionFound();
            }
        }
        else
        {
            $className = $this->dataContainerClassName;
            if (! class_exists($className))
            {
                throw new InvalidDataContainerClassName($className);
            }
            $this->dataContainer = new $className();
            $this->save();
        }
    }
    private function saveContainerToFile($path)
    {
        file_put_contents($path, serialize($this->dataContainer));
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

class DataCorruptionFound extends Exception
{
}
class InvalidDataContainerClassName extends Exception
{
}
class TaskNotFoundException extends Exception
{
}
function debug($data)
{
    $c = Console::i();
    $c->outl($c->color('cyan') . "DEBUG: " . $c->color() . $data);
}

ExampleTree::main($argv);


