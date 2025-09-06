<?php
// 代码生成时间: 2025-09-06 16:14:59
use Phalcon\Html\Escaper;
use Phalcon\Di;
use Phalcon\Di\FactoryDefault;
use Phalcon\Html;
use Phalcon\Http\Request;
use Phalcon\Config;

// 配置服务容器
$di = new FactoryDefault();

// 配置EScapier服务
$di->setShared('escaper', function () {
    return new Escaper();
});

// 配置Request服务
$di->setShared('request', function () {
    return new Request();
});

// 配置Config服务
$config = new Config(array(
    'xss' => array(
        'blacklistTags' => array('script', 'iframe', 'object', 'embed', 'applet', 'base', 'meta', 'link', 'style', 'title', 'basefont'),
        'blacklistAttrs' => array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondeactivate', 'ondblclick', 'ondeselect', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmediacomplete', 'onmediaerror', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'),
    )
));
$di->setShared('config', function () use ($config) {
    return $config;
});

class XssProtection {
    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Config
     */
    protected $config;

    /**
     * Constructor
     *
     * @param Escaper $escaper
     * @param Request $request
     * @param Config $config
     */
    public function __construct(Escaper $escaper, Request $request, Config $config) {
        $this->escaper = $escaper;
        $this->request = $request;
        $this->config = $config;
    }

    /**
     * Sanitize input to prevent XSS attacks
     *
     * @param string $input
     * @return string
     */
    public function sanitizeInput($input) {
        try {
            // Escape HTML tags
            $input = $this->escaper->escapeHtml($input);

            // Remove blacklisted tags and attributes
            $blacklistTags = $this->config->xss->blacklistTags;
            $blacklistAttrs = $this->config->xss->blacklistAttrs;
            $input = $this->removeBlacklistedTagsAndAttrs($input, $blacklistTags, $blacklistAttrs);

            return $input;
        } catch (Exception $e) {
            // Handle error
            error_log($e->getMessage());
            return '';
        }
    }

    /**
     * Remove blacklisted tags and attributes
     *
     * @param string $input
     * @param array $blacklistTags
     * @param array $blacklistAttrs
     * @return string
     */
    protected function removeBlacklistedTagsAndAttrs($input, $blacklistTags, $blacklistAttrs) {
        // Remove blacklisted tags
        foreach ($blacklistTags as $tag) {
            $input = preg_replace('/<' . $tag . '\b[^>]*>(.*?)<\/' . $tag . '>/is', '', $input);
        }

        // Remove blacklisted attributes
        foreach ($blacklistAttrs as $attr) {
            $input = preg_replace('/\s+' . $attr . '\s*=\s*(\"[^\"]+\"|\\'[^\\']+\\'|[^\s>]+)/is', '', $input);
        }

        return $input;
    }
}

// Example usage
$xssProtection = new XssProtection($di->get('escaper'), $di->get('request'), $di->get('config'));

// Sanitize user input
$userInput = $xssProtection->sanitizeInput($_POST['user_input']);

echo $userInput;
