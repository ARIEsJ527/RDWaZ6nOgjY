<?php
// 代码生成时间: 2025-08-23 03:04:53
// UserPermissionSystem.php
// 用户权限管理系统

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Exception as ModelException;
use Phalcon\Acl;
use Phalcon\Acl\<Role;
use Phalcon\Acl\Resource;
use Phalcon\Acl\Adapter\Memory;

class UserPermissionSystem extends Controller
{
    // 初始化Acl组件
    private function initializeAcl()
    {
        $acl = new Memory();

        // 定义角色
        $roles = [
            'User' => new Role('User'),
            'Admin' => new Role('Admin')
        ];

        // 定义资源
        $resources = [
            'Index' => new Resource('Index'),
            'Users' => new Resource('Users')
        ];

        // 设置默认角色
        $acl->setDefaultAction(Acl::DENY);

        // 添加角色
        foreach ($roles as $role) {
            $acl->addRole($role);
        }

        // 添加资源
        foreach ($resources as $resource) {
            $acl->addResource($resource, 'index', 'search', 'create', 'update', 'delete');
        }

        // 定义访问规则
        $acl->allow('User', 'Index', ['index', 'search']);
        $acl->allow('Admin', 'Users', ['create', 'update', 'delete']);
        $acl->allow('Admin', 'Index', ['index', 'search']);

        // 返回Acl对象
        return $acl;
    }

    // 检查权限
    public function beforeExecuteRoute(dispatcher)
    {
        try {
            $auth = $this->session->get('auth');
            if (!$auth) {
                $this->response->redirect('index/login');
                return false;
            }

            $acl = $this->initializeAcl();
            $role = $auth->role;
            $controllerName = $dispatcher->getControllerName();
            $actionName = $dispatcher->getActionName();

            if (!$acl->isAllowed($role, $controllerName, $actionName)) {
                $this->flash->error('You do not have permission to access this area');
                $this->response->redirect('index/index');
                return false;
            }
        } catch (Exception $e) {
            $this->flash->error($e->getMessage());
            $this->response->redirect('index/index');
            return false;
        }
    }

    // 登录方法
    public function loginAction()
    {
        if ($this->request->isPost()) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            try {
                $user = Users::findFirst(
                    [['username' => $username], ['password' => $this->security->hash($password)]]
                );

                if ($user) {
                    $auth = [
                        'id' => $user->id,
                        'role' => $user->role
                    ];

                    $this->session->set('auth', $auth);
                    $this->response->redirect('index/index');
                } else {
                    $this->flash->error('Invalid username or password');
                }
            } catch (ModelException $e) {
                $this->flash->error($e->getMessage());
            }
        }
    }

    // 登出方法
    public function logoutAction()
    {
        $this->session->remove('auth');
        $this->response->redirect('index/login');
    }
}
