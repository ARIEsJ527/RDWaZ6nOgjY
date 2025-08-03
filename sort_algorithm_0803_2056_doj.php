<?php
// 代码生成时间: 2025-08-03 20:56:03
use Phalcon\Mvc\Model;

class SortAlgorithm extends Model
{

    /**
     * @var array
     */
    private $arrayToSort;

    /**
     * Constructor
     *
     * @param array $arrayToSort
     */
    public function __construct(array $arrayToSort)
    {
        $this->arrayToSort = $arrayToSort;
    }

    /**
     * Sorts the array using Bubble Sort algorithm
     *
     * @return array
     */
    public function bubbleSort()
    {
        try {
            $length = count($this->arrayToSort);

            for ($i = 0; $i < $length - 1; $i++) {
                for ($j = 0; $j < $length - $i - 1; $j++) {
                    if ($this->arrayToSort[$j] > $this->arrayToSort[$j + 1]) {
                        // Swap elements
                        $temp = $this->arrayToSort[$j];
                        $this->arrayToSort[$j] = $this->arrayToSort[$j + 1];
                        $this->arrayToSort[$j + 1] = $temp;
                    }
                }
            }

            return $this->arrayToSort;

        } catch (Exception $e) {
            // Handle exception
            return "Error: " . $e->getMessage();
        }
    }

    /**
     * Sorts the array using Quick Sort algorithm
     *
     * @return array
     */
    public function quickSort()
    {
        try {
            $arrayToSort = $this->arrayToSort;
            $this->quickSortHelper($arrayToSort, 0, count($arrayToSort) - 1);

            return $arrayToSort;

        } catch (Exception $e) {
            // Handle exception
            return "Error: " . $e->getMessage();
        }
    }

    /**
     * Helper function for Quick Sort algorithm
     *
     * @param array $array
     * @param int $low
     * @param int $high
     * @return void
     */
    private function quickSortHelper(array &$array, $low, $high)
    {
        if ($low < $high) {
            $pi = $this->partition($array, $low, $high);

            if ($pi - 1 > $low) {
                $this->quickSortHelper($array, $low, $pi - 1);
            }

            if ($pi + 1 < $high) {
                $this->quickSortHelper($array, $pi + 1, $high);
            }
        }
    }

    /**
     * Partition function for Quick Sort algorithm
     *
     * @param array $array
     * @param int $low
     * @param int $high
     * @return int
     */
    private function partition(array &$array, $low, $high)
    {
        $pivot = $array[$high];
        $i = ($low - 1);

        for ($j = $low; $j <= $high - 1; $j++) {
            if ($array[$j] < $pivot) {
                $i++;
                $t = $array[$i];
                $array[$i] = $array[$j];
                $array[$j] = $t;
            }
        }

        $t = $array[$i + 1];
        $array[$i + 1] = $array[$high];
        $array[$high] = $t;

        return $i + 1;
    }

}
