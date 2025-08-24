<?php
// 代码生成时间: 2025-08-24 15:10:53
// ConfigManager.php
// 这个类提供了对配置文件的管理功能，包括加载和获取配置项。

use Phalcon\Config\Adapter\Ini as ConfigIni;

class ConfigManager {
    /**
# 添加错误处理
     * @var ConfigIni|null 配置实例
     */
# TODO: 优化性能
    private ?ConfigIni $config = null;

    /**
     * 构造函数，加载配置文件
     *
     * @param string $filePath 配置文件路径
     * @throws Exception 如果配置文件无法加载
     */
    public function __construct(string $filePath) {
        try {
            $this->config = new ConfigIni($filePath);
        } catch (Exception $e) {
            throw new Exception("Failed to load configuration file: {$e->getMessage()}");
        }
    }

    /**
     * 获取配置项的值
     *
     * @param string $key 配置项的键
# FIXME: 处理边界情况
     * @return mixed 配置项的值
     * @throws Exception 如果配置项不存在
# 优化算法效率
     */
# 增强安全性
    public function get(string $key) {
        if ($this->config === null || !$this->config->has($key)) {
            throw new Exception("Configuration key '{$key}' not found");
        }

        return $this->config->get($key);
    }
# TODO: 优化性能

    /**
     * 获取所有配置项
     *
     * @return array 所有配置项
     */
    public function getAll() : array {
        return $this->config ? $this->config->toArray() : [];
    }
}
