import $ from 'jquery';
import { Grid, html } from "gridjs";
import { esES } from "gridjs/l10n";
import toastr from "toastr";
import 'jquery-ui';
import 'jquery-ui/ui/effects/effect-shake';

export class Inventario {

    columns = [
        { id: "C_Barras", name: "Placa"},
        { id: "Situacion", name: "Situación"},
        { id: "Biblioteca_L", name: "Biblioteca"},
        { id: "Insercion", name: "Nota"},
    ];
        
    actionInventario(eve) {
        var context = this;
        $('#dialog-form').show();
        $('#enableDate').hide();
        if(gridInstance){
            gridInstance.config.data = [];
            gridInstance.updateConfig({
                data: [],
                columns: context.columns,
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
                data: []
            }).render(document.getElementById("dialog-form"));
        }

        $("#espacio").off('change.espacio1').off('change.espacio2').on('change.espacio2', () => {
            $("#submenu-1").trigger("click");
        });

        fetch(`api/inventario?bbltc=${$("#espacio").val()}`,
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
                var entry = false;
                if($($(".clasificacion")[0]).prop('checked') || $($(".clasificacion")[1]).prop('checked') || $($(".clasificacion")[2]).prop('checked')){
                    entry = true;
                }
                if(!entry){
                    $("#container-xyz").effect('shake');
                    toastr.error('Seleccione una clasificación');
                    return;
                }
                $("#codbar").prop('disabled',true);
                gridInstance.config.data.forEach( element => {
                    if(element['C_Barras'] == $('#codbar').val()){
                        toastr.error('código de barras registrado anteriormente');
                        $("#codbar").prop('disabled',false);
                        $("#codbar").trigger('focus');
                        $("#codbar").val('');
                        throw new Error("código de barras registrado anteriormente");
                    }
                });

                if($('#codbar').val() == ''){
                    toastr.error('El código de barras no puede estar vacío');
                    $("#codbar").prop('disabled',false);
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
                    $("#codbar").prop('disabled',false);
                    $("#codbar").trigger('focus');
                    $("#codbar").val('');
                    gridInstance.config.data.unshift(json);
                    gridInstance.updateConfig({
                        data: gridInstance.config.data
                    }).forceRender();
                }));
                $("#codbar").val('');
            });

        }));
        $("#calendar").off().change((eve)=>{
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
                toastr.success(json.message);
            }));
        })
    }

}