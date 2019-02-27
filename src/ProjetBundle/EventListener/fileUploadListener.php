<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 20/02/2019
 * Time: 02:22
 */

namespace ProjetBundle\EventListener;


use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use ProjetBundle\Entity\Projet;
use ProjetBundle\Service\FileUploader;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class fileUploadListener
{
    private $uploader;
    private $fileName;

    public function __construct(FileUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        $this->uploadFile($entity);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        // Retrieve Form as Entity
        $entity = $args->getEntity();

        // This logic only works for Product entities
        if (!$entity instanceof Projet) {
            return;
        }

        // Check which fields were changes
        $changes = $args->getEntityChangeSet();

        // Declare a variable that will contain the name of the previous file, if exists.
        $previousFilename = null;

        // Verify if the brochure field was changed
        if(array_key_exists("file", $changes)){
            // Update previous file name
            $previousFilename = $changes["file"][0];
        }

        // If no new brochure file was uploaded
        if(is_null($entity->getFile())){
            // Let original filename in the entity
            $entity->setFile($previousFilename);

            // If a new brochure was uploaded in the form
        }else{
            // If some previous file exist
            if(!is_null($previousFilename)){
                $pathPreviousFile = $this->uploader->getTargetDir(). "/". $previousFilename;

                // Remove it
                if(file_exists($pathPreviousFile)){
                    unlink($pathPreviousFile);
                }
            }

            // Upload new file
            $this->uploadFile($entity);
        }
    }

    private function uploadFile($entity)
    {
        // upload only works for Product entities
        if (!$entity instanceof Projet) {
            return;
        }

        $file = $entity->getFile();

        // only upload new files
        if ($file instanceof UploadedFile) {
            $fileName = $this->uploader->upload($file);

            $entity->setFile($fileName);
        }
    }

}