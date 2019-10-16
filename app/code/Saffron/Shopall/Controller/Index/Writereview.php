<?php

namespace Saffron\Shopall\Controller\Index;
use \Magento\Framework\App\Action\Action;
use Zend\Log\Filter\Timestamp;

class Writereview extends \Magento\Framework\App\Action\Action
{
	
	const XML_PATH_EMAIL_RECIPIENT_NAME = 'trans_email/ident_support/name';
    const XML_PATH_EMAIL_RECIPIENT_EMAIL = 'trans_email/ident_support/email';
	
	protected $_pageFactory;
	protected $_inlineTranslation;
    protected $_transportBuilder;
    protected $_scopeConfig;
    protected $_logLoggerInterface;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $loggerInterface,
		\Magento\Framework\View\Result\PageFactory $pageFactory,
		array $data = []
		)
	{
		$this->_pageFactory = $pageFactory;
		$this->_inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $transportBuilder;
        $this->_scopeConfig = $scopeConfig;
        $this->_logLoggerInterface = $loggerInterface;
        $this->messageManager = $context->getMessageManager();
		return parent::__construct($context);
	}
	
	
	
public function execute()
    {
    $data = $this->getRequest()->getPostValue();
	try
         {
		    
            // Send Mail
            $this->_inlineTranslation->suspend();
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $sender = [ 'email' => $data['email'],'name' => $data['firstname']];
            $sentToEmail = $this->_scopeConfig ->getValue('trans_email/ident_general/email',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $sentToName = $this->_scopeConfig ->getValue('trans_email/ident_general/name',\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
            $transport = $this->_transportBuilder
            ->setTemplateIdentifier(1)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
                )
                ->setTemplateVars([
                'Star Rating' => $data['star'],
                'user_name' => $data['user_name'],
				'email' => $data['email'],
				'review' => $data['review'],
                'age' => $data['age'],
                'skin' => $data['skin']
                ])
                ->setFrom($sender)
                ->addTo($sentToEmail,$sentToName)
                ->getTransport();
                $transport->sendMessage();
                $this->_inlineTranslation->resume();
                $this->messageManager->addSuccess('Email sent successfully');
				return $resultRedirect->setPath('thank-you/');
                 
        } catch(\Exception $e){
            $this->messageManager->addError($e->getMessage());
            $this->_logLoggerInterface->debug($e->getMessage());
            //exit;
        }
		catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            }
        catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            }
	
	
	
	
	//$this->_redirect('thank-you/');
	
	}
}