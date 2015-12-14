<?php namespace Robot\Utils;

use Image as BaseImage;
use Config;

class Image
{
    public static function upload($file, $id)
    {
        if (! $file) {
            return ['errors' => 'Error while uploading file'];
        }

        $allowed_extensions = ["png", "jpg", "gif"];
        if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
            return ['errors' => 'You may only upload png, jpg or gif.'];
        }

        $fileName        = $file->getClientOriginalName();
        $extension       = $file->getClientOriginalExtension() ?: 'png';
        $folderName      = 'uploads/images/' . date("Ym", time()) .'/'.date("d", time()) .'/'. $id;
        $destinationPath = public_path() . '/' . $folderName;
        $safeName        = str_random(10).'.'.$extension;
        $file->move($destinationPath, $safeName);

        // If is not gif file, we will try to reduse the file size
        if ($file->getClientOriginalExtension() != 'gif') {
            // open an image file
            $img = BaseImage::make($destinationPath . '/' . $safeName);
            // prevent possible upsizing
            $img->resize(1440, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            // finally we save the image as a new file
            $img->save();
        }

        return ['filename' => getUserStaticDomain() . $folderName .'/'. $safeName];
    }
}
