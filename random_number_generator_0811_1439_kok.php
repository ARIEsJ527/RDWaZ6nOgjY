<?php
// 代码生成时间: 2025-08-11 14:39:48
// RandomNumberGenerator.php
// 使用PHALCON框架实现随机数生成器

use Phalcon\Mvc\Controller;
use Phalcon\Flash\Session as Flash;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Random;

class RandomNumberGeneratorController extends Controller
{
    // 生成随机数的方法
    public function indexAction()
    {
        // 获取请求中的参数
        $min = $this->request->getQuery('min', 'int', 0);
        $max = $this->request->getQuery('max', 'int', 100);
        $length = $this->request->getQuery('length', 'int', 10);
        $isSecure = $this->request->getQuery('secure', 'int', 0);

        // 验证参数
        $validation = new Validation();
        $validation->add('min', new PresenceOf(array(
            'message' => 'Minimum value is required'
        )));
        $validation->add('max', new PresenceOf(array(
            'message' => 'Maximum value is required'
        )));
        $validation->add('min', new Numericality(array(
            'message' => 'Minimum value must be a number'
        )));
        $validation->add('max', new Numericality(array(
            'message' => 'Maximum value must be a number'
        )));
        $validation->add('length', new PresenceOf(array(
            'message' => 'Length is required'
        )));
        $validation->add('length', new Numericality(array(
            'message' => 'Length must be a number'
        )));

        $messages = $validation->validate($this->request->getQuery());

        if (count($messages)) {
            $flash = new Flash($this->di);
            foreach ($messages as $message) {
                $flash->error($message->getMessage());
            }
            return $this->response->redirect('random-number-generator/index');
        }

        // 生成随机数
        $random = new Random($isSecure);
        $result = $random->number($length);

        // 返回结果
        $this->view->setVar('result', $result);
    }
}
