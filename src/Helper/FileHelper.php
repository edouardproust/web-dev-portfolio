<?php

namespace App\Helper;

use ReflectionClass;

class FileHelper
{

    const FILE_TYPE = 'file';
    const IMAGE_TYPE = 'image';
    const GIF_TYPE = 'gif';
    const ICON_TYPE = 'icon';
    const VIDEO_TYPE = 'video';

    const UPLOAD_TYPES = [
        self::FILE_TYPE => [ // default
            'label' => 'File',
            'maxSize' => '2m',
            'mimeMsg' => 'File format {{ type }} is not allowed. Please use one of the following: {{ types }}.',
        ],
        self::IMAGE_TYPE => [
            'label' => 'Image',
            'ext' => ['jpg', 'jpeg', 'png', 'bmp', 'eps', 'tif', 'tiff', 'raw', 'cr2', 'nef', 'orf', 'sr2'],
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
        ]
    ];

    public static function getConstant(string $fileType)
    {
        return constant('self::' . $fileType);
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

    public static function getLabel(?string $fileName = null): string
    {
        if ($fileName) {
            foreach (self::UPLOAD_TYPES as $type) {
                $extension = self::getExtension($fileName);
                if (in_array($extension, $type['ext'] ?? [])) {
                    return $type['label'] . ' (' . $extension . ')';
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

    public function getMime(?string $fileName): ?string
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
