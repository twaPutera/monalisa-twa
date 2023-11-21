<?php

namespace App\Helpers;

use File;
use Intervention\Image\Facades\Image;

class FileHelpers
{
    public static function saveFile($file, $path, $filename)
    {
        $file->move($path, $filename);

        // Get File Save Path
        $path_file_save = $path . '/' . $filename;

        // Get Width and Height Of Image
        $imageSize = getimagesize($path_file_save);
        $width = $imageSize[0] / 2;  // Lebar gambar
        $height = $imageSize[1] / 2; // Tinggi gambar

        // Read the uploaded image using file_get_contents
        $imageContents = file_get_contents($path_file_save);

        // Load the image from contents using Intervention Image
        $image = Image::make($imageContents)->resize($width, $height);

        // Compress the image
        $image->save($path_file_save, 50);

        return $filename;
    }

    public static function deleteFile($path)
    {
        if (file_exists($path)) {
            if (is_file($path)) {
                if (is_readable($path)) {
                    File::delete($path);

                    return true;
                }
            }
        }

        return false;
    }

    public static function viewFile($path, $filename)
    {
        $extesion = explode('.', $filename);

        if (file_exists($path)) {
            if (is_file($path)) {
                if (is_readable($path)) {
                    if (count($extesion) > 1) {
                        if (last($extesion) == 'pdf' || last($extesion) == 'png' || last($extesion) == 'jpg' || last($extesion) == 'jpeg') {
                            return response()->file($path);
                        }
                        return response()->download(
                            $path,
                            \Str::slug($filename) . '.' . last($extesion)
                        );
                    }
                    return response()->download(
                        $path
                    );
                }
            }
        }

        return response('FILE_NOT_FOUND', 404);
    }

    public static function downloadFile($path, $filename)
    {
        if (file_exists($path)) {
            if (is_file($path)) {
                if (is_readable($path)) {
                    return response()->download(
                        $path,
                        $filename
                    );
                }
            }
        }

        return response('FILE_NOT_FOUND', 404);
    }

    public static function removeFile($path)
    {
        if (file_exists($path)) {
            if (is_file($path)) {
                if (is_readable($path)) {
                    File::delete($path);

                    return true;
                }
            }
        }

        return false;
    }
}
