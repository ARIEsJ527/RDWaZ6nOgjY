<?php
// 代码生成时间: 2025-09-17 12:13:20
class ErrorLogger {

    private $logFile;
    private $logger;

    /**
     * 构造函数
     *
     * @param string $logFile 错误日志文件路径
     */
    public function __construct($logFile) {
        $this->logFile = $logFile;
        $this->logger = new \Phalcon\Logger\Adapter\File($logFile);
    }

    /**
     * 记录错误日志
     *
     * @param string $message 错误信息
     * @param int $level 错误级别
     * @return bool
     */
    public function logError($message, $level = \Phalcon\Logger::ERROR) {
        try {
            // 记录错误日志
            $this->logger->alert($message, $level);
            return true;
        } catch (Exception $e) {
            // 错误处理
            echo 'Error logging failed: ' . $e->getMessage();
            return false;
        }
    }

    /**
     * 获取错误日志文件路径
     *
     * @return string
     */
    public function getLogFile() {
        return $this->logFile;
    }

    /**
     * 设置错误日志文件路径
     *
     * @param string $logFile
     */
    public function setLogFile($logFile) {
        $this->logFile = $logFile;
    }
}
