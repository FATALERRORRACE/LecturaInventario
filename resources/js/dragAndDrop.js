
import $ from 'jquery';

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
    $("#codbar").hide();
    $("#invent").hide();
    $(".txt-cdbab").hide();
    $("#loadfile").hide();
    $("#loader-adm").show();
    console.log(ev);
    console.log(ev.currentTarget.files);
    if (ev.currentTarget && ev.currentTarget.files) {
        
        var file = ev.currentTarget.files[0];
        var data = new FormData();
        data.append('file', file);
        fetch(`api/inventario/${$("#espacio").val()}/datafile?categoria=${$("input[name=clasificacion]:checked").val()}`,
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
            $("#loader-adm").hide();
            $("#codbar").show();
            $(".txt-cdbab").show();
            $("#invent").show();
            $("#loadfile").show();
        }));
}
    if (ev.dataTransfer && ev.dataTransfer.items) {
        if (ev.dataTransfer.items[0].kind === "file") {
            var file = ev.dataTransfer.items[0].getAsFile();
            var data = new FormData();
            data.append('file', file);
            fetch(`api/inventario/${$("#espacio").val()}/datafile?categoria=${$("input[name=clasificacion]:checked").val()}`,
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
                $("#loader-adm").hide();
                $("#codbar").show();
                $(".txt-cdbab").show();
                $("#invent").show();
                $("#loadfile").show();
            }));
        }
    }
}