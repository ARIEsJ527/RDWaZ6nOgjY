<?php
// 代码生成时间: 2025-08-30 04:37:33
// Inventory Management System using PHP and Phalcon Framework

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\DI;
use Phalcon\Logger;
use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Mvc\Controller;

class Inventory extends Model
{
    // Properties for inventory items
    public $id;
    public $name;
    public $quantity;
    public $price;

    // Initialize the inventory model
    public function initialize()
    {
        $this->setSource('inventory'); // Set the table name
    }

    // Validation rules for inventory items
    public function validation()
    {
        $validator = new Validation();
        $validator->add('name', new PresenceOf(['message' => 'Name is required']));
        $validator->add('quantity', new PresenceOf(['message' => 'Quantity is required']));
        $validator->add('price', new PresenceOf(['message' => 'Price is required']));
        $validator->add('name', new StringLength(['min' => 3, 'message' => 'Name must be at least 3 characters long']));

        return $this->validate($validator);
    }
}

class InventoryController extends Controller
{
    // Get all inventory items
    public function indexAction()
    {
        try {
            $inventoryItems = Inventory::find();
            $this->view->setVar('inventoryItems', $inventoryItems);
        } catch (Exception $e) {
            $this->flash->error('Error retrieving inventory items: ' . $e->getMessage());
            return $this->dispatcher->forward(['controller' => 'error', 'action' => 'show404']);
        }
    }

    // Add a new inventory item
    public function addAction()
    {
        if ($this->request->isPost()) {
            try {
                $inventoryItem = new Inventory();
                $inventoryItem->assign($this->request->getPost());

                if ($inventoryItem->save()) {
                    $this->flash->success('New inventory item added successfully');
                    return $this->response->redirect('inventory/index');
                } else {
                    $messages = $inventoryItem->getMessages();
                    foreach ($messages as $message) {
                        $this->flash->error($message);
                    }
                }
            } catch (Exception $e) {
                $this->flash->error('Error adding inventory item: ' . $e->getMessage());
                return $this->dispatcher->forward(['controller' => 'error', 'action' => 'show404']);
            }
        }
    }

    // Edit an existing inventory item
    public function editAction($id)
    {
        try {
            $inventoryItem = Inventory::findFirstById($id);
            if (!$inventoryItem) {
                $this->flash->error('Inventory item not found');
                return $this->response->redirect('inventory/index');
            }

            if ($this->request->isPost()) {
                $inventoryItem->assign($this->request->getPost());

                if ($inventoryItem->save()) {
                    $this->flash->success('Inventory item updated successfully');
                    return $this->response->redirect('inventory/index');
                } else {
                    $messages = $inventoryItem->getMessages();
                    foreach ($messages as $message) {
                        $this->flash->error($message);
                    }
                }
            }
            $this->view->setVar('inventoryItem', $inventoryItem);
        } catch (Exception $e) {
            $this->flash->error('Error editing inventory item: ' . $e->getMessage());
            return $this->dispatcher->forward(['controller' => 'error', 'action' => 'show404']);
        }
    }

    // Delete an inventory item
    public function deleteAction($id)
    {
        try {
            $inventoryItem = Inventory::findFirstById($id);
            if (!$inventoryItem) {
                $this->flash->error('Inventory item not found');
                return $this->response->redirect('inventory/index');
            }

            if ($inventoryItem->delete()) {
                $this->flash->success('Inventory item deleted successfully');
            } else {
                $messages = $inventoryItem->getMessages();
                foreach ($messages as $message) {
                    $this->flash->error($message);
                }
            }
            return $this->response->redirect('inventory/index');
        } catch (Exception $e) {
            $this->flash->error('Error deleting inventory item: ' . $e->getMessage());
            return $this->dispatcher->forward(['controller' => 'error', 'action' => 'show404']);
        }
    }
}

// Error controller to handle 404 errors
class ErrorController extends Controller
{
    public function show404Action()
    {
        $this->view->setRenderLevel(View::LEVEL_NO_RENDER);
    }
}
