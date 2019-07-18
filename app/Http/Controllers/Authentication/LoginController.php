<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class LoginController extends Controller
{
    /**
     * Shows login form to the guest
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return $this->responseFactory->view('auth.login');
    }

    /**
     * Logs in the user if they exist
     *
     * @param LoginFormRequest $request
     * @return RedirectResponse
     */
    public function login(LoginFormRequest $request)
    {
        $data = $request->validateData();

        $findUser = new User();

        $user = $findUser->where('email', $data['email'])->first();

        if ($this->auth->attempt($data)) {
            if ($user['confirmed']) {
                return $this->responseFactory->redirectTo('blog');
            } else {
                $this->auth->logout();
                $this->session->flash('danger', 'Account not verified yet.');
                return $this->responseFactory->redirectTo('home');
            }
        }

        $this->session->flash('danger', 'Incorrect e-mail or password.');

        return $this->responseFactory->redirectTo('login');
    }

    /**
     * Logs out the user
     *
     * @return RedirectResponse
     */
    public function logout()
    {
        if ($this->auth->check()) {
            $this->auth->logout();
        }

        return $this->responseFactory->redirectTo('home');
    }
}