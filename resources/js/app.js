import $ from 'jquery';
import { Grid, html } from "gridjs";
import { esES } from "gridjs/l10n";
import { Inventario } from "./inventario";
import { Administracion } from "./administracion";
import "select2";

var inventarioInstance = new Inventario;
var adminInstance = new Administracion;

$(document).ready(() => {

    $('#espacio').select2();

    $("#submenu-1").click((eve) => {
        eve.preventDefault();
        inventarioInstance.actionInventario(eve);
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

      console.log(ev.dataTransfer.items);
      var data = new FormData()
      data.append('file', input.files[0])
      data.append('user', 'hubot')
      fetch(`api/inventario`,
        {
            method: "GET",
            headers: headers,
            redirect: "follow",
            body: data
        }
    )
    .then((response) => response.text().then(text => {
      
    }));
      [...ev.dataTransfer.items].forEach((item, i) => {
        if (item.kind === "file") {
          const file = item.getAsFile();
          console.log(`… file[${i}].name = ${file.name}`);
        }
      });
    } else {
      // Use DataTransfer interface to access the file(s)
      [...ev.dataTransfer.files].forEach((file, i) => {
        console.log(`… file[${i}].name = ${file.name}`);
      });
    }
  }