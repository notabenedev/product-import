<?php

namespace Notabenedev\ProductImport\Helpers;


use App\ImportYml;
use App\User;
use Illuminate\Support\Facades\Auth;
use Notabenedev\ProductImport\Facades\ProductImportAuthActions;
use Notabenedev\ProductImport\Facades\ProductImportLoadFileActions;
use Notabenedev\ProductImport\Facades\ProductImportProtocolActions;

class ProductImportProtocolActionsManager
{
    protected $type;
    protected $mode;

    public function __construct(){
        $this->type = request()->get("type", false);
        $this->mode = request()->get("mode", false);
    }

    /**
     * Init manual
     *
     * @param $manualMode
     * @return void
     */
    public function manualInit($manualMode = false){
        if ($manualMode) {
            $this->mode = $manualMode;
            $this->type  = "manual";
        }
        return $this->init();
    }

    /**
     * Init import
     * @return bool|string|void
     */
    public function init(){

        if (! $this->type || ! $this->mode) return $this->failure("Not enough params");

        switch ($this->mode) {
            case "form":
                if ( $check = ProductImportAuthActions::checkAuthUser() !== true)
                    return $check;
                $yml = ImportYml::create([]);
                $answer = $this->translateAnswer(ProductImportLoadFileActions::modeLoadFile($yml));
                if ($answer !== "success\n")
                   return redirect()->back()->with("danger", $answer);
                return redirect()->back()->with("success", "Файл импорта загружен!");

            case "checkauth":
                $check =  ProductImportAuthActions::checkAuthUser();
                if ($check !== true)
                    return $check;

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

                $yml = ProductImportAuthActions::getUserCookie();
                if (is_string($yml)) return $yml;
                return $this->answer(implode("\n", [
                    "zip=no",
                    "file_limit=95000000"
                ]));

            case "file":
                $yml = ProductImportAuthActions::getUserCookie();
                if (is_string($yml)) return $yml;
                return ProductImportLoadFileActions::modeLoadFile($yml);

            case "import":
                $yml = ProductImportAuthActions::getUserCookie();
                if (is_string($yml)) return $yml;
                break;
        }
        return $this->failure("Undefined mode");
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
     *
     * @param string $value
     * @return array|false|string|string[]
     */
    public function translateAnswer(string $value){
        return  iconv( "windows-1251", "UTF-8", strip_tags($value));
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