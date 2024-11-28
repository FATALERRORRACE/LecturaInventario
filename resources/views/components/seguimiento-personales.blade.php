<div class="relative transform overflow-hidden text-left transition-all w-full h-full bg-white">
    <form id="nu-form" class="mt-2 mx-2">
        <div class="flex flex-row justify-between mb-4 mx-2">
            <span class="font-medium text-gray-700 opacity-80">
                <i class="fa-solid fa-hashtag"></i> Consecutivo: {{ $registro['consecutivo'] }}
            </span>
            <span class="font-medium text-gray-700 block opacity-80"><i class="fa-solid fa-circle-dot"></i> Tipo A
                Tipo B</span>
            <span class="font-medium text-gray-700 block opacity-80"><i class="fa-solid fa-cake-candles"></i> Edad:
                {{ $registro['edad'] }}</span>
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
            <div class="m-2 w-50">
                <label class="font-medium text-gray-700 block opacity-80" for="tdocumento">
                    <i class="fa-solid fa-id-card"></i> Tipo de documento
                </label>
                <select class="rounded-md shadow-sm border-gray-300 w-100" id="tdocumento" name="tdocumento"
                    onchange="func_documento(this.value)">
                    <option {{ $registro['tipo_documento'] == 1 ? 'selected="selected"' : '' }} value="1">
                        Registro civil</option>
                    <option {{ $registro['tipo_documento'] == 2 ? 'selected="selected"' : '' }} value="2">
                        Tarjeta de identidad</option>
                    <option {{ $registro['tipo_documento'] == 3 ? 'selected="selected"' : '' }} value="3">Cédula
                        de ciudadanía</option>
                    <option {{ $registro['tipo_documento'] == 4 ? 'selected="selected"' : '' }} value="4">Cédula
                        de extranjería</option>
                    <option {{ $registro['tipo_documento'] == 5 ? 'selected="selected"' : '' }} value="5">
                        Permiso especial de permanencia</option>
                    <option {{ $registro['tipo_documento'] == 6 ? 'selected="selected"' : '' }} value="6">
                        Documento de identidad - (No Colombia)</option>
                    <option {{ $registro['tipo_documento'] == 8 ? 'selected="selected"' : '' }} value="8">
                        Permiso de protección temporal</option>
                    <option {{ $registro['tipo_documento'] == 24 ? 'selected="selected"' : '' }} value="24">
                        Número Establecido por la SED</option>
                </select>
            </div>

            <div class="m-2 w-50">
                <label class="font-medium text-gray-700 block opacity-80" for="nodocumento">
                    <i class="fa-solid fa-id-card"></i> No.
                </label>
                <input class="rounded-md shadow-sm border-gray-300 w-100" id="nodocumento" type="text"
                    name="nodocumento" required="required" value="{{ $registro['n_documento'] }}">
            </div>

        </div>

        <div class="flex flex-row justify-between">
            <div class="m-2 w-50">
                <label class="font-medium text-gray-700 block opacity-80" for="genero">
                    <i class="fa-solid fa-circle-question"></i> País de nacionalidad
                </label>
                <select name="convenio" id="convenio" class="rounded-md shadow-sm border-gray-300 w-100">
                    @include('components/select-pais')
                </select>
            </div>
            <div class="m-2 w-50">
                <label class="font-medium text-gray-700 block opacity-80" for="genero">
                    <i class="fa-solid fa-circle-question"></i> País de expedición
                </label>
                <select name="programa" id="programa" class="rounded-md shadow-sm border-gray-300 w-100">
                    @include('components/select-pais')
                </select>
            </div>
        </div>

        <div class="flex flex-row justify-between">
            <div class="m-2 basis-2/5 w-33 ">
                <label class="font-medium text-gray-700 block opacity-80" for="nombrei">
                    <i class="fa-regular fa-id-badge"></i> Nombre identitario
                </label>
                <input class="rounded-md shadow-sm border-gray-300 w-100" id="nombrei" type="text" name="nombrei"
                    required="required" value="{{ $registro['nomb_ident'] }}">
            </div>
            <div class="m-2 basis-2/5 w-33 ">
                <label class="font-medium text-gray-700 block opacity-80" for="apellidos">
                    <i class="fa-solid fa-user-doctor"></i> Seleccione la ocupación
                </label>
                <select id="ocupacion" name="ocupacion" class="rounded-md shadow-sm border-gray-300 w-100"
                    required="">
                    <option value="Ama de casa" selected="selected">Ama de casa</option>
                    <option value="Desempleado">Desempleado</option>
                    <option value="Docente">Docente</option>
                    <option value="Empleado">Empleado</option>
                    <option value="Estudiante">Estudiante</option>
                    <option value="Independiente">Independiente</option>
                    <option value="Otros">Otros</option>
                    <option value="Pensionado">Pensionado</option>
                </select>
            </div>

            <div class="m-2 basis-2/5 w-33 ">
                <label class="font-medium text-gray-700 block opacity-80" for="empresa">
                    <i class="fa-solid fa-building"></i> Empresa o institución educativa
                </label>
                <input class="rounded-md shadow-sm border-gray-300 w-100" id="empresa" type="text"
                    name="empresa" required="required" value="{{ $registro['empresa'] }}">
            </div>

        </div>

        <div class="flex flex-row justify-between">
            <div class="m-2 basis-2/5 w-33 ">
                <label class="font-medium text-gray-700 block opacity-80" for="nacimiento">
                    <i class="fa-regular fa-calendar-days"></i> Fecha de nacimiento
                </label>
                <input class="rounded-md shadow-sm border-gray-300 w-100" id="nacimiento" type="date"
                    name="nacimiento" required="required" value="{{ $registro['fecha_nacimiento'] }}">
            </div>
            <div class="m-2 basis-2/5 w-33 ">
                <label class="font-medium text-gray-700 block opacity-80" for="genero">
                    <i class="fa-solid fa-venus-mars"></i> Género
                </label>
                <select class="rounded-md shadow-sm border-gray-300 w-100" id="genero" name="genero"
                    required="required">
                    <option value="">Selecciona tu identidad de género</option>
                    <option value="2;F">Femenino</option>
                    <option value="1;M">Masculino</option>
                    <option value="5;B">No binario</option>
                    <option value="4;T">Transgénero</option>
                    <option value="3;O">Otro</option>
                    <option selected="selected"></option>
                </select>
            </div>

            <div class="m-2 basis-2/5 w-33 ">
                <label class="font-medium text-gray-700 block opacity-80" for="escolaridad">
                    <i class="fa-solid fa-book"></i> Nivel de escolaridad
                </label>
                <select name="escolaridad" class="rounded-md shadow-sm border-gray-300 w-100" required="">
                    <option value="Ninguno" selected="selected">Ninguno</option>
                    <option value="Preescolar">Preescolar</option>
                    <option value="Primaria">Primaria</option>
                    <option value="Secundaria">Secundaria</option>
                    <option value="Tecnico">Tecnico</option>
                    <option value="Tecnologo">Tecnologo</option>
                    <option value="Universitario">Universitario</option>
                    <option value="Posgrado">Posgrado</option>
                </select>
            </div>
        </div>

        <div class="flex flex-row justify-between">
            <div class="m-2 basis-2/5 w-33 ">
                <label class="font-medium text-gray-700 block opacity-80" for="genero">
                    <i class="fa-solid fa-circle-question"></i> ¿Se afilia usted en el marco de un convenio?
                </label>
                <select name="convenio" id="afiiaconvenio" class="rounded-md shadow-sm border-gray-300 w-100">
                    <option value="No" selected="selected">No</option>
                    <option value="Convenio publico escolar">Convenio publico escolar</option>
                    <option value="Bibliotecas Colsubsidio">Bibliotecas Colsubsidio</option>
                    <option value="Cámara de Comercio">Cámara de Comercio</option>
                    <option value="Pasaporte Escolar"
                        title="Válido solo para estudiantes del distrito con pasaporte escolar">Pasaporte Escolar
                    </option>
                </select>
            </div>
            <div class="m-2 basis-2/5 w-33 ">
                <label class="font-medium text-gray-700 block opacity-80" for="genero">
                    <i class="fa-solid fa-circle-question"></i> ¿Se afilia usted en el marco de un programa?
                </label>
                <select name="programa" id="programa" class="rounded-md shadow-sm border-gray-300 w-100">
                    <option value="No" selected="selected">No</option>
                    <option value="Afiliación masiva">Afiliación masiva</option>
                    <option value="Afiliación Universal">Afiliación Universal</option>
                    <option value="Programas lectura">Programas lectura</option>
                    <option value="Programas servicios">Programas servicios</option>
                    <option value="Programas cultura">Programas cultura</option>
                </select>
            </div>
            <div class="m-2 basis-2/5 w-33 ">
                <label class="font-medium text-gray-700 block opacity-80" for="nacimiento">
                    <i class="fa-solid fa-circle-question"></i> Usuario con discapacidad
                </label>
                <input name="discapacidad" id="discapacidad" type="checkbox" value="Si">
            </div>
            
        </div>
        <div class="flex flex-row justify-between">
            <div class="m-2 w-100">
                <input name="autoriza" type="checkbox" value="Si">
                <label class="font-medium text-gray-700 opacity-80" for="nombre">
                    Autorizo enviar información de BibloRed a mi correo electrónico.
                </label>
            </div>
        </div>

    </form>
</div>
