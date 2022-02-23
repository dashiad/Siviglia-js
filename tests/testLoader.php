<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test loader</title>
    <?php include 'javascriptDependencies.php'; ?>
    <?php include 'styleDependencies.php'; ?>
</head>
<body style="background-color:#EEE; background-image:none;">
<?php include_once("../jQuery/JqxWidgets.html"); ?>
<?php include_once("../jQuery/JqxLists.html"); ?>
<?php include_once("../jQuery/Visual.html"); ?>

<script src="testScripts.js"></script>

<script>
  runTest("ComboBox: ComboBox dependientes",
    "Una fuente de un input puede tener varios conjuntos de opciones, seleccionándose uno u otro según una clave externa, indicada en \"SOURCE/PATH/\"<br>" +
    "Esta clave externa tiene que tratarse de otro campo con su propia fuente. De esta forma las fuentes quedan relacionadas.",
    '<div data-sivWidget="dependant-comboBox" data-widgetParams="" data-widgetCode="Test.DependantComboBox">'+
    '   <div class="label">ComboBox origen</div>'+
    '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
    '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"originComboBox"}\'>' +
    '   </div>'+
    '   <div class="label">ComboBox dependiente de comboBox origen</div>'+
    '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
    '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"firstDependantComboBox"}\'>' +
    '   </div>'+
    '   <div class="label">ComboBox dependiente de comboBox anterior</div>'+
    '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
    '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"secondDependantComboBox"}\'>' +
    '   </div>'+
    '</div>',
    '<div data-sivView="dependant-comboBox"></div>',
    function(){
      Siviglia.Utils.buildClass({
        context:'Test',
        classes:{
          DependantComboBox: {
            inherits: "Siviglia.inputs.jqwidgets.Form",
            methods: {
              preInitialize: function (params) {
                // this.factory = Siviglia.types.TypeFactory;
                // this.self = this;
                // this.typeCol = [];
                this.formDefinition = new Siviglia.model.BaseTypedObject({
                  "FIELDS": {
                    originComboBox: {
                      "LABEL":"Combo fuente origen",
                      "TYPE": "String",
                      "SOURCE": {
                        "TYPE": "Array",
                        "LABEL": "labelKey",
                        "VALUE": "valueKey",
                        "DATA": [
                          {"valueKey": "one", "labelKey": "Opción - uno"},
                          {"valueKey": "two", "labelKey": "Opción - dos"}
                        ],
                      }
                    },
                    firstDependantComboBox: {
                      "LABEL":"Combo dependiente de origen",
                      "TYPE": "Integer",
                      "SOURCE": {
                        "TYPE": "Array",
                        "LABEL": "message",
                        "VALUE": 'val',
                        "PATH": "/{%#../originComboBox%}",
                        "DATA": {
                          "one": [
                            {'val': 11, "message": "Opcion uno - 1"},
                            {'val': 12, "message": "Opcion uno - 2"},
                          ],
                          "two": [
                            {'val': 21, "message": "Opcion dos - 1"},
                            {'val': 22, "message": "Opcion dos - 2"},
                          ]
                        },
                      }
                    },
                    secondDependantComboBox: {
                      "LABEL":"Combo dependiente segundo nivel",
                      "TYPE": "Integer",
                      "SOURCE": {
                        "TYPE": "Array",
                        "LABEL": "message",
                        "VALUE": "comboValue",
                        "PATH": "/{%#../firstDependantComboBox%}",
                        "DATA": {
                          11: [
                            {"comboValue": 111, "message": "Opción uno.1 - 1"},
                            {"comboValue": 112, "message": "Opción uno.1 - 2"}
                          ],
                          12: [
                            {"comboValue": 121, "message": "Opción uno.2 - 1"},
                            {"comboValue": 122, "message": "Opción uno.2 - 2"}
                          ],
                          21: [
                            {"comboValue": 211, "message": "Opción dos.1 - 1"},
                            {"comboValue": 212, "message": "Opción dos.1 - 2"}
                          ],
                          22: [
                            {"comboValue": 221, "message": "Opción dos.2 - 1"},
                            {"comboValue": 222, "message": "Opción dos.2 - 2"}
                          ]
                        },
                      }
                    },
                  }
                });

                return this.Form$preInitialize({bto:this.formDefinition});
              },
              initialize: function (params) {},
              // show: function () {},
            }
          }
        }
      })
    }
  )
</script>
<script>
  checkTests();
</script>


</body>
</html>
