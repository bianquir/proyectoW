<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function getMediaFile($disk, $fileName)
    {
        $filePath = "private/media/$disk/$fileName";  // La ruta al archivo en el almacenamiento privado
        
        if (Storage::exists($filePath)) {
            $mimeType = Storage::mimeType($filePath);
            $fileContent = Storage::get($filePath);
            
            return response($fileContent, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
        }
    
        return abort(404, 'Archivo no encontrado');
    }
}    