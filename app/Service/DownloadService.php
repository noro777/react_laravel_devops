<?php


namespace App\Service;

use App\Exceptions\DownloadException;


class DownloadService
{
    public function download($filename)
    {
        $s3 = \Storage::disk('s3');
        $file_path = $s3->url('/files/books/' . $filename);
        if (file_exists($file_path)) {
            return response()->download($file_path, $filename, [
                'Content-Length: ' . filesize($file_path)
            ]);
        } else {
            // Error
            throw new DownloadException(__('file1'));
        }
    }
}
