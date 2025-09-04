<?php
// 代码生成时间: 2025-09-04 13:38:04
class WebContentScraper {

    /**
     * 抓取指定URL的内容
     *
     * @param string $url 要抓取的网页URL
     * @return string 抓取的内容
     */
    public function fetchContent($url) {
        try {
            // 使用Phalcon的Http\Client组件发送请求
            $client = new \Phalcon\Http\Client();
            $response = $client->get($url);

            // 检查HTTP状态码是否为200
            if ($response->getStatusCode() != 200) {
                throw new \Exception("Failed to fetch content: HTTP status code is not 200");
            }

            // 返回响应内容
            return $response->getBody();

        } catch (\Exception $e) {
            // 处理异常，返回错误信息
            return "Error: " . $e->getMessage();
        }
    }

    /**
     * 提取HTML中的特定标签内容
     *
     * @param string $html 要解析的HTML内容
     * @param string $selector CSS选择器
     * @return string 提取的内容
     */
    public function extractContent($html, $selector) {
        try {
            // 使用Phalcon的Html\Tag组件解析HTML
            $document = new \Phalcon\Html\Tag();
            $document->setHtml($html);
            $elements = $document->getElementsByTag($selector);

            // 组合提取的内容
            $content = '';
            foreach ($elements as $element) {
                $content .= $element->textContent;
            }
            return $content;

        } catch (\Exception $e) {
            // 处理异常，返回错误信息
            return "Error: " . $e->getMessage();
        }
    }
}

// 示例用法
$scraper = new WebContentScraper();
$url = 'https://www.example.com';
$content = $scraper->fetchContent($url);
$extractedContent = $scraper->extractContent($content, 'p');

echo $extractedContent;
