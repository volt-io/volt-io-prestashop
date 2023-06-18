export function getIdElement(elm: HTMLDivElement) {
    const name = elm.id;
    return name.replace(/\D/g, '');
}
