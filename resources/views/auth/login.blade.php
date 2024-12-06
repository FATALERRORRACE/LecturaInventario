<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        @if (Session::has('error'))
            <div class="alert alert-danger text-center" role="alert">
                {{ Session::get('error') }}
            </div>
        @endif
        <div align="center" class="mb-3">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="opt-log-radio">
                    <div class="mt-1">
                        <x-label for="espacio" :value="__('Espacio')" class="text-white"/>
                        <select name="espacio" id="espacio" class="rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 block mt-1" required>
                            <option value="">Seleccione primero</option>
                            @foreach ($bibliotecas as $biblioteca)
                                <option value="{{$biblioteca['Id']}}">{{$biblioteca['Nombre']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- user -->
                <div class="mt-3">
                    <x-label for="alias" :value="__('Usuario')" />
                    <input id="alias" class="rounded-md shadow-sm border-gray-300 border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" name="alias" :value="old('username')" required autofocus />
                </div>

                <!-- Password -->
                <div class="mt-2">
                    <x-label for="password" :value="__('Contraseña')" />
                    <input id="password"  class="rounded-md shadow-sm border-gray-300 border rounded py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"  type="password" name="password" required
                        autocomplete="current-password" />
                </div>

                <!-- Remember Me -->
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900"
                        href="{{ route('password.request') }}"> {{ __('¿Olvidaste tu contraseña?') }}
                    </a>
                @endif
                <div class="flex items-center justify-center mt-3">
                    <x-button class="ml-3 bg-60269e">
                        {{ __('Acceder') }}
                    </x-button>
                </div>
            </form>

        </div>
        <div  class="text-gray-500">
            <p align="justify"><strong class="text-black">Nota:</strong> No almacene la contraseña en el navegador, ya que esta no
                se corresponderá con el siguiente ingreso a la herramienta. Si el navegador le autocompleta la
                contraseña, bórrela y digite la asignada.</p>
        </div>
    </x-auth-card>
</x-guest-layout>
