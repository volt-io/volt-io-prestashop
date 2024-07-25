import {checkPayment} from "./_partials/payment";
import {getIdElement} from "./_partials/helpers";
import {openModal, closeModal, ready} from "./_partials/modal";
import {createIconInfo, createPartnerIcons, showPartnerIcons} from "./_partials/icons";
// import {PatchData, patchData} from "./_partials/updatePayment";


let moduleName: HTMLElement = document.querySelector('[data-module-name="volt"]');

// Create info icon
createIconInfo(moduleName)
ready(openModal)
ready(closeModal)

// Create partner icons
createPartnerIcons(moduleName)
showPartnerIcons(moduleName);

addEventListener("resize", (event) => {
    showPartnerIcons(moduleName)
});

export function radioPayments() {


    // console.log(document.querySelector('[data-module-name="volt"]').checked);



    // console.log(isVoltModuleChecked());


    if (isVoltModuleChecked()) {
        setTimeout(() => {
            paymentOption(document.querySelector('[data-module-name="volt"]'))
            const additionalInformation = document.querySelector<HTMLDivElement>('#payment-option-1-additional-information');
            if (additionalInformation) {
                additionalInformation.style.display = 'block';
            }
        },250)
    }

    let paymentInput = document.querySelector<HTMLInputElement>('input[data-module-name="volt"]')

    if (paymentInput) {
        paymentInput.addEventListener('click', (e: Event) => {
            const target = e.target as HTMLInputElement
            console.log('click payment element')

            if (target.checked === true) {
                paymentOption(paymentInput);
            }
        })
    }
}

function isVoltModuleChecked() {
    const voltElement = document.querySelector('[data-module-name="volt"]');

    if (voltElement && voltElement instanceof HTMLInputElement) {
        return voltElement.checked;
    } else {
        return false;
    }
}

export function paymentOption(formRadioId: HTMLInputElement) {
    if(formRadioId) {
        checkPayment()
    }
}

// function checkedIfOnePaymentMethod(paymentOptions: Array<HTMLInputElement>): boolean {
//     return paymentOptions && paymentOptions.length === 1
// }

resetForm(getVoltPaymentElement());


/// Testing onePage checkout
// setTimeout(() => {
    radioPayments();
// },4000)


function resetForm(arr: HTMLElement[])
{
    arr.forEach(( item: HTMLDivElement, i: number ) => {
        if (0 === i) {
            const form = document.querySelector('#pay-with-payment-option-'+ getIdElement(item) +'-form form')
            form.remove();
        }
    });
}


function getVoltPaymentElement()
{
    const allElements = document.querySelectorAll<HTMLDivElement>('.js-additional-information.additional-information');
    const array = Array.from(allElements);
    const classElm = 'volt-payment';

    return array.filter(element => Array.from(element.children)
        .some(item => item.classList.contains(classElm)));
}

// @ts-ignore
if (typeof prestashop !== 'undefined') {

    interface Window {
        previousCartQuantity: number;
        previousTotalPrice: number;
    }

    // @ts-ignore
    prestashop.on('updateCart', function (event: any) {
        if (event.resp && event.resp.cart) {
            // @ts-ignore
            const configSettings = voltSettings ?? [];
            const url: string = configSettings.ajax_url + '?action=updateTransaction' ;

            getUpdate(url).then(response => response.json()) // Konwersja odpowiedzi do formatu JSON
                .then(data => console.log(data)) // Wyświetlenie odpowiedzi z serwera
                .catch(error => console.error('Błąd:', error)); // Obsługa błędów

            localStorage.setItem('volt_update', '1');

        }
    });
}

function getUpdate(url: string) {
    return fetch(url)
}
