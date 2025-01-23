<?php

namespace App\Views;

use RuntimeException;

class View
{
    /**
     * @param string $template
     * @param array $data
     */
    public function render(string $template, array $data = []): void
    {
        $path = "app/views/$template.php";

        if (!file_exists($path)) {
            throw new RuntimeException("Template $template not found");
        }

        $render = static function () use ($path, $data) {
            foreach ($data as $key => $value) {
                $data[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }

            include $path;
        };

        $render();
    }
}
