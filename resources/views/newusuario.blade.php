
<div class="relative transform overflow-hidden text-left transition-all w-full h-full">
    <div class="bg-white">
        <div class="sm:flex sm:items-start">
            <form id="nu-form" class="text-left mt-3 mx-2">
                <div class="flex">
                    <div class="m-2 w-20">
                        <label class="font-medium text-gray-700" for="nombre">
                            Nombre completo
                        </label>
                    </div>
                    <div class="my-2 mr-3 w-30">
                        <input class="rounded-md shadow-sm border-gray-300 " id="nombre" type="text"
                            name="nombre" required="required">
                    </div>
                    <div class="m-2 w-20">
                        <label class="font-medium text-gray-700" for="alias">
                            Usuario
                        </label>
                    </div>
                    <div class="my-2 mr-3 w-30">
                        <input class="rounded-md shadow-sm border-gray-300 " id="alias" type="text"
                            name="alias" required="required">
                    </div>
                </div>
                <div class="flex">
                    <div class="m-2 w-20">
                        <label class="font-medium text-gray-700" for="email">
                            Correo
                        </label>
                    </div>
                    <div class="my-2 mr-3 w-30">
                        <input class="rounded-md shadow-sm border-gray-300 " id="email" type="email"
                            name="email" required="required">
                    </div>
                    <div class="m-2 w-20">
                        <label class="font-medium text-gray-700" for="password">
                            Contraseña
                        </label>
                    </div>
                    <div class="my-2 mr-3 w-30">
                        <input class="rounded-md shadow-sm border-gray-300 " id="password" type="password"
                            name="password" required="required">
                    </div>
                </div>
                <div class="flex">
                    <div class="m-2 w-25">
                        <label class="font-medium text-gray-700" for="email">
                            Permisos
                        </label>
                    </div>
                    <div class="my-2 mr-3 w-50">
                        <select id="type" name="type" class="rounded-md shadow-sm border-gray-300" required="required">
                            <option>Privilegios</option>
                            <option value="1">Administrador</option>
                            <option value="2">Coordinador</option>
                            <option value="3">Auxiliar</option>
                            <option value="4">Visitante</option>
                        </select>
                    </div>
                    <div class="m-2 w-20">
                        <label class="font-medium text-gray-700" for="password">
                            Espacio
                        </label>
                    </div>
                    <div class="my-2 mr-3 w-30">
                        <input class="rounded-md shadow-sm border-gray-300" id="place-txt" type="text"
                            name="place-txt" disabled>
                        <input id="nubiblioteca" type="hidden" name="nubiblioteca">
                    </div>
                </div>
            </form>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
            <button id="close-dialog" type="button"
                class="m-1 rounded-md px-3 btn">Cerrar</button>
            <button id="save-new-user" type="button"
                class="bg-009F4D hover:bg-blue-900 m-1 text-white rounded-md px-3 btn"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
        </div>
    </div>
</div>