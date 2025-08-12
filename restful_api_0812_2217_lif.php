<?php
// 代码生成时间: 2025-08-12 22:17:11
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Model\Exception;
use Phalcon\Rest\Response;
use Phalcon\Di\FactoryDefault;

// RESTful API Controller
class ApiController extends Controller
{
    // 获取数据
    public function indexAction()
    {
        try {
            // 获取数据
            $items = Items::find();
            // 检查是否找到数据
            if ($items->count() == 0) {
                // 没有找到数据，返回404
                $this->response->setStatusCode(404, 'Not Found')->sendHeaders();
                return;
            }
            // 设置响应内容
            $response = new Response();
            $response->setJsonContent($items->toArray());
            return $response;
        } catch (Exception $e) {
            // 错误处理
            $this->response->setStatusCode(500, 'Internal Server Error')->sendHeaders();
            $response = new Response();
            $response->setJsonContent(['error' => $e->getMessage()]);
            return $response;
        }
    }

    // 获取单个数据
    public function showAction($id)
    {
        try {
            // 根据ID查找数据
            $item = Items::findFirstById($id);
            // 检查是否找到数据
            if (!$item) {
                // 没有找到数据，返回404
                $this->response->setStatusCode(404, 'Not Found')->sendHeaders();
                return;
            }
            // 设置响应内容
            $response = new Response();
            $response->setJsonContent($item->toArray());
            return $response;
        } catch (Exception $e) {
            // 错误处理
            $this->response->setStatusCode(500, 'Internal Server Error')->sendHeaders();
            $response = new Response();
            $response->setJsonContent(['error' => $e->getMessage()]);
            return $response;
        }
    }

    // 创建数据
    public function createAction()
    {
        try {
            // 获取请求数据
            $data = $this->request->getJsonRawBody();
            // 创建新数据
            $item = new Items();
            $item->assign($data);
            // 保存数据
            if (!$item->save()) {
                // 保存失败，返回错误信息
                $errors = $item->getMessages();
                $response = new Response();
                $response->setJsonContent(['error' => $errors]);
                return $response;
            }
            // 设置响应内容
            $response = new Response();
            $response->setJsonContent($item->toArray());
            $response->setStatusCode(201, 'Created')->sendHeaders();
            return $response;
        } catch (Exception $e) {
            // 错误处理
            $this->response->setStatusCode(500, 'Internal Server Error')->sendHeaders();
            $response = new Response();
            $response->setJsonContent(['error' => $e->getMessage()]);
            return $response;
        }
    }

    // 更新数据
    public function updateAction($id)
    {
        try {
            // 根据ID查找数据
            $item = Items::findFirstById($id);
            // 检查是否找到数据
            if (!$item) {
                // 没有找到数据，返回404
                $this->response->setStatusCode(404, 'Not Found')->sendHeaders();
                return;
            }
            // 获取请求数据
            $data = $this->request->getJsonRawBody();
            // 更新数据
            $item->assign($data);
            // 保存数据
            if (!$item->save()) {
                // 保存失败，返回错误信息
                $errors = $item->getMessages();
                $response = new Response();
                $response->setJsonContent(['error' => $errors]);
                return $response;
            }
            // 设置响应内容
            $response = new Response();
            $response->setJsonContent($item->toArray());
            return $response;
        } catch (Exception $e) {
            // 错误处理
            $this->response->setStatusCode(500, 'Internal Server Error')->sendHeaders();
            $response = new Response();
            $response->setJsonContent(['error' => $e->getMessage()]);
            return $response;
        }
    }

    // 删除数据
    public function deleteAction($id)
    {
        try {
            // 根据ID查找数据
            $item = Items::findFirstById($id);
            // 检查是否找到数据
            if (!$item) {
                // 没有找到数据，返回404
                $this->response->setStatusCode(404, 'Not Found')->sendHeaders();
                return;
            }
            // 删除数据
            if (!$item->delete()) {
                // 删除失败，返回错误信息
                $errors = $item->getMessages();
                $response = new Response();
                $response->setJsonContent(['error' => $errors]);
                return $response;
            }
            // 设置响应内容
            $response = new Response();
            $response->setJsonContent(['success' => 'Item deleted successfully']);
            return $response;
        } catch (Exception $e) {
            // 错误处理
            $this->response->setStatusCode(500, 'Internal Server Error')->sendHeaders();
            $response = new Response();
            $response->setJsonContent(['error' => $e->getMessage()]);
            return $response;
        }
    }
}
