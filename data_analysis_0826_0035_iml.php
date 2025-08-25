<?php
// 代码生成时间: 2025-08-26 00:35:00
class DataAnalysis {

    private $data;

    /**
     * 构造函数
     *
     * @param array $data 初始数据数组
     */
    public function __construct($data) {
        $this->data = $data;
    }

    /**
     * 计算平均值
     *
     * @return float 平均值
     */
    public function calculateMean() {
        if (empty($this->data)) {
            throw new Exception('Data array is empty');
        }

        $sum = array_sum($this->data);
        $count = count($this->data);

        return $sum / $count;
    }

    /**
     * 计算中位数
     *
     * @return float 中位数
     */
    public function calculateMedian() {
        if (empty($this->data)) {
            throw new Exception('Data array is empty');
        }

        sort($this->data);
        $count = count($this->data);
        $middleIndex = floor(($count - 1) / 2);

        if ($count % 2) {
            return $this->data[$middleIndex];
        } else {
            return ($this->data[$middleIndex] + $this->data[$middleIndex + 1]) / 2;
        }
    }

    /**
     * 计算众数
     *
     * @return mixed 众数
     */
    public function calculateMode() {
        if (empty($this->data)) {
            throw new Exception('Data array is empty');
        }

        $frequency = array_count_values($this->data);
        arsort($frequency);
        $maxFrequency = key($frequency);

        return array_keys($frequency, $maxFrequency);
    }

    /**
     * 计算标准差
     *
     * @return float 标准差
     */
    public function calculateStandardDeviation() {
        if (empty($this->data)) {
            throw new Exception('Data array is empty');
        }

        $mean = $this->calculateMean();
        $variance = array_sum(array_map(function($value) use ($mean) {
            return pow($value - $mean, 2);
        }, $this->data)) / count($this->data);

        return sqrt($variance);
    }
}

// 使用示例
try {
    $data = [10, 20, 30, 40, 50];
    $analysis = new DataAnalysis($data);

    echo 'Mean: ' . $analysis->calculateMean() . "
";
    echo 'Median: ' . $analysis->calculateMedian() . "
";
    echo 'Mode: ' . implode(', ', $analysis->calculateMode()) . "
";
    echo 'Standard Deviation: ' . $analysis->calculateStandardDeviation() . "
";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
