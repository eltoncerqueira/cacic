<?php

namespace Cacic\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notifications
 */
class Notifications
{
    /**
     * @var integer
     */
    private $idNotification;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $replyTo;

    /**
     * @var string
     */
    private $to;

    /**
     * @var string
     */
    private $body;

    /**
     * @var \DateTime
     */
    private $readDate;


    /**
     * Get idNotification
     *
     * @return integer 
     */
    public function getIdNotification()
    {
        return $this->idNotification;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return Notifications
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set from
     *
     * @param string $from
     * @return Notifications
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Get from
     *
     * @return string 
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set replyTo
     *
     * @param string $replyTo
     * @return Notifications
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;

        return $this;
    }

    /**
     * Get replyTo
     *
     * @return string 
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

    /**
     * Set to
     *
     * @param string $to
     * @return Notifications
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Get to
     *
     * @return string 
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return Notifications
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set readDate
     *
     * @param \DateTime $readDate
     * @return Notifications
     */
    public function setReadDate($readDate)
    {
        $this->readDate = $readDate;

        return $this;
    }

    /**
     * Get readDate
     *
     * @return \DateTime 
     */
    public function getReadDate()
    {
        return $this->readDate;
    }
    /**
     * @var string
     */
    private $notificationType;


    /**
     * Set notificationType
     *
     * @param string $notificationType
     * @return Notifications
     */
    public function setNotificationType($notificationType)
    {
        $this->notificationType = $notificationType;

        return $this;
    }

    /**
     * Get notificationType
     *
     * @return string 
     */
    public function getNotificationType()
    {
        return $this->notificationType;
    }
    /**
     * @var string
     */
    private $object;


    /**
     * Set object
     *
     * @param string $object
     * @return Notifications
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return string 
     */
    public function getObject()
    {
        return $this->object;
    }
    /**
     * @var string
     */
    private $notificationAcao;


    /**
     * Set notificationAcao
     *
     * @param string $notificationAcao
     * @return Notifications
     */
    public function setNotificationAcao($notificationAcao)
    {
        $this->notificationAcao = $notificationAcao;

        return $this;
    }

    /**
     * Get notificationAcao
     *
     * @return string 
     */
    public function getNotificationAcao()
    {
        return $this->notificationAcao;
    }
    /**
     * @var \Cacic\CommonBundle\Entity\Computador
     */
    private $idComputador;


    /**
     * Set idComputador
     *
     * @param \Cacic\CommonBundle\Entity\Computador $idComputador
     * @return Notifications
     */
    public function setIdComputador(\Cacic\CommonBundle\Entity\Computador $idComputador = null)
    {
        $this->idComputador = $idComputador;

        return $this;
    }

    /**
     * Get idComputador
     *
     * @return \Cacic\CommonBundle\Entity\Computador 
     */
    public function getIdComputador()
    {
        return $this->idComputador;
    }
    /**
     * @var string
     */
    private $fromAddr;


    /**
     * Set fromAddr
     *
     * @param string $fromAddr
     * @return Notifications
     */
    public function setFromAddr($fromAddr)
    {
        $this->fromAddr = $fromAddr;

        return $this;
    }

    /**
     * Get fromAddr
     *
     * @return string 
     */
    public function getFromAddr()
    {
        return $this->fromAddr;
    }
    /**
     * @var string
     */
    private $toAddr;


    /**
     * Set toAddr
     *
     * @param string $toAddr
     * @return Notifications
     */
    public function setToAddr($toAddr)
    {
        $this->toAddr = $toAddr;

        return $this;
    }

    /**
     * Get toAddr
     *
     * @return string 
     */
    public function getToAddr()
    {
        return $this->toAddr;
    }
    /**
     * @var \DateTime
     */
    private $creationDate;


    /**
     * Set creationDate
     *
     * @param \DateTime $creationDate
     * @return Notifications
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime 
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }
}
