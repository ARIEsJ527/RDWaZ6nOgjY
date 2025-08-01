<?php
// 代码生成时间: 2025-08-01 21:50:35
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Exception;
use Phalcon\Mvc\ResponseInterface;
use Phalcon\Validation;
use Phalcon\Validation\ValidationInterface;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Mvc\Response;

/**
 * RESTful API Controller
 *
 * @package Controllers
 */
class ApiController extends Controller
{

    /**
     * Handle GET requests to retrieve data
     *
     * @param string $id
     * @return ResponseInterface
     */
    public function getAction(string $id = null): ResponseInterface
    {
        try {
            if ($id) {
                $result = Model::findFirstById($id);
                if (!$result) {
                    return $this->createErrorResponse("Resource not found", 404);
                }
                return $this->createApiResponse(200, $result);
            } else {
                $results = Model::find();
                if (!$results) {
                    return $this->createErrorResponse("No data found", 404);
                }
                return $this->createApiResponse(200, $results);
            }
        } catch (Exception $e) {
            return $this->createErrorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Handle POST requests to create new data
     *
     * @param array $data
     * @return ResponseInterface
     */
    public function postAction(array $data): ResponseInterface
    {
        try {
            $model = new Model();
            if (!$this->validateData($data, $model)) {
                return $this->createErrorResponse("Invalid data", 422);
            }
            $model->assign($data);
            if (!$model->save()) {
                return $this->createErrorResponse(\$model->getMessages(), 500);
            }
            return $this->createApiResponse(201, $model);
        } catch (Exception $e) {
            return $this->createErrorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Handle PUT requests to update data
     *
     * @param string $id
     * @param array $data
     * @return ResponseInterface
     */
    public function putAction(string $id, array $data): ResponseInterface
    {
        try {
            $result = Model::findFirstById($id);
            if (!$result) {
                return $this->createErrorResponse("Resource not found", 404);
            }
            if (!$this->validateData($data, $result)) {
                return $this->createErrorResponse("Invalid data", 422);
            }
            $result->assign($data);
            if (!$result->update()) {
                return $this->createErrorResponse(\$result->getMessages(), 500);
            }
            return $this->createApiResponse(200, $result);
        } catch (Exception $e) {
            return $this->createErrorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Handle DELETE requests to delete data
     *
     * @param string $id
     * @return ResponseInterface
     */
    public function deleteAction(string $id): ResponseInterface
    {
        try {
            $result = Model::findFirstById($id);
            if (!$result) {
                return $this->createErrorResponse("Resource not found", 404);
            }
            if (!$result->delete()) {
                return $this->createErrorResponse("Failed to delete", 500);
            }
            return $this->createApiResponse(200, null);
        } catch (Exception $e) {
            return $this->createErrorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Validate data using Phalcon\Mvc\Validation component
     *
     * @param array $data
     * @param Model $model
     * @return bool
     */
    private function validateData(array $data, Model $model): bool
    {
        $validation = new Validation();
        $validation->add(\$model->getDi()->getShared('validation'));
        $validation->add('email', new PresenceOf(array(
            'message' => 'Email is required'
        )));
        $validation->add('email', new Email(array(
            'message' => 'Email is not valid'
        )));
        
        $messages = $validation->validate($data);
        if (count($messages)) {
            return false;
        }
        return true;
    }

    /**
     * Create API response
     *
     * @param int $statusCode
     * @param mixed $data
     * @return 
     */
    private function createApiResponse(int $statusCode, $data): ResponseInterface
    {
        $response = new Response();
        $response->setJsonContent(array(
            'status' => 'success',
            'data' => $data
        ));
        $response->setStatusCode($statusCode, 'OK');
        return $response;
    }

    /**
     * Create error response
     *
     * @param string $message
     * @param int $statusCode
     * @return 
     */
    private function createErrorResponse(string $message, int $statusCode): ResponseInterface
    {
        $response = new Response();
        $response->setJsonContent(array(
            'status' => 'error',
            'message' => $message
        ));
        $response->setStatusCode($statusCode, 'OK');
        return $response;
    }
}
