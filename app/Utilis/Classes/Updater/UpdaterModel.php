<?php

namespace App\Utilis\Classes\Updater;

use Livewire\ComponentConcerns\ValidatesInput;

class UpdaterModel implements UpdaterModelInterface
{
    use ValidatesInput;

    public function update($model, array $inputs)
    {
        $model->update($inputs);
    }
}
