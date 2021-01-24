<?php

namespace App\EventSubscriber;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Category;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;    
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Bridge\Twig\Mime\NotificationEmail;



    class EasyAdminSubscriber implements EventSubscriberInterface {
          
     /**
     * @var Security
     */
    private $security;

    private $mailer;
    private $adminEmail;
    private $UserRepository;

    public function __construct(Security $security,MailerInterface $mailer, string $adminEmail,UserRepository $UserRepository)
    {
        $this->security = $security;
        $this->mailer = $mailer;
        $this->adminEmail = $adminEmail;
        $this->UserRepository= $UserRepository;
    }

        public static function getSubscribedEvents(){
            return [
                BeforeEntityPersistedEvent::class => ['setDatetime'],
               // AfterEntityPersistedEvent::class => ['sendMail'],
    
            ];
        }

        public function setDatetime(BeforeEntityPersistedEvent $event){
            $entity = $event->getEntityInstance();
            if ($entity instanceof Category) {
                //$entity->setPostAuthor($this->security->getUser());
                $entity->setCreated(new \DateTime());
                $entity->setUpdated(new \DateTime());
            }
            if ($entity instanceof Product) {
                //$entity->setPostAuthor($this->security->getUser());
                $entity->setCreated(new \DateTime());
                $entity->setUpdated(new \DateTime());
            }
            
            return;
        }

        // public function sendMail(AfterEntityPersistedEvent $event)
        // {   
        //     $entity = $event->getEntityInstance();
        //    // $entity->setStatus("pending");
        //     //send mail after product assign to manager 
        //     if ($entity instanceof Product){

        //     //   $this->mailer->send((new NotificationEmail())
        //     //   ->subject('New product assigned')
        //     //   ->htmlTemplate('emails/notify.html.twig')
        //     //   ->from($this->adminEmail)
        //     //   ->to($this->adminEmail)
        //     //   //->to($entity->setStatus("pending")
        //     //   //->context(['comment' => "new Product assigned to you please review."])
        //     //   );
                
        //     $email = (new Email())
        //     ->from('aadeshsharma9991@gmail.com')
        //     ->to('aadeshsharma9991@gmail.com')
        //     ->subject('New product assigned')
        //     ->text('PIM please visit the site!')
        //     ->html('<p>This is system generated mail .</p>');

        //  $this->mailer->send($email);

        //     }

        
       // }
        
    }