<?php
// 代码生成时间: 2025-08-14 16:42:49
use Phalcon\Mvc\Controller;

class MemoryUsageAnalyzerController extends Controller
{
    /**
     * Get the current memory usage
     *
     * @return string
     */
    public function getCurrentMemoryUsage()
    {
        try {
            $memoryUsage = memory_get_usage();
            return $this->response->setContent(json_encode($memoryUsage));
        } catch (Exception $e) {
            // Handle error
            return $this->response->setContent(json_encode(['error' => $e->getMessage()]));
        }
    }

    /**
     * Get the peak memory usage
     *
     * @return string
     */
    public function getPeakMemoryUsage()
    {
        try {
            $peakMemoryUsage = memory_get_peak_usage();
            return $this->response->setContent(json_encode($peakMemoryUsage));
        } catch (Exception $e) {
            // Handle error
            return $this->response->setContent(json_encode(['error' => $e->getMessage()]));
        }
    }
}
