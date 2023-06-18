
    export function openModal() {
        const modalTrigger = <HTMLCollection>document.getElementsByClassName('jsModalTrigger');
        const modalWindow = document.getElementById('jsModal');

        const triggerArray = Array.from(modalTrigger);

        triggerArray.forEach((button) => {
            button.addEventListener('click', () => {
                modalWindow.classList ? modalWindow.classList.add('open') : modalWindow.className += ' ' + 'open';
            });
        });
    }

    export function closeModal(){
        const closeButton = <HTMLCollection>document.getElementsByClassName('jsModalClose');
        const closeOverlay = <HTMLCollection>document.getElementsByClassName('jsOverlay');
        const modalWindow = <HTMLDivElement>document.getElementById('jsModal');

        const closeButtonArray = Array.from(closeButton);
        const closeOverlayArray = Array.from(closeOverlay);


        closeButtonArray.forEach((button) => {
            button.addEventListener('click', () => {
                toggleClose(modalWindow)
            });
        });

        closeOverlayArray.forEach((button) => {
            button.addEventListener('click', () => {
                toggleClose(modalWindow)
            });
        });

        window.addEventListener('keydown', function (e) {
            e.key === 'Escape' ? toggleClose(modalWindow) : undefined;
        })
    }

    export function ready(fn: () => void): void {
        document.addEventListener('DOMContentLoaded', fn);
    }

    function toggleClose(modalWindow : HTMLElement) {
        modalWindow.classList ?
            modalWindow.classList.remove('open') :
            modalWindow.className = modalWindow.className.replace(
                new RegExp('(^|\\b)' + 'open'.split(' ')
                    .join('|') + '(\\b|$)', 'gi'), ' '
            );
    }
