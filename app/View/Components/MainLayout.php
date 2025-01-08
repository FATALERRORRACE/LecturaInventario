<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Bibliotecas;

class MainLayout extends Component {
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render(){
        return view(
            'layouts.main',
            [
                'menu' => Menu::where('activo', 1)->orderBy('orden','ASC')->get()->toArray(),
                'bibliotecas' => Bibliotecas::get()->toArray()
            ]
        );
    }

    public function setMenuOrder($data)
    {
        $menus = [];
        foreach ($data as $key => $menu) {
            if ($menu["parent"]) {
                $this->checkParentElement($menus, $menu);
            } else if (!isset($menus[$menu["id"]])) {
                $menus[$menu["id"]] = [
                    'id' => $menu["id"],
                    'label' => $menu["label"],
                    'rol' => $menu["rol"],
                    'action' => $menu["action"],
                    'childElements' => [],
                ];
            }
        }
        return $menus;
    }


    public function checkParentElement(&$menus, &$menu, $pos = null)
    {
        foreach ($menus as $key => $subMenu) {
            if ($menu['parent'] == $key) {
                $menus[$key]['childElements'][$menu["id"]] = [
                    'id' => $menu["id"],
                    'label' => $menu["label"],
                    'action' => $menu["action"],
                    'rol' => $menu["rol"],
                    'childElements' => [],
                ];
            } else if (sizeof($menus[$key]['childElements']) > 0) {
                $this->checkParentElement($menus[$key]['childElements'], $menu);
            }
        }
    }
}
