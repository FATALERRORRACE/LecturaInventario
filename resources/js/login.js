import $ from 'jquery';
import "select2";
localStorage.removeItem('alerts');
$(document).ready(() => {
    $('#espacio').select2();
    $(".radio-lg").change((ev) => {
        fetch(`/api/libraries/get?type=${ev.currentTarget.value}`,
            {
                method: "GET",
                headers: headers,
                redirect: "follow"
            }
        )
        .then((response) => response.json().then(json => {
                json.unshift({
                    id: -1,
                    text: 'Seleccione el Espacio'
                })
                $('#espacio').empty().trigger('change');
                $("#espacio").select2({
                    data: json
                })
                $("#espacio").val(defaultLibrary).trigger('change');
            })
        )
    });
});