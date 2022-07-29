/* Poner el título del grupo de tests */
document.getElementsByTagName('title')[0].innerHTML = 'Batch test template'

/* Crea tantos tests como desees con la siguiente estructura */
runTest("Título del test",
  "Descripción del test",
  '<style>.simpleClass {font-weight:bold;color:green}</style>' +
  '<div data-sivWidget="widget-name" data-widgetCode="ContextName.ClassName">' +
  '<span data-sivValue="Hello world!"></span>' +
  '</div>',
  '<div data-sivView="widget-name"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'ContextName',
      classes: {
        ClassName: {
          inherits: "Siviglia.UI.Expando.View",
          methods: {
            preInitialize: function (params) {},
            // initialize: function (params) {}
          }
        }
      }
    })
  }
)
checkTests()