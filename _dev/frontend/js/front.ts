import {checkPayment} from "./_partials/payment";
import {getIdElement} from "./_partials/helpers";
import {openModal, closeModal, ready} from "./_partials/modal";
import {createIconInfo, createPartnerIcons, showPartnerIcons} from "./_partials/icons";


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

    // if (checkedIfOnePaymentMethod) {
    //     if(paymentOptions) {
    //         console.log('show payment')
    //         setTimeout(() => {
    //             paymentOption(paymentOptions[0])
    //             const additionalInformation = document.querySelector<HTMLDivElement>('#payment-option-1-additional-information');
    //             if (additionalInformation) {
    //                 additionalInformation.style.display = 'block';
    //             }
    //         },550)
    //     }
    // }

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


function paymentOption(formRadioId: HTMLInputElement) {
    if(formRadioId) {
        checkPayment()
    }
}

function checkedIfOnePaymentMethod(paymentOptions: Array<HTMLInputElement>): boolean {
    return paymentOptions && paymentOptions.length === 1
}

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
