<div id="dialog-bbltc">
    <div id="btn-header-dialog" class="text-center justify-center">
        <button id="new-library" class="btn btn-sm text-white hover:bg-blue-700 bg-blue-600"><i
                class="fa-solid fa-plus"></i> Nuevo</button>
        <button id="edit-library" class="btn btn-sm text-white hover:bg-slate-500 bg-slate-200 mx-2"><i
                class="fa-solid fa-pen-to-square"></i> Editar</button>
    </div>
    <div class="relative transform overflow-hidden text-left transition-all w-full h-full">
        <div class="bg-white">
            <div class="sm:flex sm:items-start">
                <form id="biblioteca-form" class="text-left mt-3 mx-2">
                    <div class=" flex">
                        <div class="w-50">
                            <label class="font-medium opacity-80" for="nombre">
                                Nombre
                            </label>
                        </div>
                        <div class="w-50">
                            <label class="font-medium opacity-80" for="nombre">
                                carne
                            </label>
                        </div>
                    </div>
                    <div class=" mb-3 flex">
                        <div class="w-50">
                            <input class="rounded-md shadow-sm border-gray-300 " id="biblioteca" type="text"
                                name="biblioteca" required="required">
                        </div>
                        <div class="w-50">
                            <input class="rounded-md shadow-sm border-gray-300 " id="carne" type="text"
                                name="carne" required="required">
                        </div>
                    </div>

                    <div class=" flex">
                        <div class="w-50">
                            <label class="font-medium opacity-80" for="nombre">
                                Código
                            </label>
                        </div>
                        <div class="w-50">
                            <label class="font-medium opacity-80" for="nombre">
                                Localidad
                            </label>
                        </div>
                    </div>
                    <div class=" mb-3 flex">
                        <div class="w-50">
                            <input class="rounded-md shadow-sm border-gray-300" id="codigo" type="text"
                                name="codigo" required="required">
                        </div>
                        <div class="w-50">
                            <select id="localidad" name="localidad">
                                @foreach ($localidades as $key => $localidad)
                                    <option value="{{ $key }}">{{ $localidad }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class=" flex">
                        <div class="w-50">
                            <label class="font-medium opacity-80" for="nombre">
                                Impresión
                            </label>
                        </div>
                        <div class="w-50">
                            <label class="font-medium opacity-80" for="nombre">
                                Público Escolar
                            </label>
                        </div>
                    </div>
                    <div class=" mb-3 flex">
                        <div class="w-50">
                            <label class="switch">
                                <input name="impresion" id="impresion" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <div class="w-50">
                            <label class="switch">
                                <input name="publico" id="publico" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class=" mb-3">
                        <div class="w-100">
                            <label class="font-medium opacity-80" for="nombre">Tipo</label>
                        </div>
                        <div class="me-4 inline-block ">
                            <input class="bbltc-radio" type="radio" name="bbltcacceso" id="bbltcacceso-1"
                                value="1">
                            <label class="mt-px inline-block ps-[0.15rem] " for="acceso-1">Biblioteca</label>
                        </div>
                        <div class="me-4 inline-block ">
                            <input class="bbltc-radio" type="radio" name="bbltcacceso" id="bbltcacceso-2"
                                value="2">
                            <label class="mt-px inline-block ps-[0.15rem] " for="acceso-2">Bibloestación</label>
                        </div>
                        <div class="me-4 inline-block ">
                            <input class="bbltc-radio" type="radio" name="bbltcacceso" id="bbltcacceso-4"
                                value="4">
                            <label class="mt-px inline-block ps-[0.15rem] " for="acceso-4">Sala de lectura </label>
                        </div>
                        <div class="me-4 inline-block ">
                            <input class="bbltc-radio" type="radio" name="bbltcacceso" id="bbltcacceso-3"
                                value="3">
                            <label class="mt-px inline-block ps-[0.15rem] " for="acceso-3">PPP</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <button id="close-biblioteca-dialog" type="button" class="m-1 rounded-md px-3 btn">Cerrar</button>
                <button id="save-biblioteca" type="button"
                    class="bg-009F4D hover:bg-blue-900 m-1 text-white rounded-md px-3 btn"><i
                        class="fa-solid fa-floppy-disk"></i> Guardar</button>

            </div>
        </div>
    </div>
</div>
<div class="ui-widget-overlay ui-front" id="modal-mask" style="z-index: 100;display:none"></div>