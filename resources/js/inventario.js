import $ from 'jquery';
import { Grid, html } from "gridjs";
import { esES } from "gridjs/l10n";
import toastr from "toastr";
import 'jquery-ui';
import 'jquery-ui/ui/effects/effect-shake';
import 'jquery-ui/ui/widgets/tabs';

export class Inventario {

    columns = [
        //{
            //name: "Códigos Escaneados", columns: [
                { id: "C_Barras", name: "Placa" },
                { id: "Situacion", name: "Situación" },
                { id: "Biblioteca_L", name: "Biblioteca" },
                { id: "Insercion", name: "Nota" },
            //]
        //}
    ];

    subcolumns = [
        //{
            //name: "Archivos Cargados", columns: [
        { id: "filename", name: "Nombre de archivo" },
        { id: "total", name: "Total Registros" },
        { id: "inserted", name: "Registros Insertados" },
        { id: "failed", name: "Registros Fallidos" },
        { id: "date", name: "Fecha" },
        {
            id: "summaryFile", name: "Archivo Resumen",
            formatter: (_, row) =>
                html(
                    `<div class="flex justify-center">
                        <a class="py-1 px-2 border rounded-md text-white " href="api/inventario/report?name=${row._cells[5].data}" style="background-color:#262D9E;">
                            <i class="fa-solid fa-download"></i>
                        </a>
                    </div>`
                )
        },
            //]
        //}
    ];

    actionInventario(eve) {
        var context = this;
        $('#dialog-form').show();
        $('#enableDate').hide();

        fetch(`api/inventario?bbltc=${$("#espacio").val()}&active=${$("#espacio").val()}`,
            {
                method: "GET",
                headers: headers,
                redirect: "follow"
            }
        )
            .then((response) => response.text().then(text => {
                $("#tableContent").html(text);
                context.inventarioUtils();
                $("#registercode").submit((event) => {
                    event.preventDefault();
                    if (
                        !$($(".clasificacion")[0]).prop('checked') &&
                        !$($(".clasificacion")[1]).prop('checked') &&
                        !$($(".clasificacion")[2]).prop('checked')
                    ) {
                        $("#container-xyz").effect('shake');
                        toastr.error('Seleccione una clasificación');
                        return;
                    }

                    $("#codbar").prop('disabled', true);

                    gridInstance.config.data.forEach(element => {
                        if (element['C_Barras'] == $('#codbar').val()) {
                            if (element.clasificacion && element.clasificacion != $("input[name=clasificacion]:checked").val())
                                return;
                            toastr.error('código de barras registrado anteriormente');
                            $("#codbar").prop('disabled', false);
                            $("#codbar").trigger('focus');
                            $("#codbar").val('');
                            throw new Error("código de barras registrado anteriormente");

                        }
                    });

                    if ($('#codbar').val() == '') {
                        toastr.error('El código de barras no puede estar vacío');
                        $("#codbar").prop('disabled', false);
                        $("#codbar").trigger('focus');
                        $("#codbar").val('');
                        return;
                    }

                    fetch(`api/inventario/${$("#espacio").val()}/new`,
                        {
                            method: "POST",
                            headers: headers,
                            redirect: "follow",
                            body: JSON.stringify({
                                'cbarras': $('#codbar').val(),
                                'categoria': $("input[name=clasificacion]:checked").val()
                            }),
                        })
                        .then((response) => response.json().then(json => {
                            $("#codbar").prop('disabled', false);
                            $("#codbar").trigger('focus');
                            $("#codbar").val('');
                            gridInstance.config.data.unshift(json);
                            gridInstance.updateConfig({
                                data: gridInstance.config.data
                            }).forceRender();
                            localStorage.setItem('alerts', JSON.stringify(gridInstance.config.data));
                        }));
                    $("#codbar").val('');
                });

            }));
        $("#calendar").off().change((eve) => {
            fetch(`api/inventario/${$("#espacio").val()}/date`,
                {
                    method: "POST",
                    headers: headers,
                    redirect: "follow",
                    body: JSON.stringify({
                        'fecha': eve.currentTarget.value
                    }),
                })
                .then((response) => response.json().then(json => {
                    if (json.status == 'ok')
                        toastr.success(json.message);
                    else
                        toastr.error(json.message);
                }));
        })
    }

    async inventarioUtils() {
        var context = this;
        subgridInstance = await new Grid({
            className: {
                th: 'table-tr-custom',
            },
            style: {
                th: {
                    'border': '1px solid #ccc',
                    'color': '#4B4F54',
                    'background-color': '#DDE5ED'
                },
            },
            columns: context.subcolumns,
            sort: true,
            pagination: true,
            language: esES,
            resizable: true,
            data: localStorage.getItem('filesUploaded') ? JSON.parse(localStorage.getItem('filesUploaded')) : [],
        }).render(document.getElementById("table-1-inventory"));

        gridInstance = await new Grid({
            className: {
                th: 'table-tr-custom',
            },
            style: {
                th: {
                    'border': '1px solid #ccc',
                    'color': '#4B4F54',
                    'background-color': '#DDE5ED'
                },
            },
            columns: context.columns,
            sort: true,
            pagination: true,
            language: esES,
            resizable: true,
            data: localStorage.getItem('alerts') ? JSON.parse(localStorage.getItem('alerts')) : [],
        }).render(document.getElementById("table-2-inventory"));

        await $("#tabs").tabs();
        $("#espacio").off('change.espacio1').off('change.espacio2').on('change.espacio2', () => {
            $("#submenu-1").trigger("click");
        });
    
    }
}