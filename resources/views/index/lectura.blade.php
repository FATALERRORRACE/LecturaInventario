<div id="messagedraganddrop" style="display:none;">
    <p class="w-100">Suelta el archivo acá</p>
</div>

@if ($tableExists == false)
    <div class="justify-center text-center flex">
        <div class="mt-2 w-50">
            <div class="text-xl bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert">
                <p id="txt-create-items" style="color: #9E4A24;">La biblioteca no se ha creado.</p>
            </div>
        </div>
    </div>
@else
    <div id="drop_zone" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" ondragleave="dragLeaveHandler(event);">

        <div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="text-center border-t text-4B3863 px-4 pt-2" role="alert">
                <p class="font-bold text-2xl">Lectura de códigos de barras</p>
                <span class="">
                    <span class="text-bold text-red-700">*</span>
                    Asegurese de tener la biblioteca correcta para iniciar el inventario 
                </span>
                @if ($dateAllowed)
                    <div id="container-xyz" class=" opt-log-radio w-50">
                        <input type="radio" value="1" name="clasificacion" class="clasificacion">
                        <label class="mr-3">Nivel Central</label>
                        <input type="radio" value="2" name="clasificacion" class="clasificacion">
                        <label class="mr-3">Auxiliar</label>
                        <input type="radio" value="3" name="clasificacion" class="clasificacion">
                        <label class="mr-3">Catalogación</label>
                    </div>
                @endif
            </div>
        </div>
        @if (!$dateAllowed)
            <div class="justify-center text-center flex mb-2">
                <div class="mt-2 w-50">
                    <div class="text-xl bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert">
                        <p id="txt-create-items" style="color: #9E4A24;">Fecha de inventario no habilitada.</p>
                    </div>
                </div>
            </div>
        @endif
        @if ($admin && $dateAllowed)
            <div id="container-in-de" class="justify-center text-center w-full mt-2" >
                <input type="radio" value="1" name="tipoCarga" class="tipoCarga" checked>
                <label class="mr-3">Inventario</label>
                <input type="radio" value="2" name="tipoCarga" class="tipoCarga">
                <label class="mr-3">Prestamo</label>
            </div>
        @endif
        @if ($dateAllowed)
            <form class="justify-center text-center" id="registercode">
                <small class="text-secondary block txt-cdbab mt-2">puedes soltar archivos en este espacio para subir</small>
                <div class="mt-1 flex justify-center text-center">
                    <div id="loader-adm" style="display:none;">
                        <span class="loader"></span>
                        <p style="color: #9E4A24;">Cargando el archivo...</p>
                    </div>
                    <x-input id="codbar" placeholder="Ingresa aquí el código de barras" class="form-control mr-2 w-64"
                        type="text" name="codbar" />
                    <button class="rounded p-2 hover:bg-sky-600 btn-md text-white invt-1" id="invent">
                        Inventariar
                    </button>
                    <input id="loadfile" type="file" onchange="dropHandler(event);"
                        class="border rounded p-2 bg-slate-200 hover:bg-slate-400 btn-md ml-2 hover:text-white">
                </div>
            </form>
        @endif
    </div>

    <div id="tabs">
        <ul>
            <li><a href="#tabs-1">Archivos Cargados</a></li>
            <li><a href="#tabs-2">Códigos Escaneados</a></li>
        </ul>
        <div id="tabs-1">
            <div id="table-1-inventory"></div>
        </div>
        <div id="tabs-2">
            <div id="table-2-inventory"></div>
        </div>
    </div>
@endif
