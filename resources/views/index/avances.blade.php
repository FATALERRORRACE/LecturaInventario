<div id="messagedraganddrop" style="display:none;">
    <p class="w-100">Suelta el archivo acá</p>
</div>

@if ($tableExists == false)
    <div class="justify-center text-center flex">
        <div class="mt-2 w-50">
            <div class="text-xl bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4" role="alert">
                <p id="txt-create-items" style="color: #9E4A24;">La biblioteca no se ha creado.</p>
            </div>
        </div>
    </div>
@else
    <div class="justify-center text-center flex">
        <table  class="w-2/5 text-left table-auto min-w-max table-auto border-b" style="border-color: #c1a7e285">
            <thead>
                <tr>
                    <th colspan="3" class="p-2 border-b border-blue-gray-100 bg-blue-gray-50">Inventario</th>
                </tr>
                <tr>
                    <th class="p-2 border-b border-blue-gray-100 bg-blue-gray-50">Total Material</th>   
                    <th class="p-2 border-b border-blue-gray-100 bg-blue-gray-50">Total Inventariado</th>
                    <th class="p-2 border-b border-blue-gray-100 bg-blue-gray-50">Total Sin Inventariar</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="p-2 border-b border-blue-gray-50">{{ number_format($total) }}</td>
                    <td class="p-2 border-b border-blue-gray-50">{{ number_format($inventariado) }}</td>
                    <td class="p-2 border-b border-blue-gray-50">{{ number_format($noInventariado) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="w-3/5 text-left table-auto min-w-max table-auto border-b border-l"  style="border-color: #c1a7e285">
            <tr>
                <th colspan="{{count($situaciones)}}" class="p-2 border-b border-blue-gray-100 bg-blue-gray-50">Situación</th>
            </tr>
            <tr>
                @foreach ($situaciones as $key => $situacion)
                    <th class="p-2 border-b border-blue-gray-100 bg-blue-gray-50">{{$key}}</th>
                @endforeach
            </tr>
            <tbody>
                <tr>
                    @foreach ($situaciones as $key => $situacion)
                        <td class="p-2 border-b border-blue-gray-50">{{number_format($situacion)}}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
@endif
