<?php

namespace FOS\MessageBundle\Entity;

use FOS\MessageBundle\Model\ThreadInterface;
use FOS\MessageBundle\Model\ThreadMetadata as BaseThreadMetadata;

abstract class ThreadMetadata extends BaseThreadMetadata
{
    protected $id;
    protected $thread;

    /**
     * Gets the thread map id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ThreadInterface
     */
    public function getThread()
    {
        return $this->thread;
    }

    public function setThread(ThreadInterface $thread)
    {
        $this->thread = $thread;
    }
}
