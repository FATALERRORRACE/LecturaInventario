<div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="text-center border-t border-gray-1 text-4B3863 px-4 pt-3" role="alert">
        <p class="font-bold text-xl">Bibloteca Seleccionada: <span id="sel-bbl"></span></p>
    </div>
    <form class="justify-center text-center flex" id="registercode">
        <div class="mt-2 w-50" id="alert-no-exist" style="{{ $tableExists == false ? 'display:none;' : '' }}">
            <div class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert">
                <p style="color: #9E4A24;">La biblioteca no se ha creado.</p>
                <button id="create-items"
                    class="mt-2 bg-blue-500 hover:bg-blue-700 text-white  py-2 px-4 border border-blue-700 rounded opacity-70"
                    style="background-color: #6C9E24;">
                    Crear tabla y exportar datos
                </button>
            </div>
        </div>
    </form>
</div>
