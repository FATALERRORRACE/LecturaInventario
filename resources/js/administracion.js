import $ from 'jquery';
import { Grid, html } from "gridjs";
import { esES } from "gridjs/l10n";
import "select2";
import toastr from "toastr";

export class Administracion {
    columns = [
        { id: "C_Barras", name: "Código de Barras", width: '100px' },
        { id: "Situacion", name: "Código de Barras", width: '100px' },
        { id: "Comentario", name: "Código de Barras", width: '100px' },
        { id: "Usuario", name: "Código de Barras", width: '100px' },
        { id: "Estado", name: "Código de Barras", width: '100px' },
        { id: "Fecha", name: "Código de Barras", width: '100px' },
        {
            name: 'Acción',
            width: '80px',
            formatter: (_, row) => html(
                `<div class="flex justify-center">
                    <button class="py-1 px-2 border rounded-md text-white bg-invented-300" onclick="editUser(${row._cells[0].data})">
                        <i class="fa-solid fa-check"></i> Validar
                    </button>
                </div>`
            ),
        },
    ];
    actionAdmin() {
        var context = this;
        $("#espacio").off('change.espacio1');

        $("#espacio").on('change.espacio1', () => {
            $("#sel-bbl").text($("#espacio").find(':selected').text());
            
            fetch(`api/admin/${$("#espacio").val()}/data`,
                {
                    method: "POST",
                    headers: headers,
                    redirect: "follow"
                })
                .then((response) => {
                    console.log(response.status);
                    if (response.status == 500) {
                        
                        $('#dialog-form').hide();
                        gridInstance.updateConfig({
                            columns: context.columns,
                            data: []
                        }).forceRender();
                        $('#alert-no-exist').show();
                        return;
                    }
                    response.json().then(json => {
                        $('#dialog-form').show();
                        $('#alert-no-exist').hide();
                        gridInstance.updateConfig({
                            columns: context.columns,
                            data: json
                        }).forceRender();
                    });   
                });
        }); 
        if(gridInstance){

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

        fetch(`api/admin/${$("#espacio").val()}`,
            {
                method: "GET",
                headers: headers,
                redirect: "follow"
            }
        )
            .then((response) => response.text().then(text => {
                $("#tableContent").html(text);
                $("#espacio").trigger("change");
                $("#registercode").submit((event) => {
                    event.preventDefault();
                    gridInstance.config.data.push({
                        'alias': $("#codbar").val(),
                        'bibloteca': $('#espacio').find(":selected").text()
                    });
                    gridInstance.updateConfig({
                        columns: columns,
                        data: gridInstance.config.data
                    }).forceRender();
                    $("#codbar").val('');
                });
                
                $("#create-items").click(() => {
                    fetch('/api/admin/biblioteca/set',
                        {
                            method: "POST",
                            headers: headers,
                            body: JSON.stringify({
                                'table': $('#espacio').find(":selected").text()
                            }),
                        }
                    )
                    .then((response) => response.json().then( json => {
                        toastr.success(json.message);
                        $("#submenu-4").trigger('click');
                    }));
                });
            }));

    }
}