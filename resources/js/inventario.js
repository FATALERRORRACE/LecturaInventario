import $ from 'jquery';
import { Grid, html } from "gridjs";
import { esES } from "gridjs/l10n";
import toastr from "toastr";

export class Inventario {

    columns = [
        { id: 'InsercionEstado', name: 'InserciónEstado', hidden: true },
        { id: 'Inserción', name: 'Inserción',
            formatter: (_, row) => 
                html(
                `<div class="flex justify-center">
                    <button class="py-1 px-2 border rounded-md text-white ${(row['_cells'][0]['data'] == 0 ? 'bg-red-600' : 'bg-invented-300' )}" onclick="editUser(${row._cells[0].data})">
                        ${row['_cells'][6]['data']}
                    </button>
                </div>`)
            ,
        },
        { id: 'C_Barras', name: 'C_Barras'},
        { id: 'Biblioteca_L', name: 'Biblioteca_L'},
        { id: 'Biblioteca_O', name: 'Biblioteca_O'},
        { id: 'Fecha', name: 'Fecha'},
        { id: 'Situacion', name: 'Situacion'},
        { id: 'Usuario', name: 'Usuario'},
    ];

    actionInventario(eve) {
        var context = this;
        if(gridInstance){
            gridInstance.updateConfig({
                columns: context.columns,
                data: []
            }).forceRender();
        }else{
            gridInstance = new Grid({
                className: {
                    tr: 'table-tr-custom',
                },
                columns: context.columns,
                sort: true,
                pagination: true,
                language: esES,
                resizable: true,
                selector: (cell, rowIndex, cellIndex) => cellIndex === 0 ? cell.firstName : cell,
                data: []
            }).render(document.getElementById("dialog-form"));
        }

        fetch(`api/inventario`,
            {
                method: "GET",
                headers: headers,
                redirect: "follow"
            }
        )
        .then((response) => response.text().then(text => {

            $("#tableContent").html(text);
            $("#registercode").submit((event) => {
                event.preventDefault();
                if($('#codbar').val() == ''){
                    toastr.error('El código de barras no puede estar vacío');
                    return;
                }
                fetch(`api/inventario/${$("#espacio").val()}/new`,
                {
                    method: "POST",
                    headers: headers,
                    redirect: "follow",
                    body: JSON.stringify({
                        'cbarras': $('#codbar').val()
                    }),
                })
                .then((response) => response.json().then(json => {
                    gridInstance.config.data.unshift(json);
                    gridInstance.updateConfig({
                        data: gridInstance.config.data
                    }).forceRender();
                }));
                $("#codbar").val('');
            });

        }));
    }
}