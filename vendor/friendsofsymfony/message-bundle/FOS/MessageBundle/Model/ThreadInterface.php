<?php

namespace FOS\MessageBundle\Model;

use Doctrine\Common\Collections\Collection;

interface ThreadInterface extends ReadableInterface
{
    /**
     * Gets the message unique id.
     *
     * @return mixed
     */
    public function getId();

    /**
     * @return string
     */
    public function getSubject();

    /**
     * @param string
     */
    public function setSubject($subject);

    /**
     * Gets the messages contained in the thread.
     *
     * @return MessageInterface[]|Collection
     */
    public function getMessages();

    /**
     * Adds a new message to the thread.
     */
    public function addMessage(MessageInterface $message);

    /**
     * Gets the first message of the thread.
     *
     * @return MessageInterface
     */
    public function getFirstMessage();

    /**
     * Gets the last message of the thread.
     *
     * @return MessageInterface
     */
    public function getLastMessage();

    /**
     * Gets the participant that created the thread
     * Generally the sender of the first message.
     *
     * @return ParticipantInterface
     */
    public function getCreatedBy();

    /**
     * Sets the participant that created the thread
     * Generally the sender of the first message.
     */
    public function setCreatedBy(ParticipantInterface $participant);

    /**
     * Gets the date this thread was created at
     * Generally the date of the first message.
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Sets the date this thread was created at
     * Generally the date of the first message.
     */
    public function setCreatedAt(\DateTime $createdAt);

    /**
     * Gets the users participating in this conversation.
     *
     * @return ParticipantInterface[]|Collection
     */
    public function getParticipants();

    /**
     * Tells if the user participates to the conversation.
     *
     * @param ParticipantInterface $participant
     *
     * @return bool
     */
    public function isParticipant(ParticipantInterface $participant);

    /**
     * Adds a participant to the thread
     * If it already exists, nothing is done.
     */
    public function addParticipant(ParticipantInterface $participant);

    /**
     * Tells if this thread is deleted by this participant.
     *
     * @return bool
     */
    public function isDeletedByParticipant(ParticipantInterface $participant);

    /**
     * Sets whether or not this participant has deleted this thread.
     *
     * @param ParticipantInterface $participant
     * @param bool                 $isDeleted
     */
    public function setIsDeletedByParticipant(ParticipantInterface $participant, $isDeleted);

    /**
     * Sets the thread as deleted or not deleted for all participants.
     *
     * @param bool $isDeleted
     */
    public function setIsDeleted($isDeleted);

    /**
     * Get the participants this participant is talking with.
     *
     * @param ParticipantInterface $participant
     *
     * @return ParticipantInterface[]
     */
    public function getOtherParticipants(ParticipantInterface $participant);
}
