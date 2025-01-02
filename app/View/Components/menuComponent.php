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
            if($item['permiso'] > $admin) {
                if(!isset($menu[$key+1])) $html.= '</div></div>';
                unset($menu[$key]);
                continue;
            }
            if($item['orden'] > (int)$item['orden']){
                if(!isset($validateSubMenu) || isset($validateSubMenu) && !$validateSubMenu){
                    $prevData = next($menu);
                    $html.=
                        '<div id="modal-menu-'.$prevData["id"].'" class="dropdown-smenu absolute w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1" >
                            <div class="py-1" role="none">';
                    $html.= '<a href="#" class="base-1-color block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-'.$prevData["id"].'">'.$prevData["label"].'</a>';
                }
                $html.= '<a href="#" class="base-1-color block px-4 py-2 text-sm " role="menuitem" tabindex="-1" id="menu-item-'.$item["id"].'">'.$item["label"].'</a>';
                $validateSubMenu = true;
                if(!next($menu[$key+1])) $html.= '</div></div>';
            }else{
                if(isset($validateSubMenu) && $validateSubMenu){
                    $html.= '</div></div>'; 
                    $validateSubMenu = false;
                }
                $html.= 
                "<button class=\"rounded-lg base-1-color bg-white btn rounded-lg mx-1\" type=\"button\" id=\"submenu-{$item["id"]}\"  data-id=\"{$item["id"]}\">
                    {$item["icono"]} {$item["label"]}
                </button>";
            }
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
