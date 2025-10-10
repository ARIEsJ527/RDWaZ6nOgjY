<?php
// 代码生成时间: 2025-10-10 21:21:00
class IoTGatewayManager {

    /**
     * @var \Phalcon\Mvc\Model\Manager
     */
    protected $modelsManager;

    /**
     * Constructor
     *
     * @param \Phalcon\Mvc\Model\Manager $modelsManager
     */
    public function __construct(\Phalcon\Mvc\Model\Manager $modelsManager) {
        $this->modelsManager = $modelsManager;
    }

    /**
     * Add a new IoT gateway
     *
     * @param array $gatewayData
     * @return mixed
     */
    public function addGateway(array $gatewayData) {
        try {
            $gateway = $this->modelsManager->createBuilder()
                ->add('IoTGateways')
                ->setSource(new IoTGateways());

            foreach ($gatewayData as $field => $value) {
                $gateway->setField($field, $value);
            }

            return $gateway->save();
        } catch (\Phalcon\Mvc\Model\Exception $e) {
            // Handle the error, log it and return false
            \Phalcon\Logger::error($e->getMessage());
            return false;
        }
    }

    /**
     * Update an existing IoT gateway
     *
     * @param int $id
     * @param array $gatewayData
     * @return mixed
     */
    public function updateGateway($id, array $gatewayData) {
        try {
            $gateway = IoTGateways::findFirstById($id);
            if (!$gateway) {
                throw new \Exception('Gateway not found');
            }

            foreach ($gatewayData as $field => $value) {
                $gateway->$field = $value;
            }

            return $gateway->save();
        } catch (\Phalcon\Mvc\Model\Exception $e) {
            // Handle the error, log it and return false
            \Phalcon\Logger::error($e->getMessage());
            return false;
        }
    }

    /**
     * Delete an IoT gateway
     *
     * @param int $id
     * @return mixed
     */
    public function deleteGateway($id) {
        try {
            $gateway = IoTGateways::findFirstById($id);
            if (!$gateway) {
                throw new \Exception('Gateway not found');
            }

            return $gateway->delete();
        } catch (\Phalcon\Mvc\Model\Exception $e) {
            // Handle the error, log it and return false
            \Phalcon\Logger::error($e->getMessage());
            return false;
        }
    }

    /**
     * Get an IoT gateway by its ID
     *
     * @param int $id
     * @return mixed
     */
    public function getGatewayById($id) {
        try {
            return IoTGateways::findFirstById($id);
        } catch (\Phalcon\Mvc\Model\Exception $e) {
            // Handle the error, log it and return null
            \Phalcon\Logger::error($e->getMessage());
            return null;
        }
    }

    /**
     * Get all IoT gateways
     *
     * @return mixed
     */
    public function getAllGateways() {
        try {
            return IoTGateways::find();
        } catch (\Phalcon\Mvc\Model\Exception $e) {
            // Handle the error, log it and return null
            \Phalcon\Logger::error($e->getMessage());
            return null;
        }
    }
}
