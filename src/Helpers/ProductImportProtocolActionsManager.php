<?php

namespace Notabenedev\ProductImport\Helpers;


use App\ImportYml;
use App\User;
use Illuminate\Support\Facades\Auth;
use Notabenedev\ProductImport\Facades\ProductImportAuthActions;
use Notabenedev\ProductImport\Facades\ProductImportProtocolActions;

class ProductImportProtocolActionsManager
{
    protected $type;
    protected $mode;

    public function __construct(){
        $this->type = request()->get("type", false);
        $this->mode = request()->get("mode", false);
    }

    public function init($manualMode = false){

        if ($manualMode) {
            $this->mode = $manualMode;
            $this->type  = "manual";
        }

        if (! $this->type || ! $this->mode) return $this->failure("Not enough params");

        switch ($this->mode) {
            case "form":

                if ($check = is_string(ProductImportAuthActions::checkAuthUser())) return $check;

                $yml = ImportYml::create([]);
                ProductImportAuthActions::setUserCookie($yml->uuid);

                break;

            case "console":
                $yml = ImportYml::create([]);
                $file = $yml->files()->create(["path" => "import.yml", "original_name" => "import.yml", "type" => "full"]);
                break;

            case "current":
                $yml = ProductImportAuthActions::getUserCookie();
                if (is_string($yml)) return $yml;
                $files = $yml->files();
                break;

            case "checkauth":
                if ($check = is_string(ProductImportAuthActions::checkAuthUser())) return $check;

                $yml = ImportYml::create([]);
                /**
                 * @var ImportYml $yml
                 */
                $answer = [
                    "success",
                    ProductImportAuthActions::getCookieName(),
                    $yml->uuid,
                ];
                return ProductImportProtocolActions::answer(implode("\n", $answer));

            case "init":
                return $this->answer(implode("\n", [
                    "zip=no",
                    "file_limit=95000000"
                ]));

            case "file":
                $yml = ProductImportAuthActions::getUserCookie();
                if (is_string($yml)) return $yml;

                break;

            case "import":
                $yml = ProductImportAuthActions::getUserCookie();
                if (is_string($yml)) return $yml;
                break;
        }
    }



    /**
     * Вернуть ответ.
     *
     * @param string $value
     * @return bool|false|string
     */
    public function answer(string $value)
    {
        return iconv("UTF-8", "windows-1251", $value);
    }

    /**
     * Вернуть ошибку.
     *
     * @param string $details
     * @return bool|false|string
     */
    public function failure(string $details = "")
    {
        $value = "failure" . (empty($details) ? "" : "\n$details");
        return $this->answer($value);
    }

}