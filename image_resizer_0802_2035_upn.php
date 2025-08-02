<?php
// 代码生成时间: 2025-08-02 20:35:38
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Image\Adapter\Imagick;
use Phalcon\Filter;

/**
 * 图片尺寸批量调整器
 */
class ImageResizer
{
    private $sourceDir;
    private $targetDir;
    private $width;
    private $height;
    private $logger;

    /**
     * 构造函数
     *
     * @param string $sourceDir 原图片目录
     * @param string $targetDir 目标图片目录
     * @param int $width 目标宽度
     * @param int $height 目标高度
     */
    public function __construct($sourceDir, $targetDir, $width, $height)
    {
        $this->sourceDir = $sourceDir;
        $this->targetDir = $targetDir;
        $this->width = $width;
        $this->height = $height;
        $this->logger = new Logger\Adapter\FileAdapter('logs/image_resizer.log');
    }

    /**
     * 调整图片尺寸
     */
    public function resizeImages()
    {
        $filter = new Filter();

        if (!is_dir($this->sourceDir)) {
            $this->logger->error("Source directory does not exist: {$this->sourceDir}");
            return;
        }

        if (!is_dir($this->targetDir)) {
            mkdir($this->targetDir, 0777, true);
        }

        $files = scandir($this->sourceDir);

        foreach ($files as $file) {
            $filePath = $this->sourceDir . DIRECTORY_SEPARATOR . $file;
            if (is_file($filePath) && $filter->sanitize($file, 'alnum') === $file) {
                $this->resizeImage($filePath);
            }
        }
    }

    /**
     * 单个图片尺寸调整
     *
     * @param string $filePath 文件路径
     */
    private function resizeImage($filePath)
    {
        try {
            $image = new Imagick($filePath);
            $image->resize($this->width, $this->height);
            $targetPath = $this->targetDir . DIRECTORY_SEPARATOR . basename($filePath);
            $image->save($targetPath);
            $this->logger->info("Image resized: {$targetPath}");
        } catch (Exception $e) {
            $this->logger->error("Failed to resize image: {$filePath}. Error: {$e->getMessage()}");
        }
    }
}

// 使用示例
$sourceDir = 'path/to/source/images';
$targetDir = 'path/to/target/images';
$width = 800;
$height = 600;

$resizer = new ImageResizer($sourceDir, $targetDir, $width, $height);
$resizer->resizeImages();
