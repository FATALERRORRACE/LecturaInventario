<?php

namespace App\View\Components;

use Illuminate\View\Component;

class menuComponent extends Component
{

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public static function renderMenu($menu, $admin){
        $html = "";
        foreach ($menu as $key => $item) {
            if($item['permiso'] > $admin) continue;

            $html.= "<button class=\"rounded-lg base-1-color bg-white btn rounded-lg mx-1\" type=\"button\" id=\"submenu-{$item["id"]}\" > 
                {$item["icono"]} {$item["label"]}
            </button>";
        }
        return $html;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.menu-component');
    }
}
