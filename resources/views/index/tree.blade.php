<div class="flex m-3">
    <div class="w-fit">
        <p class="text-xl ml-2">Localización</p>
        <input type="search" placeholder="Buscar..." aria-label="Buscar..."
            class="gridjs-input gridjs-search-input m-2" value="">
        <div id="jstree" class="w-full">
            <ul>
                @foreach ($dataTree as $keyB => $branch)
                <li id="{{$branch['Localizacion']}}">{{ $branch['Localizacion'] }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="w-fit">
        <p class="text-xl ml-2">Clasificación</p>
        <input type="search" placeholder="Buscar..." aria-label="Buscar..." id="search-clsfcn" class="gridjs-input gridjs-search-input m-2">
        <div id="jstre2e" class="w-full">
        </div>
    </div>
    <div class="w-fit">
        <p class="text-xl ml-2">Sub-Clasificación</p>
        <input type="search" placeholder="Buscar..." aria-label="Buscar..." class="gridjs-input gridjs-search-input m-2" value="">
        <div id="jstre3e" class="w-full">
            <ul>
                @foreach ($dataTree as $keyB => $branch)
                    <li id="{{$branch['Localizacion']}}">{{ $branch['Localizacion'] }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>