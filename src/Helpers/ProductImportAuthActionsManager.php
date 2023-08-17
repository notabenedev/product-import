<?php

namespace Notabenedev\ProductImport\Helpers;


use App\ImportYml;
use App\User;
use Illuminate\Support\Facades\Auth;
use Notabenedev\ProductImport\Facades\ProductImportProtocolActions;

class ProductImportAuthActionsManager
{
    const COOKIE_NAME = "productImport";

    /**
     * @return string
     */
    public function getCookieName(){
        return self::COOKIE_NAME;
    }

    /**
     * Проверить авторизацию.
     *
     * @return bool
     */
    public function checkRequestUser()
    {
        $user = request()->getUser();
        $pass = request()->getPassword();

        $attempt = Auth::attempt(["email" => $user, "password" => $pass]);

        return ! $attempt ? false : true;
    }

    /**
     *
     * @return bool
     */
    public function checkAuthUser(): bool
    {
        if (! $this->checkRequestUser()) return ProductImportProtocolActions::failure("Wrong user data");
        $user = Auth::user();
        /**
         * @var User $user
         */
        if (! $user->can("site-management")) return ProductImportProtocolActions::failure("Access denied");

        return true;
    }


    /**
     * Получить куку.
     *
     * @return ImportYml|string
     */
    public function getUserCookie()
    {
        if ($cookie = request()->cookie(self::COOKIE_NAME, false)) {
            try {
                $yml = ImportYml::query()
                    ->where("uuid", $cookie)
                    ->firstOrFail();
            }
            catch (\Exception $exception) {
                return ProductImportProtocolActions::failure("Cookie value is wrong");
            }
            return $yml;
        }
        else {
            return ProductImportProtocolActions::failure("Cookie authorize not found");
        }
    }


}