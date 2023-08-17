<?php

namespace Notabenedev\ProductImport\Helpers;


use App\ImportYml;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Notabenedev\ProductImport\Facades\ProductImportProtocolActions;
use Notabenedev\ProductImport\Models\YmlFile;

class ProductImportLoadFileActionsManager
{
    const FOLDER = "product-import";

    protected $originalFileName;
    protected $originalFileExt;
    protected $type;

    public function __construct()
    {
        $this->originalFileName = "";
        $this->originalFileExt = "";
        $this->type = "";
    }

    /**
     * Проверить имя файла.
     *
     * @return bool|string
     */
    public function checkFileName()
    {
        $this->originalFileName = request()->get("filename", false);
        if (! $this->originalFileName) return false;

        return $this->originalFileName;
    }

    /**
     * Загрузить файл.
     *
     * @param ImportYml $yml
     * @return string
     */
    public function modeLoadFile(ImportYml $yml)
    {
        $this->originalFileName = request()->get("filename", false);
        if (! $this->originalFileName) {
            $yml->delete();
            return ProductImportProtocolActions::failure("File name not found");
        }

        $name = $this->getFileName();
        $this->getFileType($name);
        if ($this->type === "undefined") {
            $yml->delete();
            return ProductImportProtocolActions::failure("Undefined file name");
        }

        $this->getFileExt($name);
        if (! $this->checkFileExt()) {
            $yml->delete();
            return ProductImportProtocolActions::failure("Undefined file extention:".$this->originalFileExt);
        }

        $fileData = request()->getContent();
        if (empty($fileData)) {
            $fileData = request()->file;
            if (empty($fileData)) {
                $yml->delete();
                 return ProductImportProtocolActions::failure("Input data is empty");
            }

        }

        try {
            $newFilePath = $this->storeFile($fileData);
            YmlFile::create([
                "import_yml_id" => $yml->id,
                "path" => $newFilePath,
                "type" => $this->type,
                "original_name" => $this->originalFileName,
            ]);
            return ProductImportProtocolActions::answer("success\n");
        }
        catch (\Exception $exception) {
            return ProductImportProtocolActions::failure("Error while upload file");
        }
    }

    /**
     * Имя файла.
     *
     * @return string|string[]|null
     */
    protected function getFileName()
    {
        // так сделано в 1cBitrix, наверное они что-то знают
        $filename = preg_replace("#^(/tmp/|upload/1c/webdata)#", "",
            $this->originalFileName);
        $filename = trim(str_replace("\\", "/", trim($filename)), "/");

        return $filename;
    }

    /**
     * Разрешение файла.
     *
     *
     */
    protected function getFileExt($name)
    {
        $info = new \SplFileInfo($name);
        $this->originalFileExt = $info->getExtension();
    }

    /**
     * Check file extention
     *
     * @return bool
     */
    protected function checkFileExt(){
        switch ($this->originalFileExt){
            case "yml": case "xml":
                return $this->originalFileExt;
            default:
                return false;
        }
    }

    /**
     * Тип файла.
     *
     * @param $name
     */
    protected function getFileType($name)
    {
        if (strstr($name, "import") !== false) {
            $this->type = "import";
        }
        elseif (strstr($name, "offers") !== false) {
            $this->type = "offers";
        }
        elseif (strstr($name, "catalog") !== false) {
            $this->type = "catalog";
        }
        else {
            $this->type = "undefined";
        }
    }

    /**
     * Сохранить файл.
     *
     * @param $data
     * @return string
     */
    protected function storeFile($data)
    {
        $fileName = Str::random(40) . ".".$this->originalFileExt;
        $filePath = self::FOLDER . "/" . $fileName;
        Storage::disk("public")->put($filePath, $data);
        return $filePath;
    }
}