<?php
// 代码生成时间: 2025-10-11 21:34:02
// 在线考试系统

// 使用 Phalcon 的自动加载器
$loader = new \Phalcon\Loader();
$loader->registerDirs(
    array(
        '../app/controllers/',
        '../app/models/'
    )
)->register();

// 使用 Phalcon 的服务容器
\Phalcon\Di::reset();
$di = new \Phalcon\Di();

// 设置视图服务
$di->setShared('view', function() {
    $view = new \Phalcon\Mvc\View();
    $view->setViewsDir('../app/views/');
    return $view;
});

// 设置数据库服务
$di->set('db', function(){
     return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
         'host' => 'localhost',
         'username' => 'root',
         'password' => '',
         'dbname' => 'online_exam'
     ));
});

// 设置路由服务
$di->set('router', function(){
    $router = new \Phalcon\Mvc\Router();
    $router->add('/', array(
        'controller' => 'index',
        'action' => 'index'
    ));
    $router->add('/questions', array(
        'controller' => 'questions',
        'action' => 'index'
    ));
    $router->add('/questions/:id', array(
        'controller' => 'questions',
        'action' => 'view',
        'params' => array(1)
    ));
    return $router;
});

// 启动 Phalcon 应用程序
$application = new \Phalcon\Mvc\Application($di);
echo $application->handle()->getContent();

// 控制器
class IndexController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
        $this->view->render('index', 'index');
    }
}

class QuestionsController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
        // 获取问题列表
        $questions = \Phalcon\Mvc\Model::queryBuilder()
            ->from('Question')
            ->execute();
        
        // 将问题列表传递给视图
        $this->view->setVar('questions', $questions);
        $this->view->render('questions', 'index');
    }

    public function viewAction($id)
    {
        // 获取问题详情
        $question = \Phalcon\Mvc\Model::findFirstById('Question', $id);
        if ($question) {
            // 将问题详情传递给视图
            $this->view->setVar('question', $question);
            $this->view->render('questions', 'view');
        } else {
            // 处理错误
            $this->flash->error('Question not found');
            $this->response->redirect('questions');
        }
    }
}

// 模型
class Question extends \Phalcon\Mvc\Model
{
    // 问题ID
    public $id;
    // 问题描述
    public $description;
    // 选项A
    public $option_a;
    // 选项B
    public $option_b;
    // 选项C
    public $option_c;
    // 选项D
    public $option_d;
    // 正确答案
    public $correct_answer;
}

// 视图文件
/*
views/index/index.phtml
<!DOCTYPE html>
<html>
<head>
    <title>Online Exam System</title>
</head>
<body>
    <h1>Welcome to the Online Exam System</h1>
    <p><a href="/questions">View Questions</a></p>
</body>
</html>

views/questions/index.phtml
<!DOCTYPE html>
<html>
<head>
    <title>Questions</title>
</head>
<body>
    <h1>Questions</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Options</th>
            <th>Correct Answer</th>
        </tr>
        {% for question in questions %}
        <tr>
            <td>{{ question.id }}</td>
            <td>{{ question.description }}</td>
            <td>{{ question.option_a }}, {{ question.option_b }}, {{ question.option_c }}, {{ question.option_d }}</td>
            <td>{{ question.correct_answer }}</td>
        </tr>
        {% endfor %}
    </table>
</body>
</html>

views/questions/view.phtml
<!DOCTYPE html>
<html>
<head>
    <title>Question Details</title>
</head>
<body>
    <h1>Question Details</h1>
    <p>ID: {{ question.id }}</p>
    <p>Description: {{ question.description }}</p>
    <p>Options: {{ question.option_a }}, {{ question.option_b }}, {{ question.option_c }}, {{ question.option_d }}</p>
    <p>Correct Answer: {{ question.correct_answer }}</p>
</body>
</html>
*/
