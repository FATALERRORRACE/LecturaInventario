<div class="relative transform overflow-hidden text-left transition-all w-full h-full bg-white">
    <form id="nu-form" class="mt-2 mx-2">

        <div class="flex flex-row justify-between mb-4 mx-2">
            <span class="font-medium text-gray-700 opacity-80">
                <i class="fa-solid fa-hashtag"></i> Consecutivo: {{ $registro['consecutivo'] }}
            </span>
            <span class="font-medium text-gray-700 opacity-80">
                <i class="fa-solid fa-hashtag"></i> Fecha solicitud: {{ $registro['consecutivo'] }}
            </span>
            <span class="font-medium text-gray-700 opacity-80">
                <i class="fa-solid fa-hashtag"></i> Última renovación: {{ $registro['consecutivo'] }}
            </span>
        </div>

        <div class="flex flex-row justify-between">
            <div class="m-2 basis-2/5 w-33 ">
                <label class="font-medium text-gray-700 block opacity-80" for="nombrei">
                    <i class="fa-solid fa-map-location-dot"></i> ¿Cuál es tu ciudad o departamento de residencia?
                </label>
                <select  class="rounded-md shadow-sm border-gray-300 w-100" id="diremuni" name="diremuni" required="" style="width: 100%">
                    @include('components/select-departamento')
                </select>
            </div>
            <div class="m-2 basis-2/5 w-33 ">
                <label class="font-medium text-gray-700 block opacity-80 mt-4" for="localidad">
                    <i class="fa-solid fa-map-location-dot"></i> ¿En qué localidad vives?{{$registro['localidad_numero']}}
                </label>
                
                <select class="rounded-md shadow-sm border-gray-300 w-100" id="localidad" name="localidad" style="width: 100%"
                    required="required">
                    <option>Selecciona una localidad</option>
                    @foreach ($localidades as $localidad)
                        <option value="{{ $localidad['id'] }}" {{ $localidad['id'] == $registro['localidad_numero'] ? 'selected="selected"' : '' }}>{{ $localidad['nombre'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="m-2 basis-2/5 w-33 ">
                <label class="font-medium text-gray-700 block opacity-80  mt-4" for="barrio">
                    <i class="fa-solid fa-map-location-dot"></i> ¿En qué barrio vives?
                </label>
                <select class="rounded-md shadow-sm border-gray-300 w-100" id="barrio" type="text" name="nombre" style="width: 100%" 
                    required="required" data-value="{{ $registro['barrio_id'] }}">
                    @foreach ($barrios as $key => $barrioElement)
                        <option value="{{ $barrioElement['codigo'] }}" {{ $registro['barrio_id'] == $barrioElement['id'] ? 'selected="selected"' : '' }}>{{ $barrioElement['nombre'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex flex-row justify-between">
            <div class="m-2 basis-2/5 w-33">
                <label class="font-medium text-gray-700 block opacity-80" for="tipovivienda">
                    <i class="fa-solid fa-house"></i> Tipo de vivienda
                </label>
                <select class="rounded-md shadow-sm border-gray-300 w-100" id="tipovivienda" name="tipovivienda" style="width: 100%"
                    required="required" name="tipovivienda" >
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
                <input class="rounded-md shadow-sm border-gray-300 w-100" id="direccion" type="text" name="direccion"
                    required="required" value="{{ $registro['direccion'] }}">
            </div>
            <div class="m-2 basis-2/5 w-33">
                <label class="font-medium text-gray-700 block opacity-80" for="estrato">
                    <i class="fa-solid fa-address-book"></i> Estrato
                </label>
                <select class="rounded-md shadow-sm border-gray-300 w-50" id="estrato" name="estrato"
                    required="required">
                    <option {{ $registro['estrato'] == 0 ? 'selected="selected"' : '' }}value="0">0</option>
                    <option {{ $registro['estrato'] == 1 ? 'selected="selected"' : '' }}value="1">1</option>
                    <option {{ $registro['estrato'] == 2 ? 'selected="selected"' : '' }}value="2">2</option>
                    <option {{ $registro['estrato'] == 3 ? 'selected="selected"' : '' }}value="3">3</option>
                    <option {{ $registro['estrato'] == 4 ? 'selected="selected"' : '' }}value="4">4</option>
                    <option {{ $registro['estrato'] == 5 ? 'selected="selected"' : '' }}value="5">5</option>
                    <option {{ $registro['estrato'] == 6 ? 'selected="selected"' : '' }}value="6">6</option>
                </select>
            </div>
        </div>
        <div class="flex flex-row justify-between">
            <div class="m-2 basis-2/5 w-33 ">
                <label class="font-medium text-gray-700 block opacity-80" for="ciudad">
                    <i class="fa-solid fa-location-dot"></i> Ciudad
                </label>
                <input class="rounded-md shadow-sm border-gray-300 w-100" id="ciudad" type="text" name="ciudad"
                    required="required" value="{{ $registro['ciudad'] }}">
            </div>
            <div class="m-2 basis-2/5 w-33 ">
                <label class="font-medium text-gray-700 block opacity-80" for="telefono">
                    <i class="fa-solid fa-phone"></i> Teléfono
                </label>
                <input class="rounded-md shadow-sm border-gray-300 w-100" id="telefono" type="text" name="telefono"
                    required="required" value="{{ $registro['direccion'] }}">
            </div>
            <div class="m-2 basis-2/5 w-33 ">
                <label class="font-medium text-gray-700 block opacity-80" for="nombre">
                    <i class="fa-solid fa-envelope"></i> Correo electrónico
                </label>
                <input class="rounded-md shadow-sm border-gray-300 w-100" id="nombre" type="text"
                    name="nombre" required="required" value="{{ $registro['email'] }}">
            </div>
        </div>

    </form>
</div>
