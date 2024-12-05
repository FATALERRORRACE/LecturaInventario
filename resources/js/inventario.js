import $ from 'jquery';
import { Grid, html } from "gridjs";
import { esES } from "gridjs/l10n";
import toastr from "toastr";

export class Inventario {

//" Estado":0,









    columns = [

        { id: "Insercion", name: "Insercion"},
        { id: "C_Barras", name: "C_Barras"},
        { id: "Situacion", name: "Situacion"},
        { id: "Biblioteca_L", name: "Biblioteca_L"},
        { id: "Biblioteca_O", name: "Biblioteca_O"},
        { id: "Estado", name: "Estado"},
        { id: "Usuario", name: "Usuario"},
        { id: "Fecha", name: "Fecha"},
    ];

    actionInventario(eve) {
        var context = this;
        
        if(gridInstance){
            console.log('gridInstance');
            gridInstance.config.data = [];
            gridInstance.updateConfig({
                data: [],
                columns: context.columns,
            }).forceRender();
            return;
        }else{
            console.log('gr2idInstance');
            gridInstance = new Grid({
                className: {
                    tr: 'table-tr-custom',
                },
                columns: context.columns,
                sort: true,
                pagination: true,
                language: esES,
                resizable: true,
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