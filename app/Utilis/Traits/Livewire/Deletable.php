<?php

namespace App\Utilis\Traits\Livewire;

trait Deletable
{
    public $confirmingDelete = false, $confirmableId = null;

    public function startConfirmingDelete(string $confirmableId)
    {
        $this->confirmingDelete = true;
        $this->confirmableId = $confirmableId;
        $this->dispatchBrowserEvent('confirming-delete');
    }

    public function stopConfirmingDelete()
    {
        $this->confirmingDelete = false;
        $this->confirmableId = null;
    }

    public function confirmDelete()
    {
        $this->dispatchBrowserEvent('delete-confirmed', ['id' => $this->confirmableId,]);
        $this->stopConfirmingDelete();
    }

    public function delete($id)
    {
        try {
            if ($this->modelName == '\App\Models\User') {
                $this->model::whereNotIn('id', [1])->findOrFail($id)->delete();
            } elseif ($this->modelName == '\App\Models\Role') {
                $this->model::whereNotIn('id', [1, 2, 3])->findOrFail($id)->delete();
            } else {
                $this->model::findOrFail($id)->delete();
            }
            $this->resetSelected();
            $this->dispatchBrowserEvent('message', ['type' => 'success', 'text' => __('Successfully deleted')]);
        } catch (\Exception $exception) {
            //$this->dispatchBrowserEvent('message', ['type' => 'error', 'text' => __('An error has occurred, please contact the developer.')]);
            $this->dispatchBrowserEvent('message', ['type' => 'error', 'text' => $exception->getMessage()]);
        }
    }

    public function multiDelete()
    {
        try {
            if (!empty($this->selected)) {
                (clone $this->rowsQuery)->unless($this->selectAll, function ($query) {
                    if ($this->modelName == '\App\Models\User') return $query->whereNotIn('id', [1])->whereKey($this->selected);
                    if ($this->modelName == '\App\Models\Role') return $query->whereNotIn('id', [1, 2, 3])->whereKey($this->selected);
                    return $query->whereKey($this->selected);
                })->delete();
                $this->resetSelected();
                $this->dispatchBrowserEvent('message', ['type' => 'success', 'text' => __('Successfully deleted')]);
            } else {
                $this->dispatchBrowserEvent('message', ['type' => 'warning', 'text' => __('Nothing selected')]);
            }
        } catch (\Exception $exception) {
            $this->dispatchBrowserEvent('message', ['type' => 'error', 'text' => __('An error has occurred, please contact the developer.')]);
        }
    }
}
