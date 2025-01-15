import $ from 'jquery';
import { Grid, html } from "gridjs";
import { esES } from "gridjs/l10n";
import "select2";
import toastr from "toastr";
import moment from "moment";
export class Administracion {

    columns = [
        { id: "C_Barras", name: "C_Barras", width: '100px' },
        { id: "Usuario", name: "Usuario", width: '100px' },
        { id: "Situacion", name: "Situacion", width: '100px' },
        { id: "Comentario", name: "Comentario", width: '100px' },
        { id: "Fecha", name: "Fecha", width: '100px' },
        { id: "Estado", name: "Estado", width: '100px' },
    ];

    actionAdmin(){
        var context = this;
        $('#enableDate').show();

        fetch(`api/admin/${$("#espacio").val()}`,
            {
                method: "GET",
                headers: headers,
                redirect: "follow"
            }
        )
        .then((response) => response.text().then(text => {
            $("#tableContent").html(text);
            context.actionAdminUtils();
            $("#espacio").trigger("change");
            //context.setDateAndSetEvent($("#datehidden").val());

            $("#expordata").click(()=>{
                window.open(`${location.href}admin/data/${$("#espacio").val()}/xls`);
            });

            $("#expordataRegister").click(()=>{
                window.open(`${location.href}admin/data/${$("#espacio").val()}/master/xls`);
            });
            $("#expordataRegisterPos").click(()=>{
                window.open(`${location.href}admin/data/${$("#espacio").val()}/master/xls?posInventario=1`);
            });

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
                fetch('/api/admin/biblioteca/set',
                    {
                        method: "POST",
                        headers: headers,
                        body: JSON.stringify({
                            'table': $('#espacio').find(":selected").text()
                        }),
                    }
                )
                .then((response) => {
                    console.log(response);
                    response.json().then(json => {
                        toastr.success(json.message);
                        $("#submenu-4").trigger('click');
                        $("#loader-adm").hide();
                    })
                });
            });
        }));
    }

    actionAdminUtils(){
        var context = this;
        $("#pos-inventario").click((eve)=>{
            console.log(eve);
            fetch(`api/admin/${$("#espacio").val()}/posinventario?active=${$("#pos-inventario").prop('checked')}`,
            {
                method: "PUT",
                headers: headers,
                redirect: "follow"
            })
            .then((response) =>  response.json().then(json => {
                if (json.status == 200)
                    toastr.success(json.message);
                else
                    toastr.error(json.message);
            }));
        })

        $("#espacio").off('change.espacio2').off('change.espacio1').on('change.espacio1', () => {
            $("#sel-bbl").text($("#espacio").find(':selected').text());
            fetch(`api/admin/${$("#espacio").val()}/data`,
            {
                method: "POST",
                headers: headers,
                redirect: "follow"
            })
            .then((response) => {
                if (response.status == 500) {
                    context.setDateAndSetEvent('', '');
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
                    context.setDateAndSetEvent(json.fechaInicio, json.fechaFin);
                    gridInstance.updateConfig({
                        columns: context.columns,
                        data: json.data
                    }).forceRender();
                });
            });
        });

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
        }).render(document.getElementById("table-adm"));

    }

    setDateAndSetEvent(fechaInicio, fechaFin){
        $('#daterange').off();
        $('#daterange').daterangepicker(
            {
                opens: 'left',
                startDate: moment(fechaInicio),
                endDate: moment(fechaFin)
            }, function (start, end, label) {}
        );
        $("#daterange").change((eve) => {
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
}