import $ from 'jquery';
import { Grid, html } from "gridjs";
import { esES } from "gridjs/l10n";

export class Inventario {

    columns = [
        { id: 'InserciónEstado', name: 'InserciónEstado'},
        { id: 'Inserción', name: 'Inserción',
            formatter: (_, row) => html(
                `<div class="flex justify-center">
                    <button class="py-1 px-2 border rounded-md text-white bg-invented-300" onclick="editUser(${row._cells[0].data})">
                        <i class="fa-solid fa-check"></i> Validar
                    </button>
                </div>`
            ),
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
                gridInstance.config.data.push({
                    'alias': $("#codbar").val(),
                    'bibloteca': $('#espacio').find(":selected").text()
                });
                gridInstance.updateConfig({
                    data: gridInstance.config.data
                }).forceRender();
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
                    console.log(json);
                }));
                $("#codbar").val('');
            });

        }));
    }
}