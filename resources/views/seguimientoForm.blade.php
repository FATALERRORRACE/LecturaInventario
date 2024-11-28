<div id="tabs">
    <ul>
        <li><a href="#tabs-1">PERSONALES</a></li>
        <li><a href="#tabs-2">CONTACTO</a></li>
        <li><a href="#tabs-3">REFERENCIA</a></li>
    </ul>
    <div id="tabs-1">
        @include('components/seguimiento-personales')
    </div>
    <div id="tabs-2">
        @include('components/seguimiento-afiliacion')
    </div>
    <div id="tabs-3">
        @include('components/seguimiento-referenciauno')
    </div>
</div>

<div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
    <button id="close-dialog" type="button" class="m-1 rounded-md px-3 btn">Cerrar</button>
    <button id="save-new-user" type="button" class="bg-009F4D hover:bg-blue-900 m-1 text-white rounded-md px-3 btn">
        <i class="fa-solid fa-floppy-disk"></i> Guardar
    </button>
</div>