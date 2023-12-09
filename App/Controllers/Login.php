<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Flash;

/**
 * Login controller
 *
 * PHP version 7.0
 */
class Login extends \Core\Controller
{
    /**
     * Show the login page
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Login/new.html');
    }

    public function createAction()
    {
        $user = User::authenticate($_POST['email'], $_POST['password']);

        $remember_me = isset($_POST['remember_me']);

        if ($user) {

            Auth::login($user, $remember_me);

            Flash::addMessage('Logowanie zakończone sukcesem');

            $this->redirect(Auth::getToPage());
        } else {

            Flash::addMessage('Logowanie nie powiodło się. Spróbuj ponownie', Flash::WARNING);

            View::renderTemplate('Login/new.html', [
                'email' => $_POST['email'],
                'remember_me' => $remember_me
            ]);
        }
    }

    public function destroyAction()
    {
        Auth::logout();

        $this->redirect('/login/show-logout-message');
    }

    public function showLogoutMessageAction()
    {
        Flash::addMessage('Wylogowanie powiodło się');

        $this->redirect('/');
    }


    public function deleteAccountAction()
    {
        $user = new User();

        $userId = Auth::getUser();

        if ($user->deleteProfile($userId->id)) {
            Auth::logout();

            $this->redirect('/login/show-destroy-message');
        } else {
            Flash::addMessage('Usunięcie konta nie powiodło się. Zaloguj się i spróbuj ponownie', Flash::WARNING);

            View::renderTemplate('Profile/edit.html', [
                'user' => $this->userId
            ]);
        }
    }


    public function showDestroyMessageAction()
    {
        Flash::addMessage('Pomyślnie usunięto konto');

        $this->redirect('/');
    }
}
