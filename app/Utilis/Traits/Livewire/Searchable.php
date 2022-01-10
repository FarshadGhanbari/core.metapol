<?php

namespace App\Utilis\Traits\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

trait Searchable
{
    use WithPagination;

    public $search, $selected = [], $sortCol = 'created_at', $sortType = 'DESC', $perPage = 20, $page = 1, $selectPage = false, $selectAll = false;

    public function getModelProperty(): string
    {
        return $this->modelName;
    }

    public function getRowsQueryProperty(): Builder
    {
        return $this->model::search($this->search)
            ->orderBy($this->sortCol, $this->sortType);
    }

    public function getRowsProperty()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

    public function resetSelected()
    {
        $this->selected = [];
        $this->selectPage = false;
        $this->selectAll = false;
    }

    public function resetSearch()
    {
        $this->selected = [];
        $this->selectPage = false;
        $this->selectAll = false;
        $this->search = null;
    }

    public function updatedSelected()
    {
        $this->selectPage = count($this->selected) == $this->perPage;
        $this->selectAll = false;
    }

    public function updatedSelectPage($value)
    {
        $this->selected = $value ? $this->rows->pluck('id')->map(function ($id) {
            return (string)$id;
        }) : [];
        if (!$value) {
            $this->selectPage = false;
            $this->selectAll = false;
        }
    }

    public function selectAll()
    {
        $this->selectAll = true;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function toggleSortType()
    {
        $this->sortType = $this->sortType == 'DESC' ? 'ASC' : 'DESC';
    }

    public function sort($col)
    {
        if ($this->sortCol == $col) {
            $this->toggleSortType();
        } else {
            $this->sortCol = $col;
        }
    }

    public function paginationView()
    {
        return 'particals.pagination';
    }

    public function beforeRender()
    {
        if ($this->selectAll) {
            $this->selected = $this->rows->pluck('id')->map(function ($id) {
                return (string)$id;
            });
        }
    }
}
