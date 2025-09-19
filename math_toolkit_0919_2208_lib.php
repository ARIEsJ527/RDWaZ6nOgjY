<?php
// 代码生成时间: 2025-09-19 22:08:41
class MathToolkit {

    /**
     * Add two numbers.
     *
     * @param float $a
     * @param float $b
     * @return float
     */
    public function add($a, $b) {
        return $a + $b;
    }

    /**
     * Subtract two numbers.
     *
     * @param float $a
     * @param float $b
     * @return float
     */
    public function subtract($a, $b) {
        return $a - $b;
    }

    /**
     * Multiply two numbers.
     *
     * @param float $a
     * @param float $b
     * @return float
     */
    public function multiply($a, $b) {
        return $a * $b;
    }

    /**
     * Divide two numbers.
     *
     * @param float $a
     * @param float $b
     * @return float
     */
    public function divide($a, $b) {
        if ($b == 0) {
            throw new <?=
>InvalidArgumentException('Cannot divide by zero.');
        }

        return $a / $b;
    }

    /**
     * Calculate the power of a number.
     *
     * @param float $base
     * @param float $exponent
     * @return float
     */
    public function power($base, $exponent) {
        return pow($base, $exponent);
    }
}

// Example usage:
try {
    $mathToolkit = new MathToolkit();
    echo 'Add: ' . $mathToolkit->add(5, 3) . <?=
>';
    echo 'Subtract: ' . $mathToolkit->subtract(5, 3) . <?=
>';
    echo 'Multiply: ' . $mathToolkit->multiply(5, 3) . <?=
>';
    echo 'Divide: ' . $mathToolkit->divide(5, 3) . <?=
>';
    echo 'Power: ' . $mathToolkit->power(2, 3) . <?=
>';
} catch (InvalidArgumentException $e) {
    echo 'Error: ' . $e->getMessage();
}

?>