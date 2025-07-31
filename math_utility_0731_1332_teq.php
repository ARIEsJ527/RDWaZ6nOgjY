<?php
// 代码生成时间: 2025-07-31 13:32:50
// MathUtility.php
// 这个类提供了一组基本的数学计算功能。
class MathUtility
{
    // 加法运算
    public function add($a, $b)
    {
        // 检查输入是否为数值
        if (!is_numeric($a) || !is_numeric($b)) {
            throw new \Exception('Both arguments must be numeric');
        }

        return $a + $b;
    }

    // 减法运算
    public function subtract($a, $b)
    {
        if (!is_numeric($a) || !is_numeric($b)) {
            throw new \Exception('Both arguments must be numeric');
        }

        return $a - $b;
    }

    // 乘法运算
    public function multiply($a, $b)
    {
        if (!is_numeric($a) || !is_numeric($b)) {
            throw new \Exception('Both arguments must be numeric');
        }

        return $a * $b;
    }

    // 除法运算
    public function divide($a, $b)
    {
        if (!is_numeric($a) || !is_numeric($b) || $b == 0) {
            throw new \Exception('Both arguments must be numeric and divisor cannot be zero');
        }

        return $a / $b;
    }

    // 幂运算
    public function power($base, $exponent)
    {
        if (!is_numeric($base) || !is_numeric($exponent)) {
            throw new \Exception('Both base and exponent must be numeric');
        }

        return pow($base, $exponent);
    }

    // 平方根运算
    public function squareRoot($number)
    {
        if (!is_numeric($number) || $number < 0) {
            throw new \Exception('The number must be a non-negative numeric value');
        }

        return sqrt($number);
    }
}
