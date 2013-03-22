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

use \Zend\Mail\Message;
use \Zend\Mime\Message as MimeMessage;
use \Zend\Mime\Part as MimePart;
use \Zend\Mail\Transport\Sendmail as SendmailTransport;
use \Zend\Mail\Transport\Smtp as SmtpTransport;
use \Zend\Mail\Transport\SmtpOptions;

/**
 * Login Actions
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
    
    protected function getSMTP()
    {        
        // Setup SMTP transport using LOGIN authentication
        $transport = new SmtpTransport();
        // @todo configuration
        $options   = new SmtpOptions(array(
            'name'=> 'smtp.gmail.com',
            'host'=> 'smtp.gmail.com',
            'port' => 587,
            'connection_class'  => 'login',
            'connection_config' => array(
                'username' => $this->User->username,
                'password' => $this->User->password,
                'ssl' => 'tls'
            ),
        ));
        var_dump($options);
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
        $saveFolder = 'Sent Mail';
        
        $from = new \Fiji\Mail\Address($this->User->username);
        //$to = new \Fiji\Mail\Address($to);
        
        /*
        echo '<pre>';
        var_dump($this->User);
        var_dump($from);
        var_dump(array($to, $subject, $body));
        //return;
         */
        
        $mail = new Message();
        
        $mail->getHeaders()->addHeaderLine('Cc', $cc);
        $mail->getHeaders()->addHeaderLine('Bcc', $bcc);
        $mail->getHeaders()->addHeaderLine('In-Reply-To', $inReplyTo);
        $mail->getHeaders()->addHeaderLine('Message-ID', $this->generateMessageId());

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
 
        $mail->setFrom($from->email, $from->name)
             ->addTo($to)
             ->setSubject('Re: ' . $subject)
             ->setBody($body);
        
        $transport = $this->getSendmail();
        $transport->send($mail);
        
        if ($saveFolder) {
            
            // create sent folder if not exist
            if (!$this->Imap->folderExists($saveFolder)) {
                $this->Imap->createFolder($saveFolder);
            }
            
            // @todo config
            $this->Imap->appendMessage($mail->toString(), $saveFolder, array());
        }
            
        $this->App->redirect('?app=mail&page=inbox', 'Your message has been sent.');
        
        
    }
    
    
}
