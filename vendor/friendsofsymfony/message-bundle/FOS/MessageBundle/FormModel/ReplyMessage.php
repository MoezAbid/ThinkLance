<?php

namespace FOS\MessageBundle\FormModel;

use FOS\MessageBundle\Model\ThreadInterface;

class ReplyMessage extends AbstractMessage
{
    /**
     * The thread we reply to.
     *
     * @var ThreadInterface
     */
    protected $thread;

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
