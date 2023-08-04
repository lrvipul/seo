<?php
namespace TDD\Seo\Observer;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class NoindexNofollow implements ObserverInterface
{
    protected $request;

    protected $layoutFactory;

    protected $urlInterface;

    protected $scopeConfig;

    protected $serialize;

    const URL_LIST_RAW_PATH = 'robots/config/url_list_row';

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Serialize\Serializer\Json $serialize,
        \Magento\Framework\View\Page\Config $layoutFactory)
    {
            $this->request = $request;
            $this->urlInterface = $urlInterface;
            $this->scopeConfig = $scopeConfig;
            $this->serialize = $serialize;
            $this->layoutFactory = $layoutFactory;
    }

    public function execute(Observer $observer)
    {
        $string = $this->request->getRequestUri();
        $fullActionName = $observer->getFullActionName();

        $urlListArray = $this->getConfigUrlList();
        //echo "<pre>"; print_r($urlListArray);
        
        if($urlListArray)
        {
            $newString = trim($string, '/');

            if (in_array($newString, $urlListArray))
            {
                $this->layoutFactory->setRobots('NOINDEX,NOFOLLOW');
            }
        }

    }

    public function getConfigUrlList()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $linkArray = [];
        $linkString = $this->scopeConfig->getValue(self::URL_LIST_RAW_PATH, $storeScope);
        if($linkString != '')
        {
            $linkData = $unserializedata = $this->serialize->unserialize($linkString);
            foreach ($linkData as $key => $linkValue) {

               $linkArray[] = $linkValue['label'];
            }

        }
        return $linkArray;

    }
}