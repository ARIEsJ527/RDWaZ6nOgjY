<?php
// 代码生成时间: 2025-08-14 22:40:31
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\Application;

// 设置错误报告级别
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));

// 注册自动加载器
$loader = new Loader();
$loader->registerDirs([
    BASE_PATH . '/app/controllers/',
    BASE_PATH . '/app/models/'
])->register();

// 依赖注入容器
$di = new FactoryDefault();

// 设置视图服务
$di->set('view', function(){
    $view = new Phalcon\Mvc\View();
    $view->setViewsDir(BASE_PATH . '/app/views/');
    return $view;
}, true);

try {
    // 启动Phalcon应用程序
    $app = new Application($di);
    $response = $app->handle(
        $_SERVER['REQUEST_URI']
    );
    echo $response->getContent();
} catch (Exception $e) {
    echo $e->getMessage();
}

// 图片尺寸批量调整器类
class ImageResizeBatchProcessor {
    /**
     * 调整图片尺寸
     *
     * @param string $sourcePath 源图片路径
     * @param string $targetPath 目标图片路径
     * @param int $width 目标宽度
     * @param int $height 目标高度
     * @return bool
     */
    public function resizeImage($sourcePath, $targetPath, $width, $height) {
        // 检查图片文件是否存在
        if (!file_exists($sourcePath)) {
            throw new Exception('Source image file not found.');
        }

        // 获取图片信息
        $imageInfo = getimagesize($sourcePath);
        $mime = $imageInfo['mime'];

        // 根据图片类型创建不同的GD图像资源
        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($sourcePath);
                break;
            default:
                throw new Exception('Unsupported image type.');
        }

        // 创建新的GD图像资源
        $newImage = imagecreatetruecolor($width, $height);

        // 调整图片尺寸并复制到新图像资源
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $imageInfo[0], $imageInfo[1]);

        // 保存调整后的图片
        switch ($mime) {
            case 'image/jpeg':
                imagejpeg($newImage, $targetPath, 90);
                break;
            case 'image/png':
                imagepng($newImage, $targetPath);
                break;
            case 'image/gif':
                imagegif($newImage, $targetPath);
                break;
        }

        // 释放内存
        imagedestroy($image);
        imagedestroy($newImage);

        return true;
    }

    /**
     * 批量调整图片尺寸
     *
     * @param array $files 图片文件数组
     * @param int $width 目标宽度
     * @param int $height 目标高度
     * @return array
     */
    public function batchResizeImages($files, $width, $height) {
        $results = [];

        foreach ($files as $file) {
            try {
                $targetPath = $file['target_path'];
                $this->resizeImage($file['source_path'], $targetPath, $width, $height);
                $results[$file['source_path']] = $targetPath;
            } catch (Exception $e) {
                $results[$file['source_path']] = $e->getMessage();
            }
        }

        return $results;
    }
}
