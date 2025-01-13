<nav role="navigation" aria-labelledby="block-biblored-tailwind-stable-menuprincipal-menu"
    id="block-biblored-tailwind-stable-menuprincipal" class="settings-tray-editable wrapper bg-white border-gray-200"
    data-drupal-settingstray="editable">
    <div class="flex">
        <div class="flex-none w-64 ">
            <a href="/" rel="home" class="img-001 flex items-center md:max-w-[200px] lg:max-w-[200px]">
                <img src="https://www.biblored.gov.co/sites/default/files/logo-biblored.svg" alt="Inicio"
                    class="mr-3">
            </a>
            <p class="base-1-color" style="font-size: x-large;font-weight: 300;">Inventario</p>
        </div>
        <div id="contain-e-t" class="flex-1 w-64 rounded bg-white">
            <div class="flex justify-center margin-50 mt-5">
                <select id="espacio" class="base-1-color js-example-basic-single border-bblr-1" {{ session("admin") == 1 ? '' : 'disabled' }}>
                    <option value="">Asigne una biblioteca</option>
                    @foreach ($bibliotecas as $biblioteca)
                        <option value="{{$biblioteca['Id']}}" {{(session('espacio') == $biblioteca['Id'] ? 'selected' : '' )}}>{{$biblioteca['Nombre']}}</option>
                    @endforeach
                </select>
                <div class="block mx-3" id="enableDate">
                    <p class="text-center">Fecha de habilitación</p>
                    <input id="daterange" class="border px-3 base-1-color rounded-lg p-2" type="text" value="" placeholder="Ingrese el rango de fecha"/>
                </div>
            </div>
        </div>
        <div class="flex-none w-64">
            <div class="flex justify-center">
                <div class="dropdown dropdown-hover">
                    <button data-mdb-button-init data-mdb-ripple-init data-mdb-dropdown-init class="rounded-lg dropdown-toggle btn rounded-lg mt-4" type="button" id="user" data-mdb-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user"></i> {{ session('username') }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-hover" aria-labelledby="">
                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}" id="end-sess">
                                <i class="fa-solid fa-right-from-bracket"></i> Cerrar Sesión
                            </a>
                            </ulli>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
