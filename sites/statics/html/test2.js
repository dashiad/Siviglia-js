*class MyElement extends Siviglia2.UI.Widget
{
    constructor()
    {
        super('my-element','my-element');
    }
}
customElements.define('my-element', MyElement, {});
class MyElement2 extends Siviglia2.UI.Widget
{
    constructor()
    {
        super('my-element2','my-element2')
    }
}


customElements.define('my-element2', MyElement2, {});
var newInstance=new MyElement2()
/*
class MyElement extends HTMLElement
{
    constructor() {
        super();
        var template = document.getElementById('my-element');
        var templateContent = template.content;

        var shadowRoot = this.attachShadow({mode: 'open'})
            .appendChild(templateContent.cloneNode(true));
    }
}
customElements.define('my-element', MyElement, {});


class MyElement2 extends HTMLElement
{
    constructor() {
        super();
        var template = document.getElementById('my-element2');
        var templateContent = template.content;

        var shadowRoot = this.attachShadow({mode: 'open'})
            .appendChild(templateContent.cloneNode(true));
    }
}

customElements.define('my-element2', MyElement2, {});

*/
