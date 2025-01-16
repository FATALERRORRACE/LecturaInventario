
import $ from 'jquery';
import toastr from "toastr";
import 'jquery-ui/ui/effects/effect-shake';

function hideElements() {
    $("#loader-adm").show();
    $("#codbar").hide();
    $("#invent").hide();
    $(".txt-cdbab").hide();
    $("#loadfile").hide();
}

function showElements() {
    $("#loader-adm").hide();
    $("#codbar").show();
    $(".txt-cdbab").show();
    $("#invent").show();
    $("#loadfile").show();
}

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
    hideElements();
    if (
        !$($(".clasificacion")[0]).prop('checked') &&
        !$($(".clasificacion")[1]).prop('checked') &&
        !$($(".clasificacion")[2]).prop('checked')
    ) {
        $("#container-xyz").effect('shake');
        showElements();
        toastr.error('Seleccione una clasificación');
        return;
    }
    var data = new FormData();
    var file;
    if (ev.currentTarget && ev.currentTarget.files)
        file = ev.currentTarget.files[0];

    if (ev.dataTransfer && ev.dataTransfer.items && ev.dataTransfer.items[0].kind === "file")
        file = ev.dataTransfer.items[0].getAsFile();

    if (!file){
        toastr.error('Archivo no reconocido');
        return;
    }
    
    data.append('file', file);
    fetch(`api/inventario/${$("#espacio").val()}/datafile?categoria=${$("input[name=clasificacion]:checked").val()}&inventario=${$("input[name=tipoCarga]:checked").val()}`,
        {
            method: "POST",
            headers: headersMultipart,
            redirect: "follow",
            body: data
        }
    )
    .then((response) => response.json().then(json => {
        subgridInstance.config.data.unshift(json);
        subgridInstance.updateConfig({
            data: subgridInstance.config.data
        }).forceRender();
        localStorage.setItem('filesUploaded', JSON.stringify(subgridInstance.config.data));
        showElements();
    }));
}
