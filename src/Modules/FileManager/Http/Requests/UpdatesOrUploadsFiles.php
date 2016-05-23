<?php

namespace MezzoLabs\Mezzo\Modules\FileManager\Http\Requests;


trait UpdatesOrUploadsFiles
{
    public function processFileNameAndFolder()
    {
        if ($this->has('folder')){
            $this->offsetSet('folder', str_replace('.', '', trim($this->get('folder'), '/')));
        }

        if ($this->has('filename')) {
            $this->offsetSet('filename', str_replace('..', '', trim($this->get('filename'), '/')));


        }
    }
}