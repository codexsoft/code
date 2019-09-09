<?php

namespace CodexSoft\Code\Helpers;

use CodexSoft\Code\Context\Context;
use Psr\Log\LoggerInterface;

class Files
{

    /**
     * Получить список файлов, расположенных в заданном пути.
     * Если $recursively === true, встретившиеся директории будут рекурсивно развернуты.
     *
     * @param string $path
     * @param bool $recursively
     *
     * @return string[]|array
     */
    public static function listFiles(string $path,bool $recursively = true): array
    {
        $files = [];

        if (is_dir($path) && $handle = opendir($path)) {
            while (($name = readdir($handle)) !== false) {
                /** @noinspection NotOptimalRegularExpressionsInspection */
                if (!preg_match("#^\.#",$name)) {
                    if ($recursively && is_dir($path.'/'.$name)) {
                        $files[$name] = self::listFiles($path.'/'.$name);
                    } else {
                        $files[] = $name;
                    }
                }
            }
            closedir($handle);
        }

        return $files;
    }

    /**
     * Получить список файлов, расположенных в заданном пути.
     * Если $recursively === true, встретившиеся директории будут рекурсивно развернуты.
     *
     * @param string $path
     * @param bool $recursively
     *
     * @param string $prefix
     *
     * @param string $pattern
     *
     * @return string[]|array
     */
    public static function listFilesWithPath(string $path, bool $recursively = true, string $prefix = '', $pattern = "#^\.#"): array
    {
        $files = [];

        if (is_dir($path) && $handle = opendir($path)) {
            while (($name = readdir($handle)) !== false) {
                /** @noinspection NotOptimalRegularExpressionsInspection */
                if (!preg_match($pattern,$name)) {
                    if ($recursively && is_dir($path.'/'.$name)) {
                        \array_push($files, ...self::listFilesWithPath($path.'/'.$name, $recursively, $prefix ? $prefix.'/'.$name.'/' : $name.'/', $pattern));
                        //$files[$name] = self::listFilesWithPath($path.'/'.$name, $recursively, $prefix ? $prefix.'/'.$name : $name);
                    } else {
                        $files[] = $prefix.$name;
                    }
                }
            }
            closedir($handle);
        }

        return $files;
    }

    /**
     * @param $file
     * First of all, equality is the == operator - you're doing the reverse check right now. But even
     * then, emptiness isn't an absolute, you might run into a fake-empty text-file which actually has
     * a newline or a UTF-8 BOM (byte-order mark).
     * @return bool
     */
    public static function fileNotEmpty( $file )
    {
        clearstatcache();
        return filesize($file) >= 16;
    }

    public static function fileExtention( $fileName )
    {
        return pathinfo($fileName,PATHINFO_EXTENSION);
    }

    public static function fileName( $fileName )
    {
        return pathinfo( $fileName, PATHINFO_FILENAME );
    }

    //public function removeExtension( $fileName ): string
    //{
    //    $dirname = \dirname($fileName);
    //    if ($dirname !== '.') {
    //        $dirname .= '/';
    //    } else {
    //        $dirname = '';
    //    }
    //    return $dirname.pathinfo( $fileName, PATHINFO_FILENAME );
    //}

    public static function removeExtension($fileName ): string
    {
        $dirname = \dirname($fileName);
        if ($dirname !== '.') {
            $dirname .= '/';
        } else {
            $dirname = '';
        }
        return $dirname.pathinfo( $fileName, PATHINFO_FILENAME );
    }

    /**
     * @param string $fileAbsolutePath абсолютный путь к подключаемому файлу
     * @param null $defaultValue
     *
     * @return mixed|null
     */
    public static function safelyInclude( $fileAbsolutePath, $defaultValue = null )
    {

        if ( file_exists($fileAbsolutePath) ) {
            try {
                /** @noinspection PhpIncludeInspection */
                $settings = include $fileAbsolutePath;
                return $settings;
            } catch(\Throwable $e) {

                // todo: стоит ли обращаться к Context отсюда?
                $logger = Context::getOrNull(LoggerInterface::class);
                if ($logger instanceof LoggerInterface) {
                    $logger->error( 'Settings file not found: '.$fileAbsolutePath.'!' );
                }
            }
        }

        return $defaultValue;

    }

    public static function safelyGetContents( $filename, $default = '' ) {

        try {
            if ( file_exists($filename) ) {
                return file_get_contents( $filename );
            }
        } catch (\Throwable $e) {}

        return $default;

    }

    /**
     * @param $filename
     * @param array $replacements
     *
     * @return bool|mixed|string
     * @throws \Exception
     */
    public static function fillTemplate( $filename, array $replacements )
    {

        if (!file_exists($filename)) {
            throw new \Exception('Source template file not exists: '.$filename);
        }

        $content = file_get_contents( $filename );
        foreach ( $replacements as $placeholder => $replacement ) {
            $content = str_replace( $placeholder, $replacement, $content );
        }

        return $content;

    }

}