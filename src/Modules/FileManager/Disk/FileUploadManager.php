<?php


namespace MezzoLabs\Mezzo\Modules\FileManager\Disk;

use Illuminate\Http\Request as IlluminateRequest;
use Illuminate\Support\Collection;
use MezzoLabs\Mezzo\Core\Files\File;
use MezzoLabs\Mezzo\Core\Helpers\StringHelper;
use MezzoLabs\Mezzo\Core\Validation\Validator;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Exceptions\FileUploadException;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Exceptions\FileUploadValidationFailedException;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Exceptions\MaximumFileSizeExceededException;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Exceptions\MimeTypeNotAllowedException;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Exceptions\UploadedFileEmptyException;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Uploaders\AwsS3Uploader;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Uploaders\FileUploaderContract;
use MezzoLabs\Mezzo\Modules\FileManager\Disk\Uploaders\LocalFolderUploader;
use MezzoLabs\Mezzo\Modules\FileManager\Domain\Repositories\FileRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadManager
{
    public static $uploaders = [
        'local' => LocalFolderUploader::class,
        's3' => AwsS3Uploader::class
    ];

    /**
     * @var \Illuminate\Validation\Validator
     */
    protected $lastValidation;


    /**
     * Creates the file uploader singleton.
     * This service will help you to upload a file to the file system of the server and register it in the database.
     */
    public function __construct()
    {

    }

    /**
     * Upload the file in the current request.
     *
     * Read the request and upload the file.
     *
     * @param IlluminateRequest $request
     * @return \App\File
     * @throws FileUploadException
     * @throws FileUploadValidationFailedException
     * @throws UploadedFileEmptyException
     */
    public function uploadInput(IlluminateRequest $request, string $disk = 'local')
    {
        $data = [
            'title' => $request->get('title', ''),
            'folder' => $request->get('folder', ''),
        ];

        if (!$request->hasFile('file'))
            throw new UploadedFileEmptyException("There is no file to upload.");


        return $this->upload($data, $request->file('file'), $this->makeUploader($disk));
    }

    /**
     * Upload a file and save a connected record to the database.
     *
     * @param array $metaData
     * @param UploadedFile $file
     * @param FileUploaderContract $uploader
     * @return \App\File
     * @throws Exceptions\FileManagerException
     * @throws FileUploadException
     * @throws FileUploadValidationFailedException
     * @throws MaximumFileSizeExceededException
     * @throws MimeTypeNotAllowedException
     */
    public function upload(array $metaData, UploadedFile $file, FileUploaderContract $uploader)
    {
        $fileValidation = $this->validateFile($file);
        if (!$fileValidation)
            throw new FileUploadException('File validation failed.');

        $metaData = new Collection($metaData);
        $data = new Collection();

        $data->put('extension', $this->extension($file));
        $data->put('folder', $metaData->get('folder', ''));
        $data->put('filename', $this->uniqueFileName($file, $data['folder']));

        $title = $metaData->get('title', "");
        if (empty($title))
            $title = File::removeExtension($data->get('filename'));

        $data->put('title', $title);
        $data->put('disk', $uploader->key());
        $data->put('info', $this->fileInfoJson($file));

        if (!$this->validateData($data))
            throw new FileUploadValidationFailedException($this->lastValidation);

        $newFile = $this->repository()->create($data->toArray());

        $path = StringHelper::path($data->get('folder'), $data->get('filename'));

        $fileSaved = $uploader->upload($path, $file, $newFile);

        if (!$newFile || !$fileSaved)
            throw new FileUploadException('Unexpected error during file upload.');

        return $newFile;
    }

    /**
     * Validate the file size and the mimetype.
     *
     * @param UploadedFile $file
     * @return bool
     * @throws MaximumFileSizeExceededException
     * @throws MimeTypeNotAllowedException
     */
    public function validateFile(UploadedFile $file)
    {
        if ($file->getClientSize() > $this->maximumFileSize())
            throw new MaximumFileSizeExceededException($file->getClientSize(), $this->maximumFileSize());

        if (!$this->mimeTypeAllowed($file->getClientMimeType()))
            throw new MimeTypeNotAllowedException($file->getClientMimeType());

        return true;
    }

    public function maximumFileSize()
    {
        return 10 * 1000 * 1000;
    }

    /**
     * Check if the mimeType is allowed.
     *
     * @param string $mimeType
     * @return bool
     */
    public function mimeTypeAllowed($mimeType)
    {
        return in_array($mimeType, [
            'image/gif', 'image/jpeg', 'image/png', 'text/markdown', 'text/plain', 'audio/mpeg',
            'video/mpeg', 'audio/x-wav', 'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ]);
    }

    /**
     * @param UploadedFile $file
     * @return null|string
     */
    protected function extension(UploadedFile $file)
    {
        $guessed = $file->guessClientExtension();
        if ($guessed)
            return $guessed;

        return $file->getClientOriginalExtension();
    }

    /**
     * Get a file name that is formatted and unique in its folder.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string
     */
    protected function uniqueFileName(UploadedFile $file, $folder)
    {
        $formatted = $this->formattedFileName($file);
        return $this->repository()->findUniqueFileName($formatted, $folder);
    }

    /**
     * Format the filename (name + extension) with snake case.
     *
     * @param UploadedFile $file
     * @return string
     * @throws FileUploadException
     */
    protected function formattedFileName(UploadedFile $file)
    {
        $baseName = File::removeExtension($file->getClientOriginalName());
        $extension = $this->extension($file);

        return $this->disks()->formattedFileName($baseName, $extension);
    }

    public function disks()
    {
        return app(DisksManager::class);
    }

    /**
     * Get the file repository instance.
     *
     * @return FileRepository
     * @throws \MezzoLabs\Mezzo\Exceptions\RepositoryException
     */
    protected function repository()
    {
        return FileRepository::makeRepository();
    }

    /**
     * Create a JSON string with all the "unimportant" file infos.
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function fileInfoJson(UploadedFile $file)
    {
        $info = new Collection();

        $info->put('size', $file->getClientSize());
        $info->put('originalName', $file->getClientOriginalName());

        return $info->toJson();
    }

    /**
     * Validate the meta data that will be saved in the database.
     *
     * @param Collection $data
     * @return bool
     */
    protected function validateData(Collection $data)
    {
        $rules = app(\App\File::class)->getRules();

        $validation = Validator::make($data->toArray(), $rules);

        $this->lastValidation = $validation;

        return $validation->passes();
    }


    /**
     * @param $class
     * @return FileUploaderContract
     */
    public function makeUploader($class) : FileUploaderContract
    {
        if (isset(static::$uploaders[$class]))
            $class = static::$uploaders[$class];

        if (empty($class) || !class_exists($class))
            throw new FileUploadException('Disk is not valid: ' . $class);

        return app()->make($class);
    }
}