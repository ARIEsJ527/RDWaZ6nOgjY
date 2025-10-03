<?php
// 代码生成时间: 2025-10-04 02:45:24
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;
use Phalcon\Config;
use Phalcon\Di;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as LoggerFile;

class StreamingMediaPlayer extends Controller
{
    
    protected $config;
    protected $logger;
    
    /**
     * Constructor
     *
     * @param DI $dependencyInjector
     */
    public function __construct($dependencyInjector = null)
    {
        parent::__construct($dependencyInjector);
        
        // Initialize the configuration and logger
        $this->config = $this->getDI()->getShared('config');
        $this->logger = new LoggerFile($this->config->application->logPath);
    }
    
    /**
     * Streams a media file to the client
     *
     * @param string $filePath Path to the media file
     * @return void
     */
    public function streamAction($filePath)
    {
        try {
            // Check if the file exists
            if (!file_exists($filePath)) {
                throw new \Exception('File does not exist: ' . $filePath);
            }
            
            // Set the appropriate headers for streaming
            header('Content-Type: video/mp4'); // Replace with the correct content type
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
        } catch (\Exception $e) {
            // Log the error and handle it
            $this->logger->error($e->getMessage());
            // Send an error response to the client
            $this->response->setStatusCode(500, 'Internal Server Error')->sendHeaders();
            echo 'Error streaming media file: ' . $e->getMessage();
        }
    }
    
    /**
     * Handles the index action
     *
     * @return void
     */
    public function indexAction()
    {
        // Render the view for the stream media player
        $this->view->render('streaming_media_player', 'index');
    }
}
