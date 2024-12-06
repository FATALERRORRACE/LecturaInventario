import $ from 'jquery';
import { Grid, html } from "gridjs";
import { esES } from "gridjs/l10n";
import "select2";
import toastr from "toastr";

export class Avances {

    columns = [
        { id: "C_Barras", name: "C_Barras", width: '100px' },
        { id: "Usuario", name: "Usuario", width: '100px' },
        { id: "Situacion", name: "Situacion", width: '100px' },
        { id: "Comentario", name: "Comentario", width: '100px' },
        { id: "Fecha", name: "Fecha", width: '100px' },
        { id: "Estado", name: "Estado", width: '100px' },
    ];

    actionAvances(){
        var context = this;
        $('#enableDate').show();
        $("#espacio")
        .off('change.espacio3')
        .off('change.espacio2')
        .off('change.espacio1')
        .on('change.espacio3', () => {
            $("#sel-bbl").text($("#espacio").find(':selected').text());
            fetch(`api/admin/${$("#espacio").val()}/dataadvance`,
            {
                method: "POST",
                headers: headers,
                redirect: "follow",
            })
            .then((response) => {
                if (response.status == 500) {
                    $('#calendar').val('');
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
                    $('#calendar').val(json.fecha);
                    gridInstance.updateConfig({
                        columns: context.columns,
                        data: json.data
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

        $("#tableContent").html('');
        $("#espacio").trigger("change");
    }
}