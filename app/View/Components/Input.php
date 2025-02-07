<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    /**
     * Create a new component instance.
     */
    public $id;

    public $label;
    public $class;
    public $name;
    public $disabled;
    public $type;

    public function __construct($id,$label,$class,$name,$disabled,$type='text')
    {
        $this->id = $id;

        $this->label = $label;
        $this->type = $type;
        $this->class = $class;
        $this->name = $name;
        $this->disabled = $disabled;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input');
    }
}
