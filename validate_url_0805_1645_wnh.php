<?php
// 代码生成时间: 2025-08-05 16:45:17
use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Url as UrlValidator;

class ValidateUrlController extends Controller
{
    /**
     * 验证URL链接有效性的action
     *
     * @param Request $request
     * @return void
     */
    public function indexAction(Request $request)
    {
        // 获取输入的URL
        $url = $request->getPost('url', 'string');

        // 验证URL
        $validation = new Validation();

        $validation->add(
            'url',
            new UrlValidator(
                [
                'message' => 'The URL is not valid',
                ]
            )
        );

        $messages = $validation->validate($request->getPost());

        if (count($messages)) {
            // 如果验证失败，返回错误信息
            foreach ($messages as $message) {
                echo $message, "<br/>";
            }
        } else {
            // 如果验证成功，返回成功信息
            echo 'URL is valid';
        }
    }
}
