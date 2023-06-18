describe('full success create payment', () => {
    it('passes', () => {
        cy.viewport(1920, 1080)
        cy.visit('https://ms.presta.cool.vot.pl/audio/pl/strona-glowna/14-hummingbird-vector-graphics.html')
        cy.get('.add-to-cart').eq(0).click()
        cy.get('.cart-content-btn .btn-primary').click()
        cy.get('.cart-detailed-actions .btn-primary').click()
        cy.get('[data-link-action="show-login-form"]').click()

        const user = `nordstylepl@gmail.com`
        const pass = `%kGV1(qfDdW5`

        cy.get('#login-form [name="email"]').eq(0).type(user)
        cy.get('#login-form [name="password"]').eq(0).type(pass)
        cy.get('#login-form [type="submit"]').eq(0).click()
        cy.visit('https://ms.presta.cool.vot.pl/audio/pl/zam%C3%B3wienie')

        cy.get('.js-address-form .continue').eq(0).click()
        cy.visit('https://ms.presta.cool.vot.pl/audio/pl/zam%C3%B3wienie')

        cy.contains('Pay by Bank').click({force:true})
        cy.get('input[name="conditions_to_approve[terms-and-conditions]"]').click()
        cy.wait(5000).get('#volt-payment-component > iframe').should('be.visible')

        cy.enter('#volt-payment-component > iframe').then(getBody  => {

            getBody().should('be.visible')
                .find('#country-selection').clear({force: true}).eq(0)
                .type('United Kingdom');


            getBody().should('be.visible').find('#GB').click()
            getBody().should('be.visible').contains('Volt Mock Bank').click()

            cy.get('#payment-confirmation button').click()

        })
    })
})
