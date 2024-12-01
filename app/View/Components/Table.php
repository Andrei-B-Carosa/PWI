<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Table extends Component
{
    public $id;
    public $class;

    public function __construct($id,$class)
    {
        $this->id = $id;
        $this->class = $class;
    }

    public function render(): View|Closure|string
    {
        return view('components.table');
    }
}
