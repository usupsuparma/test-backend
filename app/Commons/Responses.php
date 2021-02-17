<?php


namespace App\Commons;


class Responses
{
    public static function success($message = "Success", $data = null) {
        return [
            'success' => true,
            'message' => $message,
            'data' => $data
        ];
    }

    public static function error($message = "Error", $data = null) {
        return [
            'success' => false,
            'message' => $message,
            'data' => $data
        ];
    }

    public static function errorNotFound() {
        return Responses::error("Data Not Found");
    }

    public static function errorParams($message = "not found") {
        return Responses::error("Parameter error ".$message);
    }

    public static function tokenNotProvide() {
        return Responses::error("Token Not Provide");
    }

    public static function tokenExpire() {
        return Responses::error("Token Expire");
    }

    public static function tokenInvalid() {
        return Responses::error("Token Invalid");
    }

    public static function errorServer($message = "") {
        return response(Responses::error("Internal Server Error ". $message), 500);
    }
}
