import $ from 'jquery';
import { Inventario } from "./inventario";
import { Administracion } from "./administracion";
import { Avances } from "./avances";
import "select2";


var inventarioInstance = new Inventario;
var adminInstance = new Administracion;
var avances = new Avances;

$(document).ready(() => {

  $('#espacio').select2();

  $("#submenu-1").click((eve) => {
    eve.preventDefault();
    inventarioInstance.actionInventario(eve);
  });

  $("#submenu-2").click((eve) => {
    eve.preventDefault();
    avances.actionAvances();
  });

  $("#submenu-4").click((eve) => {
    eve.preventDefault();
    adminInstance.actionAdmin();
  });

  $("#submenu-1").trigger('click');
});

window.dragOverHandler = (ev) => {
  ev.preventDefault();
  $("#drop_zone").addClass('blur-sm');
  $("#messagedraganddrop").show();
}

window.dragLeaveHandler = (ev) => {
  ev.preventDefault();
  $("#drop_zone").removeClass('blur-sm');
  $("#messagedraganddrop").hide();
}


window.dropHandler = (ev) => {
  ev.preventDefault();
  $("#drop_zone").removeClass('blur-sm');
  $("#messagedraganddrop").hide();
  if (ev.dataTransfer.items) {
    if (ev.dataTransfer.items[0].kind === "file") {
      var file = ev.dataTransfer.items[0].getAsFile();
      var data = new FormData();
      data.append('file', file);
      fetch(`api/inventario/${$("#espacio").val()}/datafile`,
        {
          method: "POST",
          headers: headersMultipart,
          redirect: "follow",
          body: data
        }
      )
      .then((response) => response.json().then(json => {
        Object.values(json).forEach(element => {
          console.log(element);
          gridInstance.config.data.unshift(  {
            'Biblioteca_L': element.Biblioteca_L,
            'Biblioteca_O': element.Biblioteca_O,
            'C_Barras': element.C_Barras,
            'Fecha': element.Fecha,
            'Insercion': element.Insercion,
            'InsercionEstado': element.InsercionEstado,
            'Situacion': element.Situacion,
            'Usuario': element.Usuario,
            'Estado': element.Estado,
          });
        });
        gridInstance.updateConfig({
          data: gridInstance.config.data
        }).forceRender();
      }));
    }
  }
}