<?php
// 代码生成时间: 2025-09-24 18:36:38
class ImageResizer {

    /**
     * 保存图片的目录
     *
     * @var string
     */
    private $sourceDir;

    /**
     * 图片保存的目录
     *
     * @var string
     */
    private $targetDir;

    /**
     * 目标图片尺寸
     *
     * @var int
     */
    private $width;

    /**
     * 目标图片尺寸
     *
     * @var int
     */
    private $height;

    /**
     * 构造函数
     *
     * @param string $sourceDir 保存图片的目录
     * @param string $targetDir 图片保存的目录
     * @param int $width 目标图片宽度
     * @param int $height 目标图片高度
     */
    public function __construct($sourceDir, $targetDir, $width, $height) {
        $this->sourceDir = $sourceDir;
        $this->targetDir = $targetDir;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * 批量调整图片尺寸
     *
     * @return bool 成功返回true，失败返回false
     */
    public function resize() {
        try {
            // 检查源目录和目标目录是否存在
            if (!file_exists($this->sourceDir) || !file_exists($this->targetDir)) {
                throw new Exception("目录不存在");
            }

            // 获取源目录下所有图片
            $files = scandir($this->sourceDir);

            foreach ($files as $file) {
                // 过滤非图片文件
                if (pathinfo($file, PATHINFO_EXTENSION) == 'jpg' || pathinfo($file, PATHINFO_EXTENSION) == 'jpeg' || pathinfo($file, PATHINFO_EXTENSION) == 'png') {
                    // 获取图片路径
                    $sourcePath = $this->sourceDir . DIRECTORY_SEPARATOR . $file;

                    // 获取目标图片路径
                    $targetPath = $this->targetDir . DIRECTORY_SEPARATOR . $file;

                    // 调整图片尺寸
                    $this->resizeImage($sourcePath, $targetPath);
                }
            }

            return true;
        } catch (Exception $e) {
            // 错误处理
            echo "错误：" . $e->getMessage();
            return false;
        }
    }

    /**
     * 调整单个图片尺寸
     *
     * @param string $sourcePath 源图片路径
     * @param string $targetPath 目标图片路径
     * @return bool 成功返回true，失败返回false
     */
    private function resizeImage($sourcePath, $targetPath) {
        try {
            // 加载图片
            $image = new Imagick($sourcePath);

            // 调整图片尺寸
            $image->resizeImage($this->width, $this->height, Imagick::FILTER_LANCZOS, 1, false);

            // 保存图片
            $image->writeImage($targetPath);

            // 释放资源
            $image->clear();
            $image->destroy();

            return true;
        } catch (Exception $e) {
            // 错误处理
            echo "错误：" . $e->getMessage();
            return false;
        }
    }
}

// 使用示例
try {
    $sourceDir = "/path/to/source/directory";
    $targetDir = "/path/to/target/directory";
    $width = 800;
    $height = 600;

    $imageResizer = new ImageResizer($sourceDir, $targetDir, $width, $height);
    $imageResizer->resize();
    echo "图片尺寸调整成功";
} catch (Exception $e) {
    echo "错误：" . $e->getMessage();
}
