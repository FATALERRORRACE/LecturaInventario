
import $ from 'jquery';

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
        toastr.error('Seleccione una clasificación');
        return;
    }
    if (ev.currentTarget && ev.currentTarget.files) {
        var file = ev.currentTarget.files[0];
        var data = new FormData();
        data.append('file', file);
        fetch(`api/inventario/${$("#espacio").val()}/datafile?categoria=${$("input[name=clasificacion]:checked").val()}&inventario=${$("input[name=tipoCarga]:checked").val()}`,
            {
                method: "POST",
                headers: headersMultipart,
                redirect: "follow",
                body: data
            }
        )
        .then((response) => response.json().then( json => {
            subgridInstance.config.data.unshift(json);
            subgridInstance.updateConfig({
                data: subgridInstance.config.data
            }).forceRender();
            localStorage.setItem('filesUploaded', JSON.stringify(subgridInstance.config.data));
            showElements();
        }));
}
    if (ev.dataTransfer && ev.dataTransfer.items) {
        if (ev.dataTransfer.items[0].kind === "file") {
            var file = ev.dataTransfer.items[0].getAsFile();
            var data = new FormData();
            data.append('file', file);
            fetch(`api/inventario/${$("#espacio").val()}/datafile?categoria=${$("input[name=clasificacion]:checked").val()}&inventario=${$("input[name=tipoCarga]:checked").val()}`,
                {
                    method: "POST",
                    headers: headersMultipart,
                    redirect: "follow",
                    body: data
                }
            )
            .then((response) => response.json().then( json => {
                subgridInstance.config.data.unshift(json);
                subgridInstance.updateConfig({
                    data: subgridInstance.config.data
                }).forceRender();
                localStorage.setItem('filesUploaded', JSON.stringify(subgridInstance.config.data));
                showElements();
            }));
        }
    }
}
