<div id="messagedraganddrop" style="display:none;">
    <p class="w-100">Suelta el archivo acá</p>
</div>
@if($tableExists == false)

    <div class="justify-center text-center flex">
        <div class="mt-2 w-50" >
            <div class="text-xl bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert">
                <p id="txt-create-items" style="color: #9E4A24;">La biblioteca no se ha creado.</p>
            </div>
        </div>
    </div>

@else

    <div id="drop_zone" ondrop="dropHandler(event);" ondragover="dragOverHandler(event);" ondragleave="dragLeaveHandler(event);">
        <div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="text-center border-t border-b border-gray-1 text-4B3863 px-4 pt-3" role="alert">
                <p class="font-bold text-2xl">Lectura de códigos de barras</p>
                <div id="container-xyz" class=" opt-log-radio w-50 mt-2">
                    <input type="radio" value="1" name="clasificacion" class="clasificacion">
                    <label class="mr-3" >Nivel Central</label>
                    <input type="radio" value="2" name="clasificacion" class="clasificacion">
                    <label class="mr-3" >Auxiliar</label>
                    <input type="radio" value="3" name="clasificacion" class="clasificacion"> 
                    <label class="mr-3" >Catalogación</label>
                </div>
                <p>
                    <span class="text-xl text-bold text-red-700">*</span> 
                    Asegurese de tener la biblioteca correcta para iniciar el inventario
                </p>
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
                <button id="loadfile" hidden
                    class="border rounded p-2 bg-slate-200 hover:bg-slate-400 btn-md mt-2 hover:text-white">
                    Cargar Archivo
                </button>
            </div>
        </form>
    </div>

@endif