export function createIconInfo(moduleElm: HTMLElement) {
    const icon : HTMLDivElement = document.createElement('div')
    icon.innerHTML = '<a href="#jsModal" id="popup" class="jsModalTrigger">' +
        '<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
        '<path fill-rule="evenodd" clip-rule="evenodd" d="M9.99992 17.2474C14.05 17.2474 17.3333 13.9641 17.3333 9.91404C17.3333 5.86395 14.05 2.58071 9.99992 2.58071C5.94983 2.58071 2.66659 5.86395 2.66659 9.91404C2.66659 13.9641 5.94983 17.2474 9.99992 17.2474ZM9.99992 19.0807C15.0625 19.0807 19.1666 14.9767 19.1666 9.91404C19.1666 4.85143 15.0625 0.747375 9.99992 0.747375C4.93731 0.747375 0.833252 4.85143 0.833252 9.91404C0.833252 14.9767 4.93731 19.0807 9.99992 19.0807Z" fill="#1B63CF"/>\n' +
        '<path d="M10.0142 5.00988C9.2447 5.02496 8.71659 5.53799 8.71659 6.26226C8.71659 7.00162 9.22962 7.52973 9.99915 7.52973C10.7838 7.52973 11.3119 7.00162 11.3119 6.27735C11.3119 5.55308 10.7838 5.02496 10.0142 5.00988Z" fill="#1B63CF"/>\n' +
        '<path d="M9.08325 8.99738H10.9166V14.4974H9.08325V8.99738Z" fill="#1B63CF"/>\n' +
        '</svg>' +
        '</a>'

    if(moduleElm) {
        moduleElm.closest('.payment-option')
            .querySelector('label')
            .appendChild(icon);
    }
}

export function createPartnerIconElements() : HTMLElement {
    const icons = document.createElement('div')
    icons.className = 'volt-icons';

    const icon1 = document.createElement('div')
    icon1.className = 'volt-icons__elm'
    icon1.innerHTML = '<svg width="27" height="14" viewBox="0 0 27 14" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
        '<path d="M19.6423 0H6.55774V13.0846H19.6423V0Z" fill="white"/>\n' +
        '<path d="M26.1846 6.5577L19.6423 0V13.1L26.1846 6.5577Z" fill="#DB0011"/>\n' +
        '<path d="M13.1 6.5577L19.6423 0H6.55774L13.1 6.5577Z" fill="#DB0011"/>\n' +
        '<path d="M0 6.5577L6.5577 13.1V0L0 6.5577Z" fill="#DB0011"/>\n' +
        '<path d="M13.1 6.55771L6.55774 13.1H19.6423L13.1 6.55771Z" fill="#DB0011"/>\n' +
        '</svg>'

    const icon2 = document.createElement('div')
    icon2.className = 'volt-icons__elm'
    icon2.innerHTML = '<svg width="16" height="18" viewBox="0 0 16 18" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
        '<path fill-rule="evenodd" clip-rule="evenodd" d="M7.89373 3.99973C7.67421 5.50623 7.49875 6.77457 7.18262 8.74982C8.03672 8.73173 9.97527 9.06333 10.4019 6.36385C10.7552 4.11805 9.18068 3.91005 7.89373 3.99973Z" fill="url(#paint0_linear_10_134)"/>\n' +
        '<path fill-rule="evenodd" clip-rule="evenodd" d="M15.291 14.8602C15.6233 15.5814 15.2887 16.4933 14.5953 16.8603C14.1973 17.0713 13.7428 17.2673 13.289 17.3773C12.54 17.5518 11.7741 17.6488 11.0042 17.6667C9.77291 17.6667 8.53851 16.9794 8.00672 16.1429C7.86914 16.4315 7.3729 17.0118 6.50334 17.2846C5.95377 17.4579 5.29136 17.6667 3.90856 17.6652C2.66952 17.6644 1.96305 17.1166 1.58972 16.6568C0.994547 15.9258 0.907204 14.9974 1.02315 14.3719L1.02933 14.3417C1.49696 12.0545 2.13619 7.89598 2.43068 5.81221L2.43377 5.79186C2.51416 5.21383 2.59609 4.6358 2.64479 4.05401C2.66875 3.76085 2.68498 3.46618 2.68807 3.17227C2.69117 2.89418 2.66489 2.61685 2.6672 2.33876C2.6703 2.0765 2.71358 1.81122 2.83957 1.57911C3.0444 1.20456 3.41928 0.909136 3.8158 0.780266C4.25097 0.638585 4.70547 0.582063 5.15764 0.530817C5.61994 0.476803 6.08328 0.431577 6.5474 0.395165C7.11328 0.354618 7.68048 0.334006 8.24788 0.333368C9.2187 0.332614 10.1957 0.34844 11.1526 0.540614C12.1273 0.736556 13.112 1.11111 13.8811 1.76751C14.4083 2.21743 14.8264 2.7849 15.141 3.40589C15.4162 3.95302 15.5283 4.52879 15.5947 4.9689C15.713 5.75879 15.6818 6.56313 15.5028 7.34206C15.3613 7.90999 15.1228 8.45062 14.7971 8.94125C14.4307 9.49592 13.9607 9.99934 13.3617 10.4786C13.7265 11.5111 14.6773 13.5278 15.291 14.8602ZM4.38778 2.44351C4.41097 2.82635 4.41406 3.21221 4.39706 3.59656C4.38005 3.98919 4.34527 4.38032 4.30044 4.7707C4.25638 5.15957 4.20305 5.54693 4.14894 5.9343L4.13117 6.0609C3.96112 7.27198 3.78179 8.48154 3.59397 9.68885C3.39609 10.9579 3.17426 12.2203 2.95242 13.4841C2.84962 14.0734 2.61851 14.8286 2.93464 15.3908C3.216 15.8927 3.93793 15.9537 4.44112 15.9032C6.27377 15.7208 6.56518 15.3237 6.56518 15.3237C6.19957 14.9288 6.51725 13.1291 6.88517 10.71H8.18759L9.55107 14.7306C9.55107 14.7306 9.953 15.9763 11.0699 15.916C12.5006 15.8392 13.5534 15.5204 13.7049 15.3433C13.2643 15.1036 12.0044 12.0319 11.318 9.77627C11.6504 9.57882 11.9727 9.36403 12.2765 9.12363C12.6916 8.79354 13.0765 8.41296 13.371 7.96606C13.5828 7.64502 13.7443 7.29157 13.8371 6.91627C13.8858 6.71806 13.9128 6.51082 13.9298 6.30809C13.9607 5.95088 13.9491 5.58914 13.8958 5.23493C13.8448 4.8943 13.7683 4.51522 13.6121 4.20774C13.3223 3.63273 12.9118 3.13082 12.3522 2.80977C11.4077 2.26867 10.3194 2.16543 9.25812 2.1089C8.10716 2.04651 6.95278 2.08333 5.80846 2.21893C5.68015 2.23401 4.38237 2.34554 4.38778 2.44351Z" fill="url(#paint1_linear_10_134)"/>\n' +
        '<defs>\n' +
        '<linearGradient id="paint0_linear_10_134" x1="0.611115" y1="19.2793" x2="16.9252" y2="3.47107" gradientUnits="userSpaceOnUse">\n' +
        '<stop offset="0.113" stop-color="#0D59EC"/>\n' +
        '<stop offset="0.742" stop-color="#008FE1"/>\n' +
        '<stop offset="1" stop-color="#20AFFF"/>\n' +
        '</linearGradient>\n' +
        '<linearGradient id="paint1_linear_10_134" x1="0.611257" y1="19.2793" x2="16.9253" y2="3.47108" gradientUnits="userSpaceOnUse">\n' +
        '<stop offset="0.113" stop-color="#0D59EC"/>\n' +
        '<stop offset="0.742" stop-color="#008FE1"/>\n' +
        '<stop offset="1" stop-color="#20AFFF"/>\n' +
        '</linearGradient>\n' +
        '</defs>\n' +
        '</svg>'

    const icon3 = document.createElement('div')
    icon3.className = 'volt-icons__elm'
    icon3.innerHTML = '<svg width="17" height="15" viewBox="0 0 17 15" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
        '<g clip-path="url(#clip0_10_61)">\n' +
        '<path fill-rule="evenodd" clip-rule="evenodd" d="M16.9511 10.7266C16.9511 13.0884 13.3712 14.999 8.95212 14.999C4.53307 14.999 0.95813 13.0884 0.95813 10.7266C0.95813 9.02276 2.80174 7.56332 5.48445 6.86774C5.47375 7.03067 5.47743 7.19327 5.49547 7.35554C5.50951 7.51517 5.53624 7.67447 5.57567 7.83344C5.61176 7.98912 5.66189 8.14314 5.72605 8.29552C5.78754 8.44393 5.85938 8.58905 5.94159 8.73087L8.44084 13.0458C8.48762 13.127 8.53073 13.2081 8.57016 13.2892C8.6136 13.3743 8.65136 13.4594 8.68344 13.5445C8.71552 13.6296 8.74426 13.7164 8.76966 13.8047C8.79439 13.8931 8.81577 13.9832 8.83382 14.0749L8.94109 13.8948C9.09146 13.6346 9.20976 13.3585 9.29096 13.0716C9.36615 12.7797 9.40324 12.4829 9.40324 12.186C9.40324 11.8832 9.36615 11.5864 9.29096 11.2995C9.20976 11.0076 9.09146 10.7315 8.94109 10.4713L6.93106 7.03793C6.77968 6.7777 6.6674 6.50165 6.59222 6.21471C6.51703 5.92282 6.47392 5.62599 6.47893 5.32817C6.47893 5.03133 6.51703 4.7345 6.59723 4.44756C6.67242 4.16062 6.79071 3.87962 6.94109 3.62533L7.04936 3.44426C7.06673 3.53661 7.08812 3.62698 7.11352 3.71537C7.13892 3.80376 7.16932 3.8905 7.20475 3.97559C7.23683 4.06003 7.27258 4.14479 7.31202 4.22988C7.35145 4.31497 7.39456 4.39644 7.44134 4.47427L8.61327 6.46999L10.4469 9.60059C10.493 9.67843 10.5357 9.7599 10.5752 9.84499C10.6146 9.92678 10.6507 10.0119 10.6834 10.1003C10.7155 10.1847 10.7439 10.2711 10.7687 10.3595C10.7974 10.4485 10.8208 10.5389 10.8388 10.6306L10.9461 10.4505C11.0975 10.1903 11.2098 9.91425 11.291 9.62236C11.3661 9.33542 11.4093 9.03859 11.4093 8.74077C11.4093 8.43898 11.3661 8.14116 11.291 7.85521C11.2098 7.56332 11.0975 7.28727 10.9461 7.02705L8.94109 3.60356C8.79071 3.34334 8.67743 3.06728 8.59723 2.7754C8.52204 2.48945 8.47893 2.19162 8.47893 1.88984C8.47893 1.59202 8.52204 1.29518 8.59723 1.0033C8.67743 0.716359 8.79071 0.440303 8.94109 0.180079L9.04836 0C9.0664 0.0916887 9.08812 0.182058 9.11352 0.271108C9.14226 0.359499 9.17267 0.44591 9.20475 0.530343C9.23683 0.615435 9.27258 0.700528 9.31202 0.78562C9.35145 0.866755 9.39456 0.948219 9.44134 1.03001L11.9466 5.30244C12.0148 5.42249 12.0756 5.54617 12.1291 5.67348C12.1832 5.80079 12.23 5.93008 12.2694 6.06135C12.3088 6.19195 12.3393 6.32454 12.3606 6.4591C12.382 6.59697 12.3964 6.73318 12.4037 6.86774C15.0854 7.56332 16.9401 9.02276 16.9511 10.7266Z" fill="#EC0000"/>\n' +
        '</g>\n' +
        '<defs>\n' +
        '<clipPath id="clip0_10_61">\n' +
        '<rect width="16" height="15" fill="white" transform="translate(0.95813)"/>\n' +
        '</clipPath>\n' +
        '</defs>\n' +
        '</svg>'

    const icon4 = document.createElement('div')
    icon4.className = 'volt-icons__elm'
    icon4.innerHTML = '<svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
        '<g clip-path="url(#clip0_10_160)">\n' +
        '<path fill-rule="evenodd" clip-rule="evenodd" d="M1.04382 0.106659H16.8305V15.8933H1.04382V0.106659ZM3.28382 2.34666V13.76H14.6972V2.34666H3.28382ZM10.5372 3.94666H13.4172L7.44382 12.16H4.56382L10.5372 3.94666Z" fill="#0018A8"/>\n' +
        '</g>\n' +
        '<defs>\n' +
        '<clipPath id="clip0_10_160">\n' +
        '<rect width="16" height="16" fill="white" transform="translate(0.937134)"/>\n' +
        '</clipPath>\n' +
        '</defs>\n' +
        '</svg>'

    icons.appendChild(icon1);
    icons.appendChild(icon2);
    icons.appendChild(icon3);
    icons.appendChild(icon4);

    return icons
}

export function createPartnerIcons(moduleElm: HTMLElement) {

    let icons = createPartnerIconElements();

    if(moduleElm && icons) {
        moduleElm.closest('.payment-option')
            .querySelector('label')
            .after(icons);
    }
}

export function showPartnerIcons(moduleElm: HTMLElement) {
    if (moduleElm) {
        let width: number = moduleElm.closest('.payment-option').clientWidth;
        const icons: HTMLDivElement = document.querySelector<HTMLDivElement>('.volt-icons');

        icons.style.display = 'none';

        if (width > 450) {
            icons.style.display = 'flex';
        }
    }
}
