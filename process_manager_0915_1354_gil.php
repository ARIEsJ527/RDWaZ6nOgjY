<?php
// 代码生成时间: 2025-09-15 13:54:24
use Phalcon\DI;
use Phalcon\Config;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File;
use Phalcon\Process;
use Phalcon\Process\Exception;
use Phalcon\Version;

class ProcessManager
{

    /**
     * @var array $processList List of processes to manage
     */
    protected $processList = [];

    /**
     * @var Logger $logger Logger instance for logging
     */
    protected $logger;

    /**
     * ProcessManager constructor.
     *
     * @param DI $di DI container
     * @throws Exception
     */
    public function __construct(DI $di)
    {
        // Initialize the logger
        $this->logger = $di->getShared('logger');

        // Check if Phalcon version is supported
        if (Version::get() < '4.0.0') {
            throw new Exception('Unsupported Phalcon version. Please upgrade to version 4.0.0 or higher.');
        }
    }

    /**
     * Start a new process
     *
     * @param string $command The command to execute
     * @return Process
     */
    public function startProcess(string $command): Process
    {
        try {
            $process = new Process($command);
            $process->start();
            $this->logger->info('Process started:', ['command' => $command]);
            return $process;
        } catch (Exception $e) {
            $this->logger->error('Failed to start process:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Stop a running process
     *
     * @param Process $process The process to stop
     * @return bool
     */
    public function stopProcess(Process $process): bool
    {
        try {
            $process->stop();
            $this->logger->info('Process stopped:', ['pid' => $process->getPid()]);
            return true;
        } catch (Exception $e) {
            $this->logger->error('Failed to stop process:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Check the status of a process
     *
     * @param Process $process The process to check
     * @return bool
     */
    public function isProcessRunning(Process $process): bool
    {
        return $process->isRunning();
    }

    /**
     * Get the process list
     *
     * @return array
     */
    public function getProcessList(): array
    {
        return $this->processList;
    }

    /**
     * Add a process to the list
     *
     * @param Process $process The process to add
     */
    public function addProcess(Process $process)
    {
        $this->processList[$process->getPid()] = $process;
    }

    /**
     * Remove a process from the list
     *
     * @param int $pid The process ID to remove
     */
    public function removeProcess(int $pid)
    {
        unset($this->processList[$pid]);
    }
}
