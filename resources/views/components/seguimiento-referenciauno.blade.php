<div class="relative transform overflow-hidden text-left transition-all w-full h-full bg-white">
    <form id="nu-form" class="mt-2 mx-2">
        <div class="flex flex-row justify-between mb-4 mx-2">
            <span class="font-medium text-gray-700 opacity-80">
                <i class="fa-solid fa-hashtag"></i> Consecutivo: {{ $registro['consecutivo'] }}
            </span>
        </div>

        <div class="flex flex-row justify-between">
            <div class="m-2 w-50">
                <label class="font-medium text-gray-700 block opacity-80" for="apellidos">
                    <i class="fa-solid fa-user"></i> Apellidos
                </label>
                <input class="rounded-md shadow-sm border-gray-300 w-100" id="apellidos" type="text" name="apellidos"
                    required="required" value="{{ $registro['apellidos'] }}">
            </div>
            <div class="m-2 w-50">
                <label class="font-medium text-gray-700 block opacity-80" for="nombre">
                    <i class="fa-solid fa-user"></i> Nombres
                </label>
                <input class="rounded-md shadow-sm border-gray-300 w-100" id="nombre" type="text" name="nombre"
                    required="required" value="{{ $registro['nombres'] }}">
            </div>
        </div>
        <div class="flex flex-row justify-between">
            <div class="m-2 basis-2/5 w-33 ">
                <label class="font-medium text-gray-700 block opacity-80" for="tipovivienda">
                    <i class="fa-solid fa-house"></i> Tipo de vivienda
                </label>
                <select class="rounded-md shadow-sm border-gray-300 w-100" id="tipovivienda" name="tipovivienda"
                    required="required" name="tipovivienda" onchange="rural('2');">
                    <option selected="selected"></option>
                    <option {{ $registro['tipo_vivienda'] == 'Casa' ? 'selected="selected"' : '' }} value="Casa">
                        Casa</option>
                    <option {{ $registro['tipo_vivienda'] == 'Apartamento' ? 'selected="selected"' : '' }}
                        value="Apartamento">Apartamento</option>
                    <option {{ $registro['tipo_vivienda'] == 'Rural' ? 'selected="selected"' : '' }} value="Rural">
                        Rural</option>
                </select>
            </div>
            <div class="m-2 basis-2/5 w-33">
                <label class="font-medium text-gray-700 block opacity-80" for="direccion">
                    <i class="fa-solid fa-location-dot"></i> Dirección
                </label>
                <input class="rounded-md shadow-sm border-gray-300 w-100" id="direccion" type="text"
                    name="direccion" required="required" value="{{ $registro['direccion'] }}">
            </div>
            <div class="m-2 basis-2/5 w-33">
                <label class="font-medium text-gray-700 block opacity-80" for="nombre">
                    <i class="fa-solid fa-phone"></i> Teléfono fijo
                </label>
                <input class="rounded-md shadow-sm border-gray-300 w-100" id="nombre" type="text"
                    name="nombre" required="required" value="{{ $registro['tel_fijo'] }}">
            </div>
        </div>


    </form>
</div>
