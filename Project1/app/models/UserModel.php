<?php

namespace app\models;

class UserModel
{
    public function __construct(
        private readonly string $usersFile = 'data/users.txt',
        private readonly string $attemptsFile = 'data/attempts.txt',
        private ?string $errorMessage = null
    ) {}

    /**
     * @param string $username
     * @param string $password
     *
     * @return bool
     */
    public function authenticate(string $username, string $password): bool
    {
        $attempts = $this->getLoginAttempts($username);

        if ($attempts >= 3) {
            $remaining = $this->getRemainingLockTime($username);
            if ($remaining > 0) {
                $this->errorMessage = "Try again in {$remaining} seconds.";

                return false;
            } else {
                $this->resetAttempts($username);
            }
        }

        $users = $this->getUsers();

        if (isset($users[$username]) && password_verify($password, $users[$username])) {
            $this->resetAttempts($username);

            return true;
        }

        $this->incrementAttempts($username);
        $this->errorMessage = 'Wrong credentials';

        return false;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    /**
     * @return array
     */
    private function getUsers(): array
    {
        $data = file_get_contents($this->usersFile);

        return json_decode($data, true) ?? [];
    }

    /**
     * @param string $username
     *
     * @return int
     */
    private function getLoginAttempts(string $username): int
    {
        $data = $this->getAttemptsData();

        return $data[$username]['attempts'] ?? 0;
    }

    /**
     * @param string $username
     */
    private function incrementAttempts(string $username): void
    {
        $data = $this->getAttemptsData();

        if (!isset($data[$username])) {
            $data[$username] = ['attempts' => 0, 'last_attempt' => time()];
        }

        $data[$username]['attempts']++;
        $data[$username]['last_attempt'] = time();

        file_put_contents($this->attemptsFile, json_encode($data));
    }

    /**
     * @param string $username
     */
    private function resetAttempts(string $username): void
    {
        $data = $this->getAttemptsData();

        unset($data[$username]);

        file_put_contents($this->attemptsFile, json_encode($data));
    }

    /**
     * @param string $username
     *
     * @return int
     */
    private function getRemainingLockTime(string $username): int
    {
        $data = $this->getAttemptsData();

        if (isset($data[$username])) {
            $timeSinceLastAttempt = time() - $data[$username]['last_attempt'];

            return max(0, 300 - $timeSinceLastAttempt);
        }

        return 0;
    }

    /**
     * @return array
     */
    private function getAttemptsData(): array
    {
        if (!file_exists($this->attemptsFile)) {
            return [];
        }

        $data = file_get_contents($this->attemptsFile);

        return json_decode($data, true) ?? [];
    }
}
