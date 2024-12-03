import $ from 'jquery';
import { Grid, html } from "gridjs";
import { esES } from "gridjs/l10n";
import "select2";
import toastr from "toastr";

export class Administracion {

    columns = [
        { id: "C_Barras", name: "C_Barras", width: '100px' },
        { id: "Usuario", name: "Usuario", width: '100px' },
        { id: "Situacion", name: "Situacion", width: '100px' },
        { id: "Comentario", name: "Comentario", width: '100px' },
        { id: "Fecha", name: "Fecha", width: '100px' },
        { id: "Estado", name: "Estado", width: '100px' },
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

        if(gridInstance)
            gridInstance.updateConfig({
                columns: context.columns,
                search: true,
            });
        else
            gridInstance = new Grid({
                search: true,
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
            $("#expordata").click(()=>{
                window.open(`${location.href}api/admin/data/${$("#espacio").val()}/xls`);
            })
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
                $("#loader-adm").show();
                $("#txt-create-items").hide();
                $("#create-items").hide();
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
                    $("#loader-adm").hide();
                }));
            });

        }));
    }
}