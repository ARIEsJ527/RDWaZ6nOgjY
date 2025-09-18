<?php
// 代码生成时间: 2025-09-18 21:54:12
// url_validator.php
// 这是一个使用PHALCON框架的URL链接有效性验证程序。

use Phalcon\Mvc\Controller;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Url as UrlValidator;
use Phalcon\Http\Response;

class UrlValidatorController extends Controller
{
    // 验证URL链接的方法
    public function validateAction()
    {
        // 创建验证对象
        $validation = new Validation();
        
        // 添加URL验证器
        $validation->add('url', new UrlValidator(array(
            'message' => 'The URL is not valid'
        )));
        
        // 从请求中获取URL参数
        $url = $this->request->getPost('url', 'string');
        
        // 执行验证
        $messages = $validation->validate($this->request->getPost());
        
        // 检查是否有验证消息（错误）
        if (count($messages)) {
            // 返回错误消息
            $response = new Response();
            $response->setJsonContent(array(
                'status' => 'error',
                'messages' => $messages
            ));
            $response->send();
        } else {
            // 验证通过，返回成功消息
            $response = new Response();
            $response->setJsonContent(array(
                'status' => 'success',
                'message' => 'The URL is valid'
            ));
            $response->send();
        }
    }
}
