<?php
// 代码生成时间: 2025-08-30 12:17:00
use Phalcon\Http\Client;
use Phalcon\Mvc\User\Component;

class NetworkConnectionChecker extends Component
{
    /**
     * Checks the network connection status by pinging a URL.
     *
     * @param string $url The URL to ping for connection check.
     * @return bool Returns true if the connection is successful, false otherwise.
     */
    public function checkConnection(string $url): bool
    {
        try {
            // Initialize the HTTP client
            $client = new Client();
            
            // Set the URL to ping
            $response = $client->request('GET', $url);
            
            // Check if the response status is successful (200 OK)
            if ($response->getStatusCode() === 200) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            // Handle exceptions and log errors
            $this->logger->error('Network connection check failed: ' . $e->getMessage());
            return false;
        }
    }
}
