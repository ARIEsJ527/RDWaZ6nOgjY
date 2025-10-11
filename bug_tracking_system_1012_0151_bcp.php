<?php
// 代码生成时间: 2025-10-12 01:51:39
// 引入Phalcon框架的核心组件
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Controller;

// 定义Bug类，用于表示缺陷跟踪系统中的一个缺陷
class Bug extends Model {
    // 定义属性
    public $id;
    public $title;
    public $description;
    public $status;
    public $created_at;
    public $updated_at;

    // 定义模型的元数据
    public function initialize() {
        $this->setSource("bugs");
        $this->hasMany('id', 'BugStatus', 'bug_id', ['alias' => 'statuses']);
    }

    // 自定义验证规则
    public function validation() {
        // 验证标题
        $this->validate(new PresenceOf(array(
            'field' => 'title',
            'message' => 'Title is required'
        )));
        
        // 验证描述
        $this->validate(new PresenceOf(array(
            'field' => 'description',
            'message' => 'Description is required'
        )));
        
        // 验证状态
        $this->validate(new InclusionIn(array(
            'field' => 'status',
            'domain' => [
                'new',
                'in_progress',
                'resolved',
                'closed'
            ],
            'message' => 'Status must be one of the following: new, in_progress, resolved, closed'
        )));
        
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }
}

// 定义BugStatus类，用于表示缺陷跟踪系统中的一个缺陷状态
class BugStatus extends Model {
    // 定义属性
    public $id;
    public $bug_id;
    public $status;
    public $updated_at;

    // 定义模型的元数据
    public function initialize() {
        $this->belongsTo('bug_id', 'Bug', 'id', ['alias' => 'bug']);
    }
}

// 定义BugController类，用于处理缺陷跟踪系统的HTTP请求
class BugController extends Controller {
    // 显示所有缺陷
    public function indexAction() {
        // 获取所有缺陷
        $bugs = Bug::find();
        
        // 检查是否有缺陷
        if ($bugs) {
            // 将缺陷数据传递给视图
            $this->view->setVar('bugs', $bugs);
        } else {
            // 显示错误消息
            $this->flash->error('No bugs were found');
        }
    }

    // 创建新缺陷
    public function createAction() {
        // 创建新的缺陷实例
        $bug = new Bug();
        
        // 获取请求数据
        $postData = $this->request->getPost();
        
        // 将请求数据赋值给缺陷实例
        $bug->assign($postData);
        
        // 验证数据
        if ($bug->save()) {
            // 显示成功消息
            $this->flash->success('Bug was created successfully');
            return $this->response->redirect('bug/index');
        } else {
            // 显示错误消息
            foreach ($bug->getMessages() as $message) {
                $this->flash->error($message->getMessage());
            }
        }
    }

    // 更新缺陷
    public function updateAction($id) {
        // 根据ID查找缺陷
        $bug = Bug::findFirstById($id);
        
        if (!$bug) {
            // 显示错误消息
            $this->flash->error('Bug was not found');
            return $this->response->redirect('bug/index');
        }
        
        // 获取请求数据
        $postData = $this->request->getPost();
        
        // 将请求数据赋值给缺陷实例
        $bug->assign($postData);
        
        // 验证数据
        if ($bug->save()) {
            // 显示成功消息
            $this->flash->success('Bug was updated successfully');
            return $this->response->redirect('bug/index');
        } else {
            // 显示错误消息
            foreach ($bug->getMessages() as $message) {
                $this->flash->error($message->getMessage());
            }
        }
    }

    // 删除缺陷
    public function deleteAction($id) {
        // 根据ID查找缺陷
        $bug = Bug::findFirstById($id);
        
        if (!$bug) {
            // 显示错误消息
            $this->flash->error('Bug was not found');
            return $this->response->redirect('bug/index');
        }
        
        // 删除缺陷
        if ($bug->delete()) {
            // 显示成功消息
            $this->flash->success('Bug was deleted successfully');
        } else {
            // 显示错误消息
            foreach ($bug->getMessages() as $message) {
                $this->flash->error($message->getMessage());
            }
        }
        return $this->response->redirect('bug/index');
    }
}
