<?php
// 代码生成时间: 2025-08-31 12:42:35
class JsonDataConverter {

    /**
     * 将JSON格式的字符串转换成PHP数组
     *
     * @param string $jsonString JSON格式的字符串
     * @return array PHP数组
     * @throws Exception 如果JSON字符串无效或者转换失败
     */
    public function convertJsonToArray($jsonString) {
        try {
            $array = json_decode($jsonString, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON解码失败: ' . json_last_error_msg());
            }
            return $array;
        } catch (Exception $e) {
            throw new Exception('JSON数组转换失败: ' . $e->getMessage());
        }
    }

    /**
     * 将JSON格式的字符串转换成PHP对象
     *
     * @param string $jsonString JSON格式的字符串
     * @return object PHP对象
     * @throws Exception 如果JSON字符串无效或者转换失败
     */
    public function convertJsonToObject($jsonString) {
        try {
            $object = json_decode($jsonString);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON解码失败: ' . json_last_error_msg());
            }
            return $object;
        } catch (Exception $e) {
            throw new Exception('JSON对象转换失败: ' . $e->getMessage());
        }
    }

    /**
     * 将PHP数组转换成JSON格式的字符串
     *
     * @param array $array PHP数组
     * @return string JSON格式的字符串
     * @throws Exception 如果转换失败
     */
    public function convertArrayToJson($array) {
        try {
            $jsonString = json_encode($array);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON编码失败: ' . json_last_error_msg());
            }
            return $jsonString;
        } catch (Exception $e) {
            throw new Exception('数组转JSON失败: ' . $e->getMessage());
        }
    }

    /**
     * 将PHP对象转换成JSON格式的字符串
     *
     * @param object $object PHP对象
     * @return string JSON格式的字符串
     * @throws Exception 如果转换失败
     */
    public function convertObjectToJson($object) {
        try {
            $jsonString = json_encode($object);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON编码失败: ' . json_last_error_msg());
            }
            return $jsonString;
        } catch (Exception $e) {
            throw new Exception('对象转JSON失败: ' . $e->getMessage());
        }
    }
}
