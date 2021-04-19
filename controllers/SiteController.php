<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;
use app\models\LoginForm;
use app\models\RegisterModel;
use app\models\User;

class SiteController extends Controller
{
    public function home()
    {
        return parent::render('home');
    }

    public function login(Request $request)
    {
        parent::setLayout('auth');
        $loginForm = new LoginForm();

        if ($request->isGet())
            return parent::render('login', [
                'model' => $loginForm
            ]);

        elseif ($request->isPost()) {
            $loginForm->loadData($request->body());

            if ($loginForm->validate() && $loginForm->login()) {
                return 'success';
            } else
                return $this->render('login', [
                    'model' => $loginForm
                ]);
        }
    }

    public function register(Request $request)
    {
        parent::setLayout('auth');
        $user = new User();

        if ($request->isGet())
            return parent::render('register', [
                'model' => $user
            ]);
        elseif ($request->isPost()) {

            $user->loadData($request->body());
            if ($user->validate() && $user->register()) {
                Application::$app->session->setFlash('success', "Thank's for registery " . $user->name);
                Application::$app->response->redirect('/');
            } else {
                return $this->render('register', [
                    'model' => $user
                ]);
            }
        }
    }
}
