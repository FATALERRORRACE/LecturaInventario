import $ from 'jquery';
import { Grid, html } from "gridjs";
import { esES } from "gridjs/l10n";
import "select2";
import 'jstree/dist/jstree';
import 'jquery-ui/ui/widgets/dialog';
import toastr from "toastr";

export class Avances {
    controller = new AbortController();
    signal = this.controller.signal;
    sendSignal = 0;
    columns = [
        {
            name: "Códigos Escaneados", columns: [
                { id: "C_Barras", name: "C_Barras", width: '100px' },
                { id: "Usuario", name: "Usuario", width: '100px' },
                { id: "Situacion", name: "Situacion", width: '100px' },
                { id: "Comentario", name: "Comentario", width: '100px' },
                { id: "Fecha", name: "Fecha", width: '100px' },
                { id: "Estado", name: "Estado", width: '100px' },
            ]
        }
    ];

    actionAvances() {
        var context = this;
        $("#tableContent").html('');
        $('#enableDate').hide();
        fetch(`api/avances/${$("#espacio").val()}/info`,
            {
                method: "GET",
                headers: headers,
                redirect: "follow"
            }
        )
            .then((response) => response.text().then(text => {
                $('#tableContent').html(text);
                context.utils();
            }));

    }

    utils() {
        var context = this;

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
        }).render(document.getElementById("table-advances"));

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
                            $('#expordata').hide();
                            return;
                        }
                        response.json().then(json => {
                            $('#dialog-form').show();
                            $('#alert-no-exist').hide();
                            $('#expordata').show();
                            $('#calendar').val(json.fecha);
                            gridInstance.updateConfig({
                                columns: context.columns,
                                data: json.data
                            }).forceRender();
                        });
                    });
            });
        $("#espacio").trigger("change");
    }

    openDialogTree() {
        var globalContext = this;
        globalContext.sendSignal = 0;
        fetch(`api/avances/${$("#espacio").val()}/tree`,
            {
                method: "GET",
                headers: headers,
                redirect: "follow"
            }
        )
        .then((response) => response.text().then(text => {
            $('#dialog-form').html(text);
            $("#dialog-form").dialog({
                autoOpen: true,
                position: { my: "top", at: "bottom", of: $('#contain-e-t') },
                height: 'auto',
                width: 'auto',
                modal: false,
                open: function (event, ui) {
                    $(".ui-dialog-title").text('');
                },
                close: function (event, ui) {
                },
            });
            $('#jstree').on('changed.jstree', function (e, data) {
                fetch(`api/avances/${$("#espacio").val()}/tree/clasificacion?lcl=${data.selected}&search=${$("#search-clsfcn").val()}`,
                    {
                        method: "GET",
                        headers: headers,
                        redirect: "follow"
                    }
                ).then((response) => response.text().then(text => {
                    $('#jstre2e').html(text).jstree();
                    
                }));
            }).jstree();
            $("#search-clsfcn").on('keyup', function (event) {
                event.preventDefault();
                console.log($("#jstree").jstree("get_selected"));
                if(globalContext.sendSignal){
                    globalContext.controller.abort();
                    globalContext.controller = new AbortController();
                }
                globalContext.sendSignal = 1;
                fetch(`api/avances/${$("#espacio").val()}/tree/clasificacion?lcl=${$("#jstree").jstree("get_selected")[0]}&search=${$("#search-clsfcn").val()}`,
                    {
                        method: "GET",
                        headers: headers,
                        redirect: "follow",
                        signal: globalContext.controller.signal,
                    }
                ).then((response) => response.text().then(text => {
                    globalContext.sendSignal = 0;
                    $('#jstre2e').jstree("destroy");
                    $('#jstre2e').html(text);
                    $('#jstre2e').jstree();
                })).catch((err)=>{
                    console.log(err);
                });
            });
            $('#jstre3e').jstree();
            
        }));
    

    }
}