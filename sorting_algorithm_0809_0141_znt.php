<?php
// 代码生成时间: 2025-08-09 01:41:45
class SortingAlgorithm {

    private $data;

    // Constructor to initialize the data array
    public function __construct(array $data) {
        $this->data = $data;
    }

    // Bubble Sort implementation
    public function bubbleSort() {
        $n = count($this->data);
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($this->data[$j] > $this->data[$j + 1]) {
                    // Swap the elements
                    $temp = $this->data[$j];
                    $this->data[$j] = $this->data[$j + 1];
                    $this->data[$j + 1] = $temp;
                }
            }
        }
        return $this->data;
    }

    // Insertion Sort implementation
    public function insertionSort() {
        for ($i = 1; $i < count($this->data); $i++) {
            $key = $this->data[$i];
            $j = $i - 1;

            while ($j >= 0 && $this->data[$j] > $key) {
                $this->data[$j + 1] = $this->data[$j];
                $j--;
            }
            $this->data[$j + 1] = $key;
        }
        return $this->data;
    }

    // Quick Sort implementation using recursion
    public function quickSort() {
        if (count($this->data) <= 1) {
            return $this->data;
        }

        $pivot = $this->data[0];
        $less = array();
        $more = array();

        for ($i = 1; $i < count($this->data); $i++) {
            if ($this->data[$i] < $pivot) {
                $less[] = $this->data[$i];
            } else {
                $more[] = $this->data[$i];
            }
        }

        return array_merge($this->quickSort($less), array($pivot), $this->quickSort($more));
    }

    // Merge Sort implementation using recursion
    public function mergeSort() {
        if (count($this->data) <= 1) {
            return $this->data;
        }

        $mid = floor(count($this->data) / 2);
        $left = array_slice($this->data, 0, $mid);
        $right = array_slice($this->data, $mid);

        $left = $this->mergeSort($left);
        $right = $this->mergeSort($right);

        return $this->merge($left, $right);
    }

    // Merge function for Merge Sort
    private function merge($left, $right) {
        $result = array();
        while (count($left) > 0 && count($right) > 0) {
            if ($left[0] < $right[0]) {
                $result[] = array_shift($left);
            } else {
                $result[] = array_shift($right);
            }
        }

        while (count($left) > 0) {
            $result[] = array_shift($left);
        }

        while (count($right) > 0) {
            $result[] = array_shift($right);
        }

        return $result;
    }

}

// Example usage:
$data = array(5, 3, 8, 4, 2);
$sortingAlgorithm = new SortingAlgorithm($data);

echo "Bubble Sort: ";
print_r($sortingAlgorithm->bubbleSort());

echo "Insertion Sort: ";
print_r($sortingAlgorithm->insertionSort());

echo "Quick Sort: ";
print_r($sortingAlgorithm->quickSort());

echo "Merge Sort: ";
print_r($sortingAlgorithm->mergeSort());
