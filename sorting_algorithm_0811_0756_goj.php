<?php
// 代码生成时间: 2025-08-11 07:56:20
class SortingAlgorithm {

    /**
     * Bubble Sort Implementation
     *
     * @param array $array The array to be sorted
     * @return array The sorted array
     */
    public function bubbleSort(array $array): array {
        $n = count($array);
        for ($i = 0; $i < $n - 1; $i++) {
            for ($j = 0; $j < $n - $i - 1; $j++) {
                if ($array[$j] > $array[$j + 1]) {
                    // Swap the elements
                    $temp = $array[$j];
                    $array[$j] = $array[$j + 1];
                    $array[$j + 1] = $temp;
                }
            }
        }
        return $array;
    }

    /**
     * Selection Sort Implementation
     *
     * @param array $array The array to be sorted
     * @return array The sorted array
     */
    public function selectionSort(array $array): array {
        $n = count($array);
        for ($i = 0; $i < $n - 1; $i++) {
            // Find the minimum element in the remaining array
            $min_index = $i;
            for ($j = $i + 1; $j < $n; $j++) {
                if ($array[$j] < $array[$min_index]) {
                    $min_index = $j;
                }
            }
            // Swap the found minimum element with the first element
            $temp = $array[$min_index];
            $array[$min_index] = $array[$i];
            $array[$i] = $temp;
        }
        return $array;
    }

    /**
     * Insertion Sort Implementation
     *
     * @param array $array The array to be sorted
     * @return array The sorted array
     */
    public function insertionSort(array $array): array {
        for ($i = 1; $i < count($array); $i++) {
            $key = $array[$i];
            $j = $i - 1;

            while ($j >= 0 && $array[$j] > $key) {
                $array[$j + 1] = $array[$j];
                $j--;
            }
            $array[$j + 1] = $key;
        }
        return $array;
    }

    /**
     * Quick Sort Implementation
     *
     * @param array $array The array to be sorted
     * @return array The sorted array
     */
    public function quickSort(array &$array): array {
        if (count($array) < 2) {
            return $array;
        }

        $pivot = array_shift($array);
        $less = array();
        $greater = array();

        foreach ($array as $item) {
            if ($item <= $pivot) {
                $less[] = $item;
            } else {
                $greater[] = $item;
            }
        }

        return array_merge($this->quickSort($less), array($pivot), $this->quickSort($greater));
    }

    // Add more sorting algorithms as needed...

    /**
     * Main function to test the sorting algorithms
     *
     * @param array $array The array to be sorted
     */
    public function testSorting(array $array): void {
        try {
            $sortedArrayBubble = $this->bubbleSort($array);
            $sortedArraySelection = $this->selectionSort($array);
            $sortedArrayInsertion = $this->insertionSort($array);
            $sortedArrayQuick = $this->quickSort($array);

            echo "Sorted Array (Bubble Sort): " . implode(", ", $sortedArrayBubble) . "
";
            echo "Sorted Array (Selection Sort): " . implode(", ", $sortedArraySelection) . "
";
            echo "Sorted Array (Insertion Sort): " . implode(", ", $sortedArrayInsertion) . "
";
            echo "Sorted Array (Quick Sort): " . implode(", ", $sortedArrayQuick) . "
";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

// Usage example
$sortingAlgorithm = new SortingAlgorithm();
$arrayToSort = array(64, 34, 25, 12, 22, 11, 90);
$sortingAlgorithm->testSorting($arrayToSort);
