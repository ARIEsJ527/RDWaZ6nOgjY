<?php
// 代码生成时间: 2025-09-15 22:58:30
// ImageResizer.php
// A Phalcon PHP application that resizes images

use Phalcon\Mvc\Controller;
use Phalcon\Image\Adapter;
use Phalcon\Image\Adapter\Imagick;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File;

class ImageResizer extends Controller
{
    // Resizes the image to the specified dimensions
    public function resizeAction($sourcePath, $targetPath, $width, $height)
    {
        try {
            // Load the image adapter
            $adapter = new Imagick($sourcePath);

            // Check if the image exists and is valid
            if (!$adapter->isValid()) {
                throw new \Exception('The image file does not exist or is not valid.');
            }

            // Resize the image
            $adapter->resize($width, $height);

            // Save the resized image to the target path
            $adapter->save($targetPath);

            // Return a success message
            return $this->response->setJsonContent(['success' => true, 'message' => 'Image resized successfully.']);
        } catch (Exception $e) {
            // Log the exception
            $this->logError($e);

            // Return an error message
            return $this->response->setJsonContent(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Logs the error to a file
    private function logError($error)
    {
        $logger = new File('error.log');
        $logger->error($error->getMessage());
    }
}
