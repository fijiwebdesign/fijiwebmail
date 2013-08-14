<?php
/**
 * Fiji Mail Server 
 *
 * @link      http://www.fijiwebdesign.com/
 * @copyright Copyright (c) 2010-2020 Fiji Web Design. (http://www.fijiwebdesign.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 * @package   Fiji_Mail
 */

namespace app\mail\form;
 
use Fiji\Factory;
use Fiji\App\Request;
use Fiji\Mail\AddressList;

use \Zend\Mail\Message;
use \Zend\Mime\Message as MimeMessage;
use \Zend\Mime\Part as MimePart;
use \Zend\Mail\Transport\Sendmail as SendmailTransport;
use \Zend\Mail\Transport\Smtp as SmtpTransport;
use \Zend\Mail\Transport\SmtpOptions;

/**
 * Compose email form handler
 */
class Compose
{
    
    protected $User;
    
    protected $App;
    
    protected $Req;
    
    protected $Imap;
    
    public function __construct()
    {
        $this->User = Factory::getSingleton('Fiji\App\User');
        $this->App = Factory::getSingleton('Fiji\App\Application');
        $this->Req = Factory::getSingleton('Fiji\App\Request');
        // configs @todo
        $this->Config = Factory::getSingleton('config\Mail');
        // user imap configs
        $options = $this->User->imapOptions;
        if (!$options) {
            $this->App->redirect('?app=auth');
        }
        $this->Imap = Factory::getSingleton('Fiji\Mail\Storage\Imap', array($options));
    }
    
    public function getMailTransport()
    {
        $messageer = $this->Config->get('mailTransport');
        if ($messageer == 'sendmail') {
            return $this->getSendmail();
        } else {
            return $this->getSMTP();
        }
    }
    
    protected function getSMTP()
    {
        
        $stmpOptions = $this->Config->get('mailTransportOptions');
        
        // Setup SMTP transport using LOGIN authentication
        $transport = new SmtpTransport();
        // @todo dynamic configuration
        // we should add a method to config/Mail to get as data/options
        // eg: $stmpOptions->toString() or $smtpOptions->toOptions()
        $ssl = $stmpOptions->get('connection_config');
        $ssl = $ssl['ssl'];
        $options   = new SmtpOptions(array(
            'name'=> $stmpOptions->get('name'),
            'host'=> $stmpOptions->get('host'),
            'port' => $stmpOptions->get('port'),
            'connection_class'  => $stmpOptions->get('connection_class'),
            'connection_config' => array(
                'username' => $this->User->username,
                'password' => $this->User->password,
                'ssl' => $ssl
            ),
        ));

        $transport->setOptions($options);
        return $transport;
    }
    
    protected function getSendmail()
    {
        $transport = new SendmailTransport();
        return $transport;
    }
    
    protected function generateMessageId()
    {
        return '<' . md5(microtime(true) . rand()) . '@' . $_SERVER['HTTP_HOST'] . '>';
    }
    
    public function sendComposed()
    {
        $to = $this->Req->getVar('to', '');
        $cc = $this->Req->getVar('cc', '');
        $bcc = $this->Req->getVar('bcc', '');
        $subject = $this->Req->getVar('subject', '');
        $body = $this->Req->getVar('body', '');
        $inReplyTo = $this->Req->getVar('In-Reply-To', '');
        
        // @todo configurable
        $saveFolder = $this->Config->get('folders')->get('sent', 'Sent Mail');
        
        $fromAddressList = new AddressList($this->User->username);
        $toAddressList = new AddressList($to);
        $ccAddressList = new AddressList($cc);
        $bccAddressList = new AddressList($bcc);
        
        // zend mail message
        $message = new Message();
        $message->setEncoding("UTF-8");
        
        if ($cc) {
            $message->addCc($ccAddressList);
        }
        if ($bcc) {
            $message->addBcc($bccAddressList);
        }
        $message->getHeaders()->addHeaderLine('In-Reply-To', $inReplyTo);
        $message->getHeaders()->addHeaderLine('Message-ID', $this->generateMessageId());

        $text = new MimePart(strip_tags($body));
        $text->type = "text/plain";
        
        $html = new MimePart($body);
        $html->type = "text/html";
        
        /*
        $image = new MimePart(fopen($pathToImage, 'r'));
        $image->type = "image/jpeg";
         */
        
        $body = new MimeMessage();
        $body->setParts(array($html));
 
        $message->setFrom($fromAddressList)
             ->setSubject($subject)
             ->setBody($body);
             
        $message->addTo($toAddressList);
        
        $transport = $this->getMailTransport();
        
        $transport->send($message);
        
        if ($saveFolder) {
            
            // create sent folder if not exist
            if (!$this->Imap->folderExists($saveFolder)) {
                $this->Imap->createFolder($saveFolder);
            }
            
            // @todo config
            $this->Imap->appendMessage($message->toString(), $saveFolder, array());
        }
            
        $this->App->redirect('?app=mail&page=mailbox', 'Your message has been sent.');
        
        
    }
    
    
}
