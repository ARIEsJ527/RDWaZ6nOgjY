<?php
// 代码生成时间: 2025-09-13 09:54:36
class ProcessManager {

    /**
     * @var \Phalcon\Process\ProcessFactory Process factory
     */
    private $processFactory;

    /**
     * ProcessManager constructor
     */
    public function __construct() {
        $this->processFactory = new \Phalcon\Process\ProcessFactory();
    }

    /**
     * Start a new process
     *
     * @param string $command Command to execute
     * @return \Phalcon\Process\AbstractProcess
     */
    public function startProcess(string $command): \Phalcon\Process\AbstractProcess {
        try {
            $process = $this->processFactory->newInstance($command);
            $process->start();
            return $process;
        } catch (\Phalcon\Process\Exception $e) {
            // Log error or handle it
            throw new \Exception('Failed to start process: ' . $e->getMessage());
        }
    }

    /**
     * Stop a running process
     *
     * @param \Phalcon\Process\AbstractProcess $process Process to stop
     * @return bool
     */
    public function stopProcess(\Phalcon\Process\AbstractProcess $process): bool {
        try {
            return $process->stop();
        } catch (\Phalcon\Process\Exception $e) {
            // Log error or handle it
            throw new \Exception('Failed to stop process: ' . $e->getMessage());
        }
    }

    /**
     * Check if a process is running
     *
     * @param \Phalcon\Process\AbstractProcess $process Process to check
     * @return bool
     */
    public function isProcessRunning(\Phalcon\Process\AbstractProcess $process): bool {
        return $process->isRunning();
    }

    /**
     * Get the output of a process
     *
     * @param \Phalcon\Process\AbstractProcess $process Process to get output from
     * @return string
     */
    public function getProcessOutput(\Phalcon\Process\AbstractProcess $process): string {
        return $process->getOutput();
    }

    /**
     * Get the error output of a process
     *
     * @param \Phalcon\Process\AbstractProcess $process Process to get error output from
     * @return string
     */
    public function getProcessErrorOutput(\Phalcon\Process\AbstractProcess $process): string {
        return $process->getErrorOutput();
    }
}
