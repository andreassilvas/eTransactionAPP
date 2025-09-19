<?php
class CreateAccountController extends Controller
{
    public function index()
    {
        $this->view('create-account/index');
    }

    public function store()
    {
        $userModel = new User();
        $result = $userModel->create($_POST);

        if ($result) {
            $this->view('create-account/index', ['success' => 'Compte créé!']);
        } else {
            $this->view('create-account/index', ['error' => 'Erreur lors de la création du compte.']);
        }
    }
}
