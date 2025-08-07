<?php
// 代码生成时间: 2025-08-07 23:43:38
// Image Resizer using PHP and Phalcon Framework
// This script resizes images in batch

use Phalcon\Image as PhalconImage;
use Phalcon\Mvc\Application;
use Phalcon\DI\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Config\Adapter\Ini as ConfigIni;

class ImageResizer extends ConsoleApp
{
    public function onConstruct()
    {
        // Load configuration file
        $config = new ConfigIni(\$configFile = '../app/config/config.ini');

        // Set application configuration
        $this->getDI()->set('config', function () use ($config) {
            return $config;
        });
    }

    public function mainAction()
    {
        // Get the images directory and target size from the configuration
        $sourceDir = $this->getDI()->get('config')->image_resizer->sourceDir;
        $targetDir = $this->getDI()->get('config')->image_resizer->targetDir;
        $targetWidth = $this->getDI()->get('config')->image_resizer->targetWidth;
        $targetHeight = $this->getDI()->get('config')->image_resizer->targetHeight;

        // Check if the source directory exists
        if (!file_exists($sourceDir)) {
            $this->handleError("Source directory does not exist: {$sourceDir}");
            return;
        }

        // Check if the target directory exists, if not, create it
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Get all image files in the source directory
        $files = glob($sourceDir . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

        foreach ($files as $file) {
            try {
                // Load the image
                $image = PhalconImage::factory($file);
                // Resize the image
                $image->resize($targetWidth, $targetHeight);
                // Save the resized image to the target directory
                $image->save($targetDir . basename($file));
                echo "Resized and saved: " . $file . "\
";
            } catch (Exception $e) {
                // Handle any errors that occur during the resizing process
                $this->handleError("Error resizing image: {$file} - {$e->getMessage()}");
            }
        }
    }

    protected function handleError($message)
    {
        // Log the error and exit the script
        echo $message . "\
";
        exit(1);
    }
}

// Bootstrap Phalcon application
$di = new FactoryDefault();
$loader = new Loader();
$loader->registerDirs(
    array(
        '../app/library/',
        '../app/models/',
        '../app/controllers/'
    )
)->register();

// Set up the console application
$console = new ImageResizer($di);
$console->run();
