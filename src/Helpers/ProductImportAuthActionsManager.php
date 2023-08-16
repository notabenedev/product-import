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
     * @return bool|string
     */
    public function checkAuthUser()
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
     * Установить куку
     *
     * @param $value
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function setUserCookie($value)
    {
        if (! request()->cookie(self::COOKIE_NAME, false)) {
            $minutes = 3*60;
            $cookie = cookie(self::COOKIE_NAME, $value, $minutes);
            return response()->cookie($cookie);
        }
        else {
            return ProductImportProtocolActions::failure("Cookie  not set");
        }
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