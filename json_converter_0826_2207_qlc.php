<?php
// 代码生成时间: 2025-08-26 22:07:55
class JsonConverter
{

    /**
     * 将JSON字符串转换为PHP数组
     *
     * @param string $jsonString JSON字符串
     * @return array
     * @throws InvalidArgumentException 如果JSON字符串无效
     */
    public function convertJsonToArray(string $jsonString): array
    {
        // 检查JSON字符串是否有效
        if (!is_string($jsonString) || !json_decode($jsonString)) {
            throw new InvalidArgumentException('Invalid JSON string');
        }

        // 将JSON字符串解码为PHP数组
        return json_decode($jsonString, true);
    }

    /**
     * 将PHP数组转换为JSON字符串
     *
     * @param array $array PHP数组
     * @return string
     * @throws InvalidArgumentException 如果数组无效
     */
    public function convertArrayToJson(array $array): string
    {
        // 检查数组是否有效
        if (!is_array($array)) {
            throw new InvalidArgumentException('Invalid array');
        }

        // 将PHP数组编码为JSON字符串
        return json_encode($array);
    }

}
