<div id="messagedraganddrop" style="display:none;">
    <p class="w-100">Suelta el archivo acá</p>
</div>
<div id="drop_zone" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);"
    ondragleave="dragLeaveHandler(event);">
    <div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="text-center border-t border-b border-gray-1 text-4B3863 px-4 pt-3" role="alert">
            <p class="font-bold text-2xl">Lectura de códigos de barras</p>
            <p><span class="text-2xl text-bold text-red-700">*</span> Asegurese de tener la biblioteca correcta para
                iniciar
                el inventario</p>
        </div>
    </div>
    <form class="justify-center text-center flex" id="registercode">
        <div class="mt-4 ">
            <x-label for="codbar" :value="__('Código de barras a buscar:')" />
            <small class="text-primary">puedes soltar archivos en este espacio para subir</small>
            <x-input id="codbar" class="form-control block w-100 mt-1" type="text" name="codbar" />
            <button class="rounded p-2 bg-blue-500 hover:bg-sky-700 btn-md text-white mt-2">
                Inventariar
            </button>
            <button id="loadfile"
                class="border rounded p-2 bg-slate-200 hover:bg-slate-400 btn-md mt-2 hover:text-white">
                Cargar Archivo
            </button>
        </div>
    </form>
</div>
