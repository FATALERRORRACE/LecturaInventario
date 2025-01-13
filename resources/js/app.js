import $ from 'jquery';
import { Inventario } from "./inventario";
import { Administracion } from "./administracion";
import { Avances } from "./avances";
import "select2";
import "moment";
import "daterangepicker";
import toastr from "toastr";

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
    $("#modal-menu-2").attr('data-open') == 1 ?
      $("#modal-menu-2").hide(200) :
      $("#modal-menu-2").show(200);
    $("#modal-menu-2").attr('data-open', $("#modal-menu-2").attr('data-open') == 1 ? 0 : 1);
    return
  });

  $("#menu-item-2").click((eve) => {
    eve.preventDefault();
    $("#sub-content").hide();
    $("#modal-menu-2").hide(200);
    $("#modal-menu-2").attr('data-open', 0);
    avances.actionAvances();
  });

  $("#menu-item-5").click((eve) => {
    eve.preventDefault();
    $("#sub-content").hide();
    $("#modal-menu-2").hide(200);
    $("#modal-menu-2").attr('data-open', 0);
    avances.openDialogTree();
  });

  $("#submenu-4").click((eve) => {
    eve.preventDefault();
    $("#sub-content").hide()
    adminInstance.actionAdmin();
  });

  $("#submenu-1").trigger('click');
});
