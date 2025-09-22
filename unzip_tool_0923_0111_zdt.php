<?php
// 代码生成时间: 2025-09-23 01:11:42
use Phalcon\Mvc\Controller;
use Phalcon\Filter;
use Phalcon\Text;
use Phalcon\Di\FactoryDefault;
use Phalcon\Di;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Model\Transaction\Manager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Dispatcher\Exception;
use Phalcon\Mvc\View\Exception as ViewException;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as LoggerFile;
use Phalcon\Flash\Direct;

class UnzipToolController extends Controller
{
    private $unzipPath;
    private $tempPath;
    private $zipFile;
    private $destPath;
    private $errorMessages = [];

    /**
     * Initialize the controller
     *
     * @param Di $dependencyInjector
     */
    public function initialize($di = null)
    {
        $this->unzipPath = $this->config->application->unzipPath;
        $this->tempPath = $this->config->application->tempPath;
        $this->view->setVar('di', $di);
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->view->setVar('unzipPath', $this->unzipPath);
    }

    /**
     * Unzip action
     *
     * @param string $zipFileName
     */
    public function unzipAction($zipFileName = null)
    {
        if (!$zipFileName) {
            $this->flashSession->error('No zip file provided.');
            return $this->dispatcher->forward(['controller' => 'unzipTool', 'action' => 'index']);
        }

        try {
            $this->zipFile = $this->tempPath . $zipFileName;
            $this->destPath = $this->unzipPath . Text::uncamelize($zipFileName) . '_' . Filter::sanitize(uniqid('', true));
            $this->_recursiveUnzip($this->zipFile, $this->destPath);
            $this->flashSession->success('File successfully unzipped at ' . $this->destPath);
        } catch (Exception $e) {
            $this->flashSession->error($e->getMessage());
            return $this->dispatcher->forward(['controller' => 'unzipTool', 'action' => 'index']);
        }
    }

    /**
     * Recursively unzips the file
     *
     * @param string $zipFile
     * @param string $destPath
     * @throws Exception
     */
    private function _recursiveUnzip($zipFile, $destPath)
    {
        if (!file_exists($zipFile)) {
            throw new Exception('Zip file does not exist.');
        }

        $zip = new ZipArchive();
        if ($zip->open($zipFile) !== true) {
            throw new Exception('Failed to open zip file.');
        }

        if (!is_dir($destPath)) {
            mkdir($destPath, 0777, true);
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            $extractPath = $destPath . DIRECTORY_SEPARATOR . $filename;

            if (substr($filename, -1) == '/') {
                if (!is_dir($extractPath)) {
                    mkdir($extractPath, 0777, true);
                }
            } else {
                file_put_contents($extractPath, $zip->getStream($filename));
            }
        }

        $zip->close();
    }
}
