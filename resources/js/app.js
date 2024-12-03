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

    $("#submenu-1").click((eve)=>{
        eve.preventDefault();
        inventarioInstance.actionInventario(eve);
    });

    $("#submenu-4").click((eve)=>{
        eve.preventDefault();
        adminInstance.actionAdmin();
    });
    $("#submenu-1").trigger('click');
});