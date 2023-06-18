describe('backend', () => {
    it('passes', () => {
        cy.viewport(1920, 1080)
        cy.visit('https://ms.presta.cool.vot.pl/audio/hfwtr42frwyfgs7w/')
        const user = `staging@coolbrand.pl`
        const pass = `%kGV1(qfDdW5`
        cy.get('#login_form [name="email"]').eq(0).type(user)
        cy.get('#login_form [name="passwd"]').eq(0).type(pass)
        cy.get('#login_form [type="submit"]').eq(0).click()

        cy.get('#subtab-AdminParentModulesSf').eq(0).click()
        cy.get('#subtab-AdminModulesSf').eq(0).click()
        cy.get('.module-item[data-name="Pay by Bank"]').find('.btn-primary-reverse').click({force:true})
        cy.get('#configuration_form_submit_btn').click();
        cy.get('#growls').contains('Configuration saved successfully')
    })
})


