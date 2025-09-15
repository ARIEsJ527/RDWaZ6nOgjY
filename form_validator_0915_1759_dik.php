<?php
// 代码生成时间: 2025-09-15 17:59:05
class FormValidator {

    /**
     * 存储验证规则
     *
     * @var array
     */
    protected $rules = [];

    /**
     * 存储错误信息
     *
     * @var array
     */
    protected $errors = [];

    /**
     * 设置验证规则
     *
     * @param array $rules 验证规则数组
     */
    public function setRules($rules) {
        $this->rules = $rules;
    }

    /**
     * 验证表单数据
     *
     * @param array $data 要验证的数据
     * @return bool 返回验证是否通过
     */
    public function validate($data) {
        foreach ($this->rules as $field => $rule) {
            if (!isset($data[$field])) {
                $this->errors[] = "Field '{$field}' is required";
                continue;
            }

            $value = $data[$field];
            $rule = explode(':', $rule);
            $validator = $rule[0];
            $params = explode(',', $rule[1] ?? '');

            switch ($validator) {
                case 'string':
                    if (!is_string($value)) {
                        $this->errors[] = "Field '{$field}' must be a string";
                    }
                    break;
                case 'int':
                    if (!is_numeric($value) || (string)(int)$value !== $value) {
                        $this->errors[] = "Field '{$field}' must be an integer";
                    }
                    break;
                case 'email':
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $this->errors[] = "Field '{$field}' must be a valid email address";
                    }
                    break;
                case 'min':
                    if (strlen($value) < $params[0]) {
                        $this->errors[] = "Field '{$field}' must be at least {$params[0]} characters long";
                    }
                    break;
                case 'max':
                    if (strlen($value) > $params[0]) {
                        $this->errors[] = "Field '{$field}' must not exceed {$params[0]} characters";
                    }
                    break;
                default:
                    $this->errors[] = "Unknown validator '{$validator}'";
                    break;
            }
        }

        return empty($this->errors);
    }

    /**
     * 获取错误信息
     *
     * @return array 返回错误信息数组
     */
    public function getErrors() {
        return $this->errors;
    }
}

// 示例使用
$validator = new FormValidator();

// 设置验证规则
$validator->setRules([
    'username' => 'string:min:3',
    'email' => 'email',
    'age' => 'int',
    'password' => 'string:min:6',
]);

// 提交的表单数据
$data = [
    'username' => 'test',
    'email' => 'test@example.com',
    'age' => '25',
    'password' => 'password123',
];

// 验证表单数据
if ($validator->validate($data)) {
    echo "Validation passed";
} else {
    echo "Validation failed: ";
    print_r($validator->getErrors());
}
