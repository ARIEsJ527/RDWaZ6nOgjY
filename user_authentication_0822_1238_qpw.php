<?php
// 代码生成时间: 2025-08-22 12:38:43
class UserAuthentication {

    /**
     * @var Phalcon\Di 服务容器
     */
    private $di;

    /**
     * 构造函数
     *
     * @param Phalcon\Di $di 服务容器
     */
    public function __construct(Phalcon\Di $di) {
        $this->di = $di;
    }

    /**
     * 用户登录
     *
     * @param string $username 用户名
     * @param string $password 密码
     * @return bool 登录成功返回true，否则返回false
     */
    public function login($username, $password) {
        try {
            // 从数据库获取用户信息
            $user = $this->getUserByUsername($username);
            if (!$user) {
                throw new Exception('用户名不存在');
            }

            // 验证密码
            if (!password_verify($password, $user->password)) {
                throw new Exception('密码错误');
            }

            // 设置会话
            $this->setSession($user);

            return true;
        } catch (Exception $e) {
            // 错误处理
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * 验证用户是否已登录
     *
     * @return bool 已登录返回true，否则返回false
     */
    public function isAuthenticated() {
        // 检查会话
        return isset($_SESSION['user_id']);
    }

    /**
     * 获取用户信息
     *
     * @param string $username 用户名
     * @return mixed 用户信息或null
     */
    private function getUserByUsername($username) {
        // 使用Phalcon的模型来查询用户
        $userModel = $this->di->get('modelsManager')->getRepository('User');
        return $userModel->findFirst(['username = :username:', 'bind' => ['username' => $username]]);
    }

    /**
     * 设置会话
     *
     * @param object $user 用户对象
     */
    private function setSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
    }
}
