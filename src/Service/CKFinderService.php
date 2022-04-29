<?php

namespace App\Service;

use App\Path;
use stdClass;

class CKFinderService
{
    const REGEX_SEARCH = '/="\/uploads\/ckfinder[^>]+">/i';

    public function purgeLocalFiles()
    {
        $localFiles = $this->getLocalFilesList();
        $manifestFiles = $this->getManifestFilesList();
        // compare both
        // remove files that are in local but not in the manifest
    }

    public function UpdateManifestOnEntitySave(object $entity)
    {
        $entityClass = get_class($entity);
        $entityId = $entity->getId();

        // get a list of the files uploaded with ckfinder for this entity
        $content = $entity->getContent();
        preg_match_all(self::REGEX_SEARCH, $content, $matches);
        $pregResult = array_map(function ($match) {
            $match = str_replace('="', '', $match);
            return str_replace('">', '', $match);
        }, $matches);
        $pregResult = $pregResult[0];

        // obtenir la liste des fichiers du manifeste pour cette entité
        $content = $this->getManifestContent();

        // add entity to the manifest if not yet
        if (empty($content)) {
            $content = new stdClass;
        }
        if (!isset($content->$entityClass)) {
            $content->$entityClass = new stdClass;
        }
        if (!isset($content->$entityClass->$entityId)) {
            $content->$entityClass->$entityId = new stdClass;
        }

        // pour chaque fichier du manifest, si il n'est pas dans la tableau: le supprimer
        foreach ($content->$entityClass->$entityId as $i => $file) {
            if (!in_array($file, $pregResult)) {
                unset($content->$entityClass->$entityId->$i);
            }
        }

        // pour chaque fichier du tableau si il n'est pas dans le manifest: le rajouter
        foreach ($pregResult as $i => $match) {
            $content->$entityClass->$entityId->$i = $match;
        }

        // réecrire le manifest avec les nouvelles données
        $this->setManifestContent($content);
    }

    public function UpdateManifestOnEntityDelete(object $entity)
    {
        $entityClass = get_class($entity);
        $entityId = $entity->getId();
        $content = $this->getManifestContent();
        
        unset($content->$entityClass->$entityId);
        $this->setManifestContent($content);
    }

    private function getLocalFilesList(): array
    {
        $localFiles = [];
        return $localFiles;
    }

    private function getManifestFilesList(): array
    {
        $allFiles= [];
        foreach ($this->getManifestContent() as $entityClass) {
            foreach ($entityClass as $entity) {
                foreach ($entity as $file) {
                    if (!in_array($file, $allFiles)) {
                        $allFiles[] = $file;
                    }
                }
            }
        }
        return $allFiles;
    }
    
    private function getManifestDir()
    {
        return Path::APP_DIR() . '/ckfinder-manifest.json';
    }

    private function getManifestContent()
    {
        return json_decode(file_get_contents($this->getManifestDir()));
    }

    private function setManifestContent($content)
    {
        file_put_contents($this->getManifestDir(), json_encode($content, JSON_PRETTY_PRINT));
    }
}
