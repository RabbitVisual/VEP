<?php

namespace VertexSolutions\HomePage\View\Components\Components\Layouts;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Master extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('homepage::components.layouts.master');
    }
}
