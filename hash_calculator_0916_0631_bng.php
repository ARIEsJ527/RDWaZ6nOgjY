<?php
// 代码生成时间: 2025-09-16 06:31:53
// HashCalculator.php
// 这是一个使用Phalcon框架实现的哈希值计算工具

use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\DiInterface;
use Phalcon\Cli\Console;
use Phalcon\Escaper;
use Phalcon\Cli\Task;

class HashCalculatorTask extends Task
{
    // 依赖注入容器
    protected $di;

    public function __construct(DiInterface $di = null)
    {
        if ($di === null) {
            $di = new FactoryDefault();
        }
        $this->di = $di;
    }

    // 计算哈希值的方法
    public function calculateAction($algorithm = 'sha256', $input = '')
    {
        try {
            // 检查输入是否为空
            if (empty($input)) {
                throw new \Exception('Input cannot be empty.');
            }

            // 检查算法是否有效
            if (!in_array($algorithm, hash_algos(), true)) {
                throw new \Exception('Invalid hashing algorithm.');
            }

            // 计算哈希值
            $hash = hash($algorithm, $input);

            // 输出结果
            $this->console->handle($hash);
        } catch (Exception $e) {
            // 错误处理
            $this->console->handle($e->getMessage());
        }
    }
}

// 设置加载器
$loader = new Loader();
$loader->registerDirs(
    array(
       $config->application->controllersDir,
       $config->application->modelsDir
    )
)->register();

// 设置视图服务
$di->setShared('view', function() {
    $view = new View();
    $view->setViewsDir($config->application->viewsDir);
    return $view;
});

// 设置Escaper服务
$di->setShared('escaper', function() {
    return new Escaper();
});

// 设置CLI Console
$console = new Console($di);

// 设置任务
$console->addTask(new HashCalculatorTask($di));

// 运行CLI Console
$console->handle(array(
    'task' => 'hashCalculator',
    'action' => 'calculate',
    'params' => array('sha256', 'Hello, World!')
));
