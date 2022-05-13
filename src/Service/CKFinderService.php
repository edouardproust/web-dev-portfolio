<?php

namespace App\Service;

use App\Path;
use stdClass;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CKFinderService
{
    const REGEX_SEARCH = '/="\/uploads\/ckfinder[^>]+">/i';
    const MANIFEST_PATH = '/ckfinder-manifest.json';
    // Subdirs of the CKFinder uploads dir defined in App\Path::UPLOADS_CKFINDER
    const SUBDIRS_TO_EXCLUDE_FROM_PURGE = [
        '.ckfinder',
        'images/__thumbs'
    ];

    private $flash;

    public function __construct(FlashBagInterface $flash)
    {
        $this->flash = $flash;
    }

    /**
     * Purge All local files that are not in the manifest any more
     * @return string Success message
     */
    public function purgeLocalFiles()
    {
        $localFiles = $this->getLocalFilesList();
        $manifestFiles = $this->getManifestFilesList();

        // get list of files to remve
        $filesToRemove = [];
        foreach ($localFiles as $file) {
            if (!in_array($file, $manifestFiles)) {
                $filesToRemove[] = self::getCKFinderUploadsDir() . $file;
            }
        }

        // remove them
        $errors = [];
        foreach ($filesToRemove as $file) {
            if (is_file($file) && @unlink($file)) {
                // ...
            } elseif (is_file($file)) {
                $errors[] = "Unlink failed: " . $file;
            } else {
                $errors[] = "File doesn't exist: " . $file;
            }
        }
        // Show a flash message
        if (empty($error)) {
            if (count($filesToRemove) > 0) {
                $this->flash->add('success', '<b>' . count($filesToRemove) . ' files have been deleted. Your storage is clean now.</b> Can you feel the breeze?');
            } else {
                $this->flash->add('success', 'The storage is already clean: No files have been deleted.');
            }
        } else {
            $message = '';
            foreach ($errors as $i => $error) {
                if ($i < 10) {
                    $message .= '<li>' . $error . '</li>';
                } elseif ($i === 10) {
                    $message .= '<li>' . '...' . '</li>';
                }
            }
            $this->flash->add('danger', '<b>Failed purging files!</b><ul>' . $message . '</ul>');
        }
    }

    public function updateManifestOnEntitySave(object $entity)
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

    private function getManifestFilesList(): array
    {
        $allFiles = [];
        foreach ($this->getManifestContent() as $entityClass) {
            foreach ($entityClass as $entity) {
                foreach ($entity as $file) {
                    $file = str_replace(Path::UPLOADS_CKFINDER, '', $file);
                    $file = urldecode($file);
                    if (!in_array($file, $allFiles)) {
                        $allFiles[] = $file;
                    }
                }
            }
        }
        return $allFiles;
    }

    private function getLocalFilesList(): array
    {

        // PROCESS ----------------------
        $rii = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(self::getCKFinderUploadsDir())
        );

        // 1. get list of files to keep (exceptions)
        $filesToPreserve = [];
        foreach ($rii as $file) {
            foreach (self::SUBDIRS_TO_EXCLUDE_FROM_PURGE as $dir) {
                // exclude directories & files
                if (
                    $file->isDir() ||
                    strpos($file->getPath(), self::getCKFinderUploadsDir() . '/' . $dir) !== false
                ) {
                    if (!in_array($file->getPathname(), $filesToPreserve)) {
                        $filesToPreserve[] = $file->getPathname();
                    }
                }
            }
        }
        // 2. get list of local files (= files not included in the list of files to preserve)
        $localFiles = [];
        foreach ($rii as $file) {
            if (!in_array($file, $filesToPreserve)) {
                $localFiles[] = str_replace(self::getCKFinderUploadsDir(), '', $file->getPathname());
            }
        }
        // 3. return
        return $localFiles;
    }
    
    private function getManifestDir()
    {
        return Path::APP_DIR() . self::MANIFEST_PATH;
    }

    private function getCKFinderUploadsDir()
    {
        return Path::APP_DIR() . '/public' . Path::UPLOADS_CKFINDER;
    }

    private function getManifestContent()
    {
        // create file if it does not exist
        if (!file_exists($this->getManifestDir())) {
            touch($this->getManifestDir());
        }
        return json_decode(file_get_contents($this->getManifestDir()));
    }

    private function setManifestContent($content)
    {
        file_put_contents($this->getManifestDir(), json_encode($content, JSON_PRETTY_PRINT));
    }
}
