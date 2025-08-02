<?php
// 代码生成时间: 2025-08-03 05:46:06
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Model\Transaction\Manager as TxManager;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Flash\Session as Flash;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Acl\Adapter\Memory as AclList;

class UserAuthentication extends Controller
{
    // 依赖注入 Phalcon session, flash, 和 database connection
    protected $session;
    protected $flash;
    protected $db;

    public function initialize()
    {
        $this->session = $this->getDI()->getSession();
        $this->flash = new Flash($this->getDI());
        $this->db = $this->getDI()->get('db');
    }

    public function loginAction()
    {
        if ($this->request->isPost()) {
            // 获取表单数据
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            // 验证数据
            $validation = new Validation();
            $validation->add('email', new PresenceOf(array('message' => 'Email is required.')));
            $validation->add('email', new Email(array('message' => 'Email is not valid.')));
            $validation->add('password', new PresenceOf(array('message' => 'Password is required.')));
            $validation->add('password', new StringLength(array(
                'min' => 8,
                'messageMinimum' => 'Password is too short. Minimum 8 characters'
            )));
            $validation->add('password', new Identical(array(
                'value' => '$',
                'message' => 'Password must contain a dollar sign ($).',
                'included' => true
            )));

            $messages = $validation->validate($this->request->getPost());

            if (count($messages)) {
                foreach ($messages as $message) {
                    $this->flash->error($message);
                }
                return $this->dispatcher->forward(array('controller' => 'session', 'action' => 'index'));
            }

            // 验证通过，进行数据库查询
            $user = Users::findFirst(
                array(
                    'email = :email:',
                    'bind' => array('email' => $email)
                )
            );

            if ($user) {
                // 使用 Phalcon 的安全组件校验密码
                if ($this->security->checkHash($password, $user->password)) {
                    // 如果用户存在，密码正确，设置 session
                    $this->session->set('auth', array(
                        'id' => $user->id,
                        'name' => $user->name
                    ));

                    // 重定向到dashboard
                    return $this->response->redirect('dashboard/index');
                } else {
                    $this->flash->error('Incorrect password.');
                }
            } else {
                $this->flash->error('User not found.');
            }

            return $this->dispatcher->forward(array('controller' => 'session', 'action' => 'index'));
        }
    }

    public function logoutAction()
    {
        // 销毁 session
        $this->session->remove('auth');
        return $this->response->redirect('index');
    }
}
