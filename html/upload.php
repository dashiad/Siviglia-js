<?php

include_once("CONFIG_default.php");
include_once(LIBPATH."startup.php");

class Upload
{
    private $uploadedFile;
    private $request;
    private $validMimeTypes = array(
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'csv' => 'text/plain',
        'json' => 'application/json'
    );

    public function __construct($uploadedFile, $request)
    {
        $this->uploadedFile = $uploadedFile;
        $this->request = $request;
    }

    private function insertDb($filename, $originalFilename, $mimeType)
    {
        global $oCurrentUser;

        $mdl = \getModel('upload');
        $mdl->id_user = $oCurrentUser;
        $mdl->filename = $filename;
        $mdl->original_filename = $originalFilename;
        $mdl->path = PERCENTIL_UPLOAD_DIR . $filename;
        $mdl->mime_type = $mimeType;
        $mdl->name = $this->request['uploadName'];
        $mdl->comments = $this->request['uploadComments'];
        $mdl->{"*date_add"}->setAsNow();
        $mdl->save();

        return $mdl->id_upload;
    }

    public function upload()
    {
        try {
            if (! isset($this->uploadedFile['error']) || is_array($this->uploadedFile['error'])) {
                throw new \RuntimeException('Invalid parameters.');
            }

            switch ($this->uploadedFile['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new \RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new \RuntimeException('Exceeded filesize limit.');
                default:
                    throw new \RuntimeException('Unknown errors.');
            }

            if ($this->uploadedFile['size'] > PERCENTIL_MAX_UPLOADED_FILE_SIZE) {
                throw new \RuntimeException('Exceeded filesize limit.');
            }

            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($this->uploadedFile['tmp_name']);
            if (false === $ext = array_search($mimeType, $this->validMimeTypes, true)) {
                throw new \RuntimeException('Invalid file format.');
            }

            if('csv' == $ext && $this->uploadedFile['type'] == 'application/json') {
                $ext = 'json';
            }

            $filename = uniqid() . '.' . $ext;
            if (! move_uploaded_file($this->uploadedFile['tmp_name'], PERCENTIL_UPLOAD_DIR . $filename)) {
                throw new \RuntimeException('Failed to move uploaded file.');
            }

            //Meter la entrada en la BBDD
            $idUpload = $this->insertDb($filename, $this->uploadedFile['name'], $mimeType);
            $response = array(
                'status' => 200,
                'filename' => $filename,
                'original_filename' => $this->uploadedFile['name'],
                'path' => PERCENTIL_UPLOAD_DIR . $filename,
                'mime_type' => $mimeType,
                'name' => $this->request['uploadName'],
                'comments' => $this->request['uploadComments'],
                'id_upload' => $idUpload
            );

            return $response;
        }
        catch (\RuntimeException $e) {
            echo $e->getMessage();
            exit();
        }
    }
}

include_once(LIBPATH."/Request.php");
$request=Request::getInstance();
Registry::initialize($request);

$keys = array_keys($request->filesData);
$_file = $keys[0];
$upload = new Upload($request->filesData[$_file], $request->actionData);
$response = $upload->upload();
?>
<html>
    <body>
        <textarea>
            <?php echo json_encode($response); ?>
        </textarea>
    </body>
</html>