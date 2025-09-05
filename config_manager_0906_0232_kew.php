<?php
// 代码生成时间: 2025-09-06 02:32:18
use Phalcon\Config\Adapter\Php;
use Phalcon\Config;

class ConfigManager {
    /**
     * @var Config|null The configuration object
     */
    private ?Config $config = null;

    /**
     * @var string The path to the configuration file
     */
    private string $configPath;

    /**
     * Constructor
     *
     * @param string $configPath The path to the configuration file
     */
    public function __construct(string $configPath) {
        $this->configPath = $configPath;
        try {
            $this->config = new Php($this->configPath);
        } catch (Exception $e) {
            // Handle the error, could be a log or an exception throw
            throw new Exception("Failed to load configuration file: " . $e->getMessage());
        }
    }

    /**
     * Get the entire configuration array
     *
     * @return array The configuration array
     */
    public function getAllConfigs(): array {
        return $this->config->toArray();
    }

    /**
     * Get a specific configuration value by key
     *
     * @param string $key The key to look up in the configuration
     * @return mixed The configuration value
     */
    public function getConfigValue(string $key) {
        return $this->config->get($key);
    }

    /**
     * Set a specific configuration value by key
     *
     * @param string $key The key to set in the configuration
     * @param mixed $value The value to set
     * @return mixed The new configuration value
     */
    public function setConfigValue(string $key, $value): mixed {
        return $this->config->set($key, $value);
    }
}
