<?php
// 代码生成时间: 2025-09-19 01:32:37
use Phalcon\Mvc\User\Component;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Manager as EventsManager;

class AccessControl extends Component {
    /**
     * @var Acl
     */
    protected $acl;

    public function initialize() {
        $this->acl = $this->getAcl();
    }

    /**
     * 创建ACL
     *
     * @return Acl
     */
    protected function getAcl() {
        $acl = new Acl();

        // 默认拒绝所有访问
        $acl->setDefaultAction(Acl::DENY);

        // 添加角色
        $roles = [
            new Role('Guests'),
            new Role('Members'),
            new Role('Administrators')
        ];
        foreach ($roles as $role) {
            $acl->addRole($role);
        }

        // 添加资源
        $resources = [
            new Resource('Index'),
            new Resource('Users'),
            new Resource('Products')
        ];
        foreach ($resources as $resource) {
            $acl->addResource($resource, ['index', 'search', 'new', 'edit', 'create', 'save', 'delete']);
        }

        // 配置访问权限
        $acl->allow('Guests', 'Index', ['index']);
        $acl->allow('Members', 'Users', ['index', 'search']);
        $acl->allow('Administrators', 'Products', ['index', 'search', 'new', 'edit', 'create', 'save', 'delete']);

        return $acl;
    }

    /**
     * 检查用户是否有权限访问某个资源
     *
     * @param string $role
     * @param string $resourceName
     * @param string $access
     * @return bool
     */
    public function isAllowed($role, $resourceName, $access) {
        return $this->acl->isAllowed($role, $resourceName, $access);
    }
}
