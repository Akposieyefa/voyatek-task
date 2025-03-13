<?php

namespace App\Helpers;

class FileProcessing
{
    /**
     * push image to folder
     * @param $request
     * @param $file
     * @return string
     */
    public function pushImageFile($request, $file): string
    {
        $filenameWithExt = $request->file("$file")->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME );
        $extension = $request->file("$file")->getClientOriginalExtension();
        $fileNameToStore = $filename  .'_'.time().'.'.$extension;
        $destination = base_path() . "/files";
        $request->file("$file")->move($destination, $fileNameToStore);
        return $fileNameToStore;
    }

}
