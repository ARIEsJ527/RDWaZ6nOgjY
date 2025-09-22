<?php
// 代码生成时间: 2025-09-22 15:24:40
// password_encryption_tool.php
// 密码加密解密工具

use Phalcon\Encryption\Crypt;

class PasswordEncryptionTool {

    /**
     * @var Crypt
     * Phalcon加密组件实例
     */
    private $crypt;

    public function __construct() {
        // 初始化Phalcon加密组件
        $this->crypt = new Crypt();
        // 设置加密密钥
        $this->crypt->setKey("your-encryption-key");
    }

    /**
     * 加密密码
     *
     * @param string $password 需要加密的密码
     *
     * @return string 加密后的密码
     */
    public function encryptPassword(string $password): string {
        try {
            // 使用Phalcon的Crypt类加密密码
            return $this->crypt->encryptBase64($password);
        } catch (Exception $e) {
            // 错误处理
            throw new Exception("Password encryption failed: " . $e->getMessage());
        }
    }

    /**
     * 解密密码
     *
     * @param string $encryptedPassword 需要解密的密码
     *
     * @return string 解密后的密码
     */
    public function decryptPassword(string $encryptedPassword): string {
        try {
            // 使用Phalcon的Crypt类解密密码
            return $this->crypt->decryptBase64($encryptedPassword);
        } catch (Exception $e) {
            // 错误处理
            throw new Exception("Password decryption failed: " . $e->getMessage());
        }
    }
}

// 示例用法
try {
    $tool = new PasswordEncryptionTool();
    $password = "your-password";
    $encrypted = $tool->encryptPassword($password);
    echo "Encrypted: " . $encrypted . "\
";
    $decrypted = $tool->decryptPassword($encrypted);
    echo "Decrypted: " . $decrypted . "\
";
} catch (Exception $e) {
    echo $e->getMessage();
}
