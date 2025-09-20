<?php
// 代码生成时间: 2025-09-20 17:09:07
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Message;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Session\Adapter\Files as Session;

class LoginController extends Controller
{
    /**
     * 登录操作
     *
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function indexAction($email, $password)
    {
        try {
            // 创建验证器
            $validation = new Validation();

            // 添加验证规则
            $validation->add('email', new PresenceOf(array(
                'message' => 'Email is required'
            )));
            $validation->add('email', new Email(array(
                'message' => 'Email is not valid'
            )));
            $validation->add('password', new PresenceOf(array(
                'message' => 'Password is required'
            )));
            $validation->add('password', new StringLength(array(
                'min' => 8,
                'messageMinimum' => 'Password is too short. Minimum 8 characters'
            )));

            // 执行验证
            $messages = $validation->validate(array(
                'email' => $email,
                'password' => $password
            ));

            // 检查是否有验证错误
            if (count($messages)) {
                foreach ($messages as $message) {
                    $this->flash->error($message);
                }
                return false;
            }

            // 验证通过，执行登录操作
            $user = $this->findUserByEmail($email);
            if ($user) {
                if (password_verify($password, $user->password)) {
                    // 登录成功，设置session
                    $this->setSession($user);
                    $this->flash->success('You are logged in successfully');
                    return true;
                } else {
                    $this->flash->error('Invalid password');
                    return false;
                }
            } else {
                $this->flash->error('User not found');
                return false;
            }
        } catch (Exception $e) {
            $this->flash->error($e->getMessage());
            return false;
        }
    }

    /**
     * 查找用户
     *
     * @param string $email
     * @return Resultset|bool
     */
    protected function findUserByEmail($email)
    {
        // 假设有一个Users模型
        $user = Users::findFirst(array(
            'conditions' => 'email = :email:',
            'bind' => array(
                'email' => $email
            )
        ));

        return $user;
    }

    /**
     * 设置session
     *
     * @param Resultset $user
     */
    protected function setSession($user)
    {
        $session = new Session();
        $session->start();
        $session->set('user_id', $user->id);
    }
}
