<?php
// 代码生成时间: 2025-08-09 12:31:48
// access_control.php
// 使用PHALCON框架实现访问权限控制

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Acl\Adapter\ Memory as AclList;
use Phalcon\Http\Request;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Component;
use Phalcon\Mvc\ControllerBase;
use Phalcon\Filter;
use Phalcon\Mvc\Url;
use Phalcon\Session\Adapter\Files as Session;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\Model\Manager;
use Phalcon\Mvc\Model\Transaction\Manager as Tm;
use Phalcon\Mvc\Model\ValidationFailed as ValidationFailed;
use Phalcon\Mvc\Model\Exception;
use Phalcon\Mvc\View;
use Phalcon\Logger;
use Phalcon\Registry;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Tag;
use Phalcon\Flash;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Annotations\Adapter\Memory as Annotations;
use Phalcon\Escaper;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\PresenceOf as PresenceOfValidator;
use Phalcon\Validation\Validator\StringLength as StringLengthValidator;
use Phalcon\Validation\Validator\Confirmation as ConfirmationValidator;
use Phalcon\Validation\Validator\Identical as IdenticalValidator;
use Phalcon\Validation\Validator\Regex as RegexValidator;
use Phalcon\Validation\Validator\Date as DateValidator;
use Phalcon\Validation\Validator\InclusionIn as InclusionInValidator;
use Phalcon\Validation\Validator\Between as BetweenValidator;
use Phalcon\Validation\Validator\ExclusionIn as ExclusionInValidator;
use Phalcon\Validation\Validator\Numericality as NumericalityValidator;
use Phalcon\Validation\Validator\StringLength as StringLengthValidator;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Validation\Validator\InclusionIn as InclusionInValidator;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Mvc\Model\Transaction\Failed as TxFailed;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Validation as V;
use Phalcon\Validation\Message\Group as Messages;
use Phalcon\Mvc\Model\MetaData\Memory as MetaData;
use Phalcon\Mvc\Model\Resultset\Simple as ResultsetSimple;

class AccessControl extends Component {
    protected $_roles;
    protected $_resources;
    protected $_acl;
    protected $_dispatcher;
    protected $_request;
    protected $_di;
    protected $_user;
    protected $_errorMessages;

    // 构造函数
    public function __construct($di) {
        $this->_di = $di;
        $this->_roles = array(
            new Role('Guests'),
            new Role('Members'),
            new Role('Admins')
        );
        $this->_resources = array(
            new Resource('Index'),
            new Resource('Posts'),
            new Resource('Users')
        );
        $this->_acl = new AclList();

        // 设置默认访问权限
        $this->_acl->setDefaultAction(Acl::DENY);

        // 设置角色
        foreach ($this->_roles as $role) {
            $this->_acl->addRole($role);
        }

        // 设置资源
        foreach ($this->_resources as $resource) {
            $this->_acl->addResource($resource, array('index', 'search', 'view', 'edit', 'delete'));
        }

        // 设置访问权限
        $this->_acl->allow('Guests', 'Index', 'index');
        $this->_acl->allow('Guests', 'Posts', 'index');
        $this->_acl->allow('Members', 'Posts', array('index', 'view'));
        $this->_acl->allow('Members', 'Users', 'index');
        $this->_acl->allow('Admins', '*');

        $this->_request = $di->get('request');
        $this->_dispatcher = $di->get('dispatcher');
        $this->_user = $di->get('user');
    }

    // 检查访问权限
    public function checkAccess($controller, $action) {
        foreach ($this->_roles as $role) {
            if ($this->_user->{$role->getName()}) {
                $allowed = $this->_acl->isAllowed($role->getName(), $controller, $action);
                if ($allowed != Acl::ALLOW) {
                    $this->_errorMessages[] = "Access denied to {$controller}::{$action}";
                    return false;
                }
                break;
            }
        }
        return true;
    }

    // 错误信息
    public function getErrorMessages() {
        return $this->_errorMessages;
    }
}

class IndexController extends ControllerBase {
    protected $_accessControl;

    public function initialize() {
        $this->_accessControl = new AccessControl($this->di);
    }

    public function indexAction() {
        if (!$this->_accessControl->checkAccess(__CLASS__, __FUNCTION__)) {
            $this->flash->error(join(', ', $this->_accessControl->getErrorMessages()));
            return $this->dispatcher->forward(array(
                'controller' => 'error',
                'action' => 'show404'
            ));
        }
    }
}
