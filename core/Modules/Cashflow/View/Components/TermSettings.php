<?php

namespace Modules\Cashflow\App\View\Components;

use Illuminate\View\Component;

class TermSettings extends Component
{
    /**
     * The term.
     *
     * @var string
     */
    public $term;
    /**
     * The date_to.
     *
     * @var string
     */
    public string $date_from;
    /**
     * The date_to.
     *
     * @var string
     */
    public string $date_to;
 
    /**
     * Create the component instance.
     *
     * @param  string  $term
     * @param  string  $date_to
     * @return void
     */
    public function __construct($attrs)
    {
        $this->term = $attrs['term'];
        $this->date_from= $attrs['date_from'];
        $this->date_to= $attrs['date_to'];
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        $term=$this->term;
        $date_from=$this->date_from;
        $date_to=$this->date_to;
        $term_conditions= get_option('term_conditions');
        if($term_conditions) $term_conditions = unserialize($term_conditions);
        return view('components.term-settings',compact('term','date_from','date_to','term_conditions'));
    }
}
