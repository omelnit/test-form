const alertPlaceholder = document.getElementById('liveAlertPlaceholder');

const alert = (type, message) => {
    const wrapper = document.createElement('div');
    wrapper.innerHTML = [
        `<div class="alert alert-${type} alert-dismissible" role="alert">`,
        `   <div>${message}</div>`,
        '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
        '</div>'
    ].join('');

    alertPlaceholder.append(wrapper);
}

$( "form" ).on( "submit", function( event ) {
    event.preventDefault();
    $('.btn-close').click();

    if ($('#email').val().includes('@')) {

        if ($('#password').val() && $('#password').val() == $('#submitPassword').val()) {

            const data = $(this).serialize();
            $.ajax({
                url: 'ajax.php',
                type: "POST",
                data: data,
                success: function(response) {
                    result = $.parseJSON(response);
                    alert(result.status, result.message);
                    if (result.status == 'success') $('form').hide();
                },
                error: function(response) {
                    alert('danger', 'Помилка. Дані не відправлені');
                }
            });

        } else {
            alert('danger', 'Пароль обов\'язкове поле і повинен співпадати з підтвердженням');
        }

    } else {
        alert('danger', 'Невірний формат електронної пошти (має містити @)');
    }

});
