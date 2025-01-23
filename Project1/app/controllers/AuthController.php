<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Views\View;

class AuthController
{
    public const ACTION_LOGIN = 'login';
    public const ACTION_LOGOUT = 'logout';
    public const ACTION_PROFILE = 'profile';

    public const ALLOWED_ACTIONS = [
        self::ACTION_LOGIN,
        self::ACTION_LOGOUT,
        self::ACTION_PROFILE,
    ];

    /**
     * @param UserModel $userModel
     * @param View $view
     */
    public function __construct(
        private readonly UserModel $userModel = new UserModel(),
        private readonly View $view = new View()
    ) {}

    public function route(): void
    {
        $action = $_GET['action'] ?? self::ACTION_LOGIN;

        match ($action) {
            self::ACTION_LOGOUT => $this->handleLogout(),
            self::ACTION_PROFILE => $this->showProfile(),
            default => $this->handleLogin(),
        };
    }

    private function handleLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($this->userModel->authenticate($username, $password)) {
                $_SESSION['username'] = $username;

                self::redirect(self::ACTION_PROFILE);
            } else {
                $error = $this->userModel->getErrorMessage();

                $this->view->render(self::ACTION_LOGIN, ['error' => $error]);
            }
        } else {
            $this->view->render(self::ACTION_LOGIN);
        }
    }

    private function handleLogout(): void
    {
        session_destroy();

        self::redirect(self::ACTION_LOGIN);
    }

    private function showProfile(): void
    {
        if (!isset($_SESSION['username'])) {
            self::redirect(self::ACTION_LOGIN);
        }

        $this->view->render('profile', ['username' => $_SESSION['username']]);
    }

    /**
     * @param string $action
     */
    public static function redirect(string $action): void
    {
        header('Location: index.php?action=' . $action);

        exit;
    }
}
