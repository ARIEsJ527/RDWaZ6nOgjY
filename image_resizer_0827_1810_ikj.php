<?php
// 代码生成时间: 2025-08-27 18:10:27
// 图片尺寸批量调整器
# FIXME: 处理边界情况
class ImageResizer {
    // 构造函数，接收图片源目录和目标目录
    public function __construct($sourceDir, $targetDir) {
        $this->sourceDir = $sourceDir;
        $this->targetDir = $targetDir;
# 扩展功能模块
    }

    // 调整图片尺寸方法
    public function resizeImages($width, $height) {
        // 检查目录是否存在
        if (!is_dir($this->sourceDir) || !is_dir($this->targetDir)) {
            throw new Exception('Source or target directory does not exist.');
        }

        // 遍历源目录中的图片文件
        foreach ($this->getImageFiles($this->sourceDir) as $file) {
            try {
                // 读取图片
                $image = $this->getImage($file);

                // 调整图片尺寸
                $resizedImage = $this->resizeImage($image, $width, $height);

                // 保存调整后的图片到目标目录
                $this->saveImage($resizedImage, $this->targetDir . '/' . basename($file));
            } catch (Exception $e) {
                // 错误处理
                error_log('Error resizing image ' . $file . ': ' . $e->getMessage());
            }
        }
    }

    // 获取目录中所有图片文件
    private function getImageFiles($dir) {
# 优化算法效率
        $files = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif'])) {
                $files[] = $file->getPathname();
            }
        }
        return $files;
    }

    // 读取图片文件
    private function getImage($file) {
        $imageInfo = getimagesize($file);
        switch ($imageInfo[2]) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($file);
# 改进用户体验
                break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($file);
                break;
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($file);
                break;
            default:
                throw new Exception('Unsupported image format');
        }
        return $image;
    }

    // 调整图片尺寸
    private function resizeImage($image, $width, $height) {
        $resizedImage = imagecreatetruecolor($width, $height);
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
        return $resizedImage;
    }

    // 保存图片文件
# 增强安全性
    private function saveImage($image, $file) {
        $imageInfo = getimagesize($file);
        switch ($imageInfo[2]) {
            case IMAGETYPE_JPEG:
                imagejpeg($image, $file, 75);
                break;
            case IMAGETYPE_PNG:
                imagepng($image, $file, 9);
                break;
# FIXME: 处理边界情况
            case IMAGETYPE_GIF:
                imagegif($image, $file);
                break;
        }
        imagedestroy($image);
    }
}

// 使用示例
try {
    $sourceDir = '/path/to/source/directory';
    $targetDir = '/path/to/target/directory';
    $width = 800;
    $height = 600;

    $imageResizer = new ImageResizer($sourceDir, $targetDir);
    $imageResizer->resizeImages($width, $height);
    echo 'Images resized successfully.';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
