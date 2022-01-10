<?php

namespace App\Utilis\Classes\Updater;

interface UpdaterModelInterface
{
    public function update($row, array $inputs);
}
