<?php


namespace Xigen\WordpressWidget\Block\Widget;

/**
 * Posts class
 */
class Posts extends \Magento\Framework\View\Element\Template
{
    /**
     * Default value for products per page
     */
    const DEFAULT_JSON_FEED = "http://domain.com/wp-json/wp/v2/posts?per_page=10&_embed";

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    private $serializer;

    /**
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    private $_curl;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Posts constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\HTTP\Client\Curl $curl
     * @param \Psr\Log\LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\HTTP\Client\Curl $curl,
        \Psr\Log\LoggerInterface $logger,
        array $data = []
    ) {
        $this->_curl = $curl;
        $this->logger = $logger;
        parent::__construct($context, $data);
    }

    /**
     * @return bool|mixed|string|void
     */
    public function getBlogPosts()
    {
        try {
            $this->_curl->setOption(CURLOPT_CONNECTTIMEOUT, 10);
            $this->_curl->setOption(CURLOPT_TIMEOUT, 10);
            $this->_curl->setOption(CURLOPT_SSL_VERIFYHOST, 0);
            $this->_curl->setOption(CURLOPT_SSL_VERIFYPEER, 0);
            $this->_curl->setOption(CURLOPT_RETURNTRANSFER, 1);
            $this->_curl->setOption(CURLOPT_FOLLOWLOCATION, 1);
            $this->_curl->get($this->getJsonFeed());
            $response = $this->_curl->getBody();
            if ($response === false) {
                return;
            }
            return \Zend_Json::decode($response, true);
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $e->getMessage();
        }
        return false;
    }

    /**
     * Retrieve how many products should be displayed
     * @return string
     */
    public function getJsonFeed()
    {
        if (!$this->hasData('json_feed')) {
            $this->setData('json_feed', self::DEFAULT_JSON_FEED);
        }
        return $this->getData('json_feed');
    }

    /**
     * Get key pieces for caching block content
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return array_merge(
            parent::getCacheKeyInfo(),
            [
                $this->getPostsCount(),
                $this->serializer->serialize($this->getRequest()->getParams())
            ]
        );
    }

    /**
     * Retrieve how many posts should be displayed
     * @return int
     */
    public function getPostsCount()
    {
        if (!$this->hasData('posts_count')) {
            return parent::getPostsCount();
        }
        return $this->getData('posts_count');
    }

    /**
     * Tidy text for excerpt
     * @param $string
     * @param int $length
     * @param string $delim
     * @return string
     */
    public function tidyText($string, $length = 90, $delim = '...')
    {
        $converted = mb_convert_encoding($string, 'HTML-ENTITIES', 'UTF-8');
        $stripped = preg_replace('/\[\w+\]/', '', $converted);
        return $this->neatTrim($stripped, $length, $delim);
    }
    
    /**
     * Neatly trim string
     * @param string $str
     * @param int $n
     * @param string $delim
     * @return string
     */
    public function neatTrim($str, $n, $delim = '...')
    {
        $len = strlen($str);
        if ($len > $n) {
            preg_match('/(.{' . $n . '}.*?)\b/', strip_tags($str), $matches);
            return rtrim($matches[1]) . $delim;
        } else {
            return $str;
        }
    }
}
