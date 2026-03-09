<?php

namespace Villa1203\MenuDuJour;

use Kirby\Http\Response;
use Kirby\Exception\NotFoundException;

class SliderImages
{
    private static function dir(): string
    {
        $dir = __DIR__ . '/../data/slider-images';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }

    private static function jsonFile(): string
    {
        return __DIR__ . '/../data/slider-images.json';
    }

    private static function readOrder(): array
    {
        $file = self::jsonFile();
        if (!file_exists($file)) {
            return [];
        }
        $data = json_decode(file_get_contents($file), true);
        return is_array($data) ? $data : [];
    }

    private static function writeOrder(array $order): bool
    {
        return file_put_contents(self::jsonFile(), json_encode(array_values($order))) !== false;
    }

    public static function list(): array
    {
        $order = self::readOrder();
        $images = [];

        foreach ($order as $filename) {
            if (file_exists(self::dir() . '/' . $filename)) {
                $images[] = [
                    'filename' => $filename,
                    'url' => '/slider-images/' . $filename,
                ];
            }
        }

        return $images;
    }

    public static function upload(string $originalName, string $base64Data, string $mimeType): array
    {
        $allowedMimes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml',
        ];

        if (!in_array($mimeType, $allowedMimes)) {
            throw new \Exception('Type de fichier non autorisé');
        }

        $data = base64_decode($base64Data);
        if ($data === false) {
            throw new \Exception('Données invalides');
        }

        if (strlen($data) > 10 * 1024 * 1024) {
            throw new \Exception('Fichier trop volumineux (max 10 Mo)');
        }

        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!$ext) {
            $ext = 'jpg';
        }

        $filename = time() . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destination = self::dir() . '/' . $filename;

        file_put_contents($destination, $data);

        $order = self::readOrder();
        $order[] = $filename;
        self::writeOrder($order);

        return [
            'filename' => $filename,
            'url' => '/slider-images/' . $filename,
        ];
    }

    public static function reorder(array $filenames): bool
    {
        $existing = self::readOrder();
        $valid = array_filter($filenames, fn($f) => in_array(basename($f), $existing));
        $valid = array_map('basename', $valid);
        return self::writeOrder($valid);
    }

    public static function delete(string $filename): bool
    {
        $filename = basename($filename);
        $path = self::dir() . '/' . $filename;

        if (file_exists($path)) {
            unlink($path);
        }

        $order = self::readOrder();
        $order = array_filter($order, fn($f) => $f !== $filename);
        return self::writeOrder($order);
    }

    public static function serve(string $filename): Response
    {
        $filename = basename($filename);
        $path = self::dir() . '/' . $filename;

        if (!file_exists($path)) {
            throw new NotFoundException('Image not found');
        }

        $mime = mime_content_type($path);
        $content = file_get_contents($path);

        return new Response($content, $mime);
    }
}
