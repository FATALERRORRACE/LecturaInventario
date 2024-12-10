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
    $("#sub-content").show()
    inventarioInstance.actionInventario(eve);
  });

  $("#submenu-2").click((eve) => {
    eve.preventDefault();
    $("#sub-content").hide()
    avances.actionAvances();
  });

  $("#submenu-4").click((eve) => {
    eve.preventDefault();
    $("#sub-content").hide()
    adminInstance.actionAdmin();
  });

  $("#submenu-1").trigger('click');
});
