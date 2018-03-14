<?php
namespace Alph\Controllers;

use Alph\Controllers\View;
use Alph\Managers\AccountManager;
use Alph\Managers\TerminalManager;
use Alph\Managers\NetworkManager;
use Alph\Services\Database;
use Alph\Services\Mail;

class AccountController
{
    public static function signup(array $params)
    {
        $view = (new View("account/signup"))->render();

        unset($_SESSION["errors"]);
        unset($_SESSION["data"]);

        return $view;
    }

    public static function signin(array $params)
    {
        $view = (new View("account/signin"))->render();

        unset($_SESSION["errors"]);
        unset($_SESSION["data"]);

        return $view;
    }

    public static function logout(array $params) {
        AccountManager::logout();

        header("Location: /");        
    }

    public static function validate(array $params)
    {
        if (strlen($params["code"]) == 100) {
            $db = Database::connect();

            
            $idaccount = AccountManager::getUserIdFromCode($db, $params["code"]);

            if($idaccount !== false) {
                if (AccountManager::validateUser($db, $idaccount)) {
                    $network_mac = NetworkManager::createNetwork($db);
                    
                    if($network_mac !== false) {
                        echo "mac done";
                        if(TerminalManager::createTerminal($db, $idaccount, $network_mac)) {

                            echo "terminal done";
                            
                            AccountManager::removeValidationCode($db, $params["code"]);
                        }
                    }
                }
            }
        }

        header("Location: /signin");
    }

    public static function signupaction(array $params)
    {
        $db = Database::connect();

        $_SESSION["errors"] = AccountManager::checkUserRegister($db, $_POST["username"], $_POST["email"], $_POST["password"]);

        if (!empty($_SESSION["errors"])) {
            $_SESSION["data"]["username"] = $_POST["username"];
            $_SESSION["data"]["email"] = $_POST["email"];

            header("Location: /signup");
            return;
        }

        $result = AccountManager::createUser($db, $_POST["username"], $_POST["email"], $_POST["password"]);

        if ($result) {
            $rand_str = AccountManager::createActivationCode($db, $_POST["email"]);

            if ($rand_str !== false) {
                $mail = new Mail($db, "Account validation", "Please validate your email at this link: <a href=\"" .
                    sprintf("%s://%s:%s/validate/%s", 
                        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', 
                        $_SERVER['SERVER_NAME'], 
                        $_SERVER["SERVER_PORT"], 
                        $rand_str) .
                    "\">Click here</a>.", [$_POST["email"]]);
                $mail->send();
            }
        }

        header("Location: /signin");
    }

    public static function signinaction(array $params)
    {
        $db = Database::connect();

        $_SESSION["errors"] = AccountManager::checkUserLogin($db, $_POST["email"], $_POST["password"]);

        if (!empty($_SESSION["errors"])) {
            $_SESSION["data"]["email"] = $_POST["email"];

            header("Location: /signin");
            return;
        }

        if(!AccountManager::identificateUser($db, $_POST["email"], $_POST["password"])) {
            $_SESSION["data"]["email"] = $_POST["email"];

            header("Location: /signin");
            return;
        }
        
        header("Location: /");
    }
}
