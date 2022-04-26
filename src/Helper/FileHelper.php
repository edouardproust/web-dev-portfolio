<?php

namespace App\Helper;

use App\Path;
use ReflectionClass;

class FileHelper
{
    const FILE_TYPE = 'file';
    const IMAGE_TYPE = 'image';
    const GIF_TYPE = 'gif';
    const ICON_TYPE = 'icon';
    const VIDEO_TYPE = 'video';
    const EMBED_TYPE = 'text';

    const EMBED_TYPE_VIMEO = 'vimeo';
    const EMBED_TYPE_YOUTUBE = 'youtube';

    const UPLOAD_TYPES = [
        self::FILE_TYPE => [ // default
            'label' => 'File',
            'maxSize' => '2m',
            'mimeMsg' => 'File format {{ type }} is not allowed. Please use one of the following: {{ types }}.',
        ],
        self::IMAGE_TYPE => [
            'label' => 'Image',
            'ext' => ['jpg', 'jpeg', 'png', 'webp', 'bmp', 'eps', 'tif', 'tiff', 'raw', 'cr2', 'nef', 'orf', 'sr2'],
            'mime' => 'image',
        ],
        self::GIF_TYPE => [
            'label' => 'Animated GIF',
            'ext' => ['gif'],
            'mime' => 'image'
        ],
        self::ICON_TYPE => [
            'label' => 'Icon',
            'ext' => ['ico', 'svg'],
            'mime' => 'image'
        ],
        self::VIDEO_TYPE => [
            'label' => 'Video',
            'ext' => ['mp4', 'mov', 'avi', 'flv', 'mkv', 'wmv', 'webm', 'avchd', 'h264', 'mpeg4'],
            'maxSize' => '5m',
            'mime' => 'video'
        ],
        self::EMBED_TYPE => [
            // Upload a text file containing the embed code inside
            // (use one of the recongnized extensions below or add your own ones)
            'label' => 'Embed',
            'ext' => ['embed', 'txt', 'html', 'htm', 'vimeo', 'youtube', 'plain'],
            'maxSize' => '1k',
            'mime' => 'text'
        ],
    ];

    /**
     * Get the file type based on it's name or url.
     * @param null|string $fileName The file name or url (must contain the file extension: .jpg, .mp4,...)
     * @return null|string The file type or null if the extension is not met in the UPLOAD_TYPES table
     * In cas of success, the string returned is sameas one of the consts:
     * FILE_TYPE, IMAGE_TYPE, GIF_TYPE, ICON_TYPE, VIDEO_TYPE, EMBED_TYPE,...
     */
    public static function getTypeFromExtension(?string $fileName): ?string
    {
        if ($fileName) {
            $extension = self::getExtension($fileName);
            $extensionsByType = [];
            foreach (self::UPLOAD_TYPES as $type => $data) {
                if ($type !== self::FILE_TYPE) {
                    $extensionsByType[$type] = $data['ext'];
                }
            }
            foreach ($extensionsByType as $type => $extensions) {
                foreach ($extensions as $ext) {
                    if ($extension === $ext) {
                        return $type;
                    }
                }
            }
        }
        return null;
    }

    public static function getAbsPath(?string $constPath = null, ?string $fileName = null)
    {
        $append = '';
        if ($fileName) {
            $append = '/' . $fileName;
        }
        if (!$constPath || empty($constPath)) {
            $constPath = '';
        } else {
            $constPath = constant('\App\Path' . '::' . $constPath);
        }
        $path = dirname(__DIR__, 2) . Path::PUBLIC . $constPath . $append;

        return $path;
    }

    public static function getConstant(string $fileType)
    {
        return constant('self::' . $fileType);
    }

    public static function getUplodadTypes()
    {
        return array_keys(self::UPLOAD_TYPES);
    }

    public static function getConstants()
    {
        $reflectionClass = new ReflectionClass(__CLASS__);
        return $reflectionClass->getConstants();
    }

    public static function getExtension(?string $fileName): ?string
    {
        $parts = explode(".", $fileName);
        return end($parts);
    }

    public static function getExtensions(string ...$fileType): array
    {
        $extensions = [];
        foreach ($fileType as $const) {
            $type = self::UPLOAD_TYPES[$const];
            foreach ($type['ext'] as $extension) {
                $extensions[] = $extension;
            }
        }
        return $extensions;
    }

    public static function getLabel(?string $fileName = null): string
    {
        if ($fileName) {
            foreach (self::UPLOAD_TYPES as $type) {
                $extension = self::getExtension($fileName);
                if (in_array($extension, $type['ext'] ?? [])) {
                    return $type['label'] . ' (' . ucFirst($extension) . ')';
                }
            }
        }
        return self::getDefault('label');
    }

    public static function getMaxSize(string $fileType): string
    {
        return
            self::get($fileType, 'maxSize')
            ?? self::getDefault('maxSize');
    }

    public static function getMime(?string $fileName): ?string
    {
        $fileExtension = self::getExtension($fileName);
        foreach (self::UPLOAD_TYPES as $type) {
            if (in_array($fileExtension, $type['ext'] ?? [])) {
                return $type['mime'] ?? null;
            }
        }
        return null;
    }

    public static function getMimeTypes(string ...$fileType): array
    {
        $mimeTypes = [];
        foreach ($fileType as $const) {
            $const = self::getConstant($const);
            $type = self::UPLOAD_TYPES[$const];
            foreach ($type['ext'] as $extension) {
                $mimeTypes[] = $type['mime'] . '/' . $extension;
            }
        }
        return $mimeTypes;
    }

    public static function getMimeTypesMessage(?string $fileType = null): string
    {
        return
            self::UPLOAD_TYPES[$fileType]['mimeMsg']
            ?? self::getDefault('mimeMsg');
    }

    private static function get(string $fileType, string $key)
    {
        $slug = self::getConstant($fileType);
        return self::UPLOAD_TYPES[$slug][$key] ?? null;
    }

    private static function getDefault(string $key)
    {
        return self::UPLOAD_TYPES[self::FILE_TYPE][$key] ?? null;
    }
}
