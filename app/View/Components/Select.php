<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select extends Component
{
    /**
     * Create a new component instance.
     */
    public $id;

    public $label;
    public $class;
    public $name;
    public $disabled;
    public $options;

    // public $attributes;

    public function __construct($id,$label,$class,$name, $options = [],)
    {
        $this->id = $id;

        $this->label = $label;
        $this->class = $class;
        $this->name = $name;
        $this->options = $options;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select');
    }
}
