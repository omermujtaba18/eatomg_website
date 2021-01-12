


var stripe = Stripe('pk_live_51Hj8OgGmHVnRihcTLXzLkTbDUlThGbTtLkxJW4Ho31RptH6DULwEsSPshn04J4o62EwbHf7ZIxLnpHKK2GQqI4Xf00LsQFmcSb');
var elements = stripe.elements();

var elementStyles = {
    base: {
        color: '#9b9b9b',
        fontFamily: 'GillSans',
        fontSize: '12px',
        fontWeight: 100,
        ':focus': {
            color: '#9b9b9b',
        },

        '::placeholder': {
            color: '#dbdbdb',
        },

        ':focus::placeholder': {
            color: '#dbdbdb',
        },
    },
    invalid: {
        color: '#fff',
        ':focus': {
            color: '#FA755A',
        },
        '::placeholder': {
            color: '#FFCCA5',
        },
    },
};

var elementClasses = {
    focus: 'focus',
    empty: 'empty',
    invalid: 'invalid',
};

var cardNumber = elements.create('cardNumber', {
    style: elementStyles,
    classes: elementClasses,
});
cardNumber.mount('#card-number');

var cardExpiry = elements.create('cardExpiry', {
    style: elementStyles,
    classes: elementClasses,
});
cardExpiry.mount('#card-expiry');

var cardCvc = elements.create('cardCvc', {
    style: elementStyles,
    classes: elementClasses,
});
cardCvc.mount('#card-cvc');

$('#store-form').submit(function (ev) {
    ev.preventDefault();

    var form = document.getElementById("form_checkout");
    var name = document.getElementById("name");
    var email = document.getElementById("email");
    var phone = document.getElementById("phone");

    if (!name.checkValidity() || !email.checkValidity() || !phone.checkValidity()) {
        form.reportValidity();
        form.classList.add('was-validated');
    } else { 
        $('form#form_checkout').submit();
    }
});


$('#card-form').submit(function (ev) {
    ev.preventDefault();

    var form = document.getElementById("form_checkout");
    var name = document.getElementById("name");
    var email = document.getElementById("email");
    var phone = document.getElementById("phone");

    if (!name.checkValidity() || !email.checkValidity() || !phone.checkValidity()) {
        form.reportValidity();
        form.classList.add('was-validated');
    } else {
        $('body').loadingModal();

        stripe.confirmCardPayment($('#checkout').attr("secret"), {
            payment_method: {
                card: cardNumber
            }
        }).then(function (result) {
            if (result.error) {
                $('body').loadingModal('hide');
                setTimeout(() => {
                    $('body').loadingModal('destroy');
                }, 500);
                $('#error').text(result.error.message).show();
            } else {
                if (result.paymentIntent.status === 'succeeded') {
                    $('#card').val(result.paymentIntent.id);
                    $('form#form_checkout').submit();
                }
            }
        });
    }
});

paypal.Buttons({
    style: {
        layout: 'vertical',
        color: 'blue',
        label: 'pay',
    },
    createOrder: function () {
        var form = document.getElementById("form_checkout");
        var name = document.getElementById("name");
        var email = document.getElementById("email");
        var phone = document.getElementById("phone");

        if (!name.checkValidity() || !email.checkValidity() || !phone.checkValidity()) {
            form.reportValidity();
            form.classList.add('was-validated');
        } else {
            $('body').loadingModal();

            return fetch('/checkout/pay-paypal', {
                method: 'post',
                headers: {
                    'content-type': 'application/json'
                }
            }).then(function (res) {
                return res.json();
            }).then(function (data) {
                return data.result.id;
            });
        }
    },
    onApprove: function (data, actions) {
        return actions.order.capture().then(function (details) {
            $('#paypal').val(details.purchase_units[0].payments.captures[0].id);
            $('form#form_checkout').submit();
        });
    },
    onError: function (err) {
        $('body').loadingModal('hide');
        setTimeout(() => {
            $('body').loadingModal('destroy');
        }, 500);
        console.log(err);
    },
    onCancel: function (details) {
        $('body').loadingModal('hide');
        setTimeout(() => {
            $('body').loadingModal('destroy');
        }, 500);
    }

}).render('#paypal-button-container');