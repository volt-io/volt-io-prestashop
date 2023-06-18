// @ts-ignore
const configSettings = voltSettings ?? [];

let mode = "sandbox";
if(configSettings.env !== null && configSettings.env === '1') {
    mode = "production"
}

// @ts-ignore
const volt = new window.Volt({ mode })

const headers: HeadersInit = {
    'Content-Type': 'application/x-www-form-urlencoded'
}

interface CheckoutInterface {
    action: string;
    ajax: boolean;
}

interface PaymentInterface {
    action: string;
    paymentId: string;
    ajax: boolean;
}

interface WidgetEventInterface {
    complete: boolean;
}


export const checkPayment = () => {
    // @ts-ignore
    const payment = configSettings.ajax_url ?? null;
    if (payment) {
        collapsePayment(payment);
    }
}

const collapsePayment = (payment: string) => {

    const url = payment;
    const paymentPlaceholder = document.querySelector<HTMLDivElement>('#volt-payment-component');
    const paymentLoading = document.querySelector<HTMLDivElement>('.volt-loading');
    const paymentData: CheckoutInterface = {
        action: 'initPayment',
        ajax: true
    };


    // Reset placeholder
    paymentLoading.style.display = 'block';
    paymentPlaceholder.innerHTML = "";


    const createPaymentWidget = paymentFetch(url, paymentData).then(response => {
        // @ts-ignore
        const responseBody = response;

        if (responseBody.exception?.message) {
            alert(responseBody.exception.message)
        }

        const paymentContainer = volt.payment({
            payment: responseBody,
            // language: config.language,
            // country: config.country,
            language: 'pl',
            country: configSettings.country
        })

        const paymentComponent = paymentContainer.createPayment({
            displayPayButton: false
        });

        if(document.querySelector('#volt-payment-component').childNodes.length === 0) {
            paymentLoading.style.display = 'none';
            paymentComponent.mount("#volt-payment-component");
        }
        return paymentComponent;
    });

    createPaymentWidget.then((paymentComponent) => {
        // if the checkout
        // const payButton = <HTMLButtonElement>document.getElementById("confirm_order")
        const payButton = <HTMLButtonElement>document.querySelector("#payment-confirmation button")

        console.warn(paymentComponent);

        if (paymentComponent.parent.options.payment.id) {
            payButton.onclick = function (e) {
                e.preventDefault();

                console.log(paymentComponent);


                const url2 = configSettings.ajax_url;
                const PaymentComponentPayload: PaymentInterface = {
                    action: 'createTransaction',
                    paymentId: paymentComponent.parent.options.payment.id,
                    ajax: true
                };

                paymentFetch(url2, PaymentComponentPayload).then(response => {
                    console.error(response);
                });

                paymentComponent.checkout()

            }

            paymentComponent.parent.on('change', function (event: WidgetEventInterface) {
                payButton.disabled = !event.complete
            });

        } else {
            payButton.disabled
            alert('No support this currency')
        }

    })
}


function setPaymenyId(id:string) {
    localStorage.setItem('volt_pid', id);
}


function initTheCheckout()
{
    // @ts-ignore
    if (typeof checkoutPaymentParser != "undefined") {
        // @ts-ignore
        checkoutPaymentParser.volt = {

            container: function (element: any) {

                console.log('run element')
                console.log(element)

                setTimeout(function () {

                    console.log(element)
                    // console.log(container);

                    // let formId = localStorage.getItem('bm-form-id');

                    // if (container && (container.id === element[0].id)) {
                    //
                    //     const gatewayId = document.querySelector(
                    //         '[data-bm-gateway-id="' + localStorage.getItem('bm-gateway') + '"]'
                    //     );
                    //
                    //     container.appendChild(createLabel);
                    //
                    //
                    //
                    //     document.querySelector(
                    //         '[data-show-bm-gateway-id="' + localStorage.getItem('bm-gateway') + '"]'
                    //     ).style.display = 'block';
                    //
                    // }
                }, 40);

            },

            init_once: function (content: any, triggerElementName: any) {
                // checkPayment();
            },



            additionalInformation: function (element: any) {
               console.log('init add')
            },

            confirm: function () {
                console.log('init confirm')
            },


            all_hooks_content: function (content: any) {
                // empty
            },

            form: function (element: any) {
                // empty
            },
        }
    }
}






// export function getPaymentIdToInput()
// {
//     const id = localStorage.getItem('volt_pid');
//     if (id !== null) {
//         let inputPaymentId = document.getElementsByName("volt_payment_id")[0]
//         inputPaymentId.value = id;
//     }
// }



function paymentFetch(url: string, config: {}) {
    interface Resp {
        status: boolean,
        exception?: {
            message: string
        }
    }

    const params: RequestInit = {
        method: 'POST',
        headers,
        body: new URLSearchParams(config)
    };

    return fetch(url, params)

        .then((response) => response.json())
        .catch(e => {
            console.log(configSettings.errorMsg)
        })
        .then((data) => data as Resp);
}
