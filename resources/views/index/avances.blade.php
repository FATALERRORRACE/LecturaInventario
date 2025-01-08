<div id="messagedraganddrop" style="display:none;">
    <p class="w-100">Suelta el archivo acá</p>
</div>

@if ($tableExists == false)
    <div class="justify-center text-center flex">
        <div class="mt-2 w-50">
            <div class="text-xl bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 border" role="alert">
                <p id="txt-create-items" class="border" style="color: #9E4A24;">La biblioteca no se ha creado.</p>
            </div>
        </div>
    </div>
@else
    <div class="my-2 text-center justify-center items-center flex flex-col">
        <table class=" table-auto min-w-max table-auto border border-l " style="border-color: #c1a7e285">
            <tr>
                <th colspan="{{ count($situacionesI) }}" class="p-2 border-b border-blue-gray-100 bg-blue-gray-50">Total
                    Material: {{ number_format($total) }}</th>
            </tr>
            <tr>
                <th colspan="{{ count($situacionesI) }}" class="p-2 border-b border-blue-gray-100 bg-blue-gray-50">Total
                    Inventariado: {{ number_format($inventariado) }}</th>
            </tr>
            <tr>
                @foreach ($situacionesI as $key => $situacion)
                    <th class="p-2 border border-blue-gray-100 bg-blue-gray-50">{{ $key }}</th>
                @endforeach
            </tr>
            <tbody>
                <tr>
                    @foreach ($situacionesI as $key => $situacion)
                        <td class="p-2 border border-blue-gray-50">{{ number_format($situacion) }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
    <div class="my-2 text-center justify-center items-center flex flex-col">
        <table class=" table-auto min-w-max table-auto border border-l " style="border-color: #c1a7e285">
            <tr>
                <th colspan="{{ count($situacionesI) }}" class="p-2 border-b border-blue-gray-100 bg-blue-gray-50">Total
                    Prestado: {{ number_format($prestado) }}</th>
            </tr>
            <tr>
                @foreach ($situacionesP as $key => $situacion)
                    <th class="p-2 border border-blue-gray-100 bg-blue-gray-50">{{ $key }}</th>
                @endforeach
            </tr>
            <tbody>
                <tr>
                    @foreach ($situacionesP as $key => $situacion)
                        <td class="p-2 border border-blue-gray-50">{{ number_format($situacion) }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
    <div class="my-2 text-center justify-center items-center flex flex-col">
        <table class="w-full table-auto min-w-max table-auto border " style="border-color: #c1a7e285">
            <tr>
                <th colspan="{{ count($situacionesNI) }}" class="p-2 border-b border-blue-gray-100 bg-blue-gray-50 ">
                    Total Sin Inventariar: {{ number_format($noInventariado) }}</th>
            </tr>
            <tr>
                @foreach ($situacionesNI as $key => $situacion)
                    <th class="p-2 border border-blue-gray-100 bg-blue-gray-50">{{ $key }}</th>
                @endforeach
            </tr>
            <tbody>
                <tr>
                    @foreach ($situacionesNI as $key => $situacion)
                        <td class="p-2 border border-blue-gray-50">{{ number_format($situacion) }}</td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
    <div id="table-advances"></div>
@endif