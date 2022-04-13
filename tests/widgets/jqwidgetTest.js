runTest("Input: definición",
  "Se trata de containers que contienen todo lo relacionado con un campo de entrada de información del usuario en el UI.<br>" +
  "Para generar un input se emplea una vista del widget <b>StdInputContainer</b>, al cual se asocia un campo del formulario al que pertenece el input.<br>" +
  "La asignación se realiza dando al parámetro \"<b>key</b>\" el nombre del campo según aparece en la definición del formulario.<br>" +
  "Como clase asociada al widget, se usa una clase derivada de Form, que a su vez deriva de Container y este de BaseInput.<br>",
  '<div data-sivWidget = "input-definition" data-widgetParams="" data-widgetCode="Test.InputDefinition">' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{\n' +
  '                           "key":"fieldName",\n' +
  '                           "parent":"*type",\n' +
  '                           "form":"*form",\n' +
  '                           "controller":"*self"\n' +
  '                       }\'\n' +
  '   >' +
  '   </div>' +
  '</div>',
  '<div data-sivView="input-definition"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        InputDefinition: {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              // this.factory = Siviglia.types.TypeFactory;
              // this.self = this;
              // this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                FIELDS: {
                  fieldName: {
                    TYPE: "String",
                    LABEL: "Etiqueta del campo",
                    HELP: "La ayuda"
                  },
                }
              });
              this.formDefinition.fieldName = 'valor predefinido';
              return this.Form$preInitialize({bto: this.formDefinition});
            },
            // No es necesario que el método de clase "initialice" esté declarado.
            // Puede hacerse y dejarse como función vacía si se desea.
            // initialize: function (params) {},
          }
        }
      }
    })
  }
)
runTest("Input: campos de tipos básicos",
  "Se prueban los tipos de campos básicos sobre input simples.<br>",
  '<div data-sivWidget = "basic-field-types" data-widgetParams="" data-widgetCode="Test.BasicFieldTypes">' +
  '   <div class="label">Input con campo String</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"stringType"}\'></div>' +
  '   <div class="label">Input con campo Enum</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"enumType"}\'></div>' +
  '   <div class="label">Input con campo Integer</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"integerType"}\'></div>' +
  '   <div class="label">Input con campo Decimal</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"decimalType"}\'></div>' +
  '   <div class="label">Input con campo Text</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"textType"}\'></div>' +
  '   <div class="label">Input con campo Boolean</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"booleanType"}\'></div>' +
  '</div>',
  '<div data-sivView="basic-field-types"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        BasicFieldTypes: {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                FIELDS: {
                  stringType: {
                    TYPE: "String",
                    LABEL: "String type",
                    MINLENGTH: 3,
                    HELP: "La ayuda"
                  },
                  enumType: {
                    TYPE: "Enum",
                    LABEL: "Enum type",
                    VALUES: ["Uno", "Dos", "Tres"],
                  },
                  integerType: {
                    TYPE: "Integer",
                    LABEL: "Integer type",
                    MAX: 1000
                  },
                  decimalType: {
                    TYPE: "Decimal",
                    LABEL: "Decimal type",
                    NINTEGERS: 5,
                    NDECIMALS: 2
                  },
                  textType: {
                    TYPE: "Text",
                    LABEL: "text type",
                  },
                  booleanType: {
                    TYPE: "Boolean",
                    LABEL: "Boolean type",
                  }
                }
              });
              this.formDefinition.stringType = "abcde";
              this.formDefinition.enumType = "Dos";
              this.formDefinition.integerType = 10;
              this.formDefinition.decimalType = 8.3;
              this.formDefinition.textType = "Esta es una prueba";
              this.formDefinition.booleanType = true;
              return this.Form$preInitialize({bto: this.formDefinition});
            },
          }
        }
      }
    })
  }
)
runTest("Input: campo de tipo Container",
  "Un campo container genera una vista con los valores de sus campos internos agrupados.",
  '<div data-sivWidget="container-field" data-widgetParams="" data-widgetCode="Test.ContainerField">' +
  '   <div class="label">Input con campo Container:</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"simpleContainer"}\'>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="container-field"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        ContainerField: {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  simpleContainer: {
                    "LABEL": "Campo container",
                    "TYPE": "Container",
                    "FIELDS": {
                      "field1": {
                        "LABEL": "Campo 1 del container",
                        "TYPE": "String"
                      },
                      "field2": {
                        "LABEL": "Campo 2 del container",
                        "TYPE": "Integer"
                      }
                    }
                  }
                }
              });
              this.formDefinition.simpleContainer = {"field1": "AAA", "field2": 555};

              return this.Form$preInitialize({bto: this.formDefinition});
            },
          }
        }
      }
    })
  }
)
runTest("Estados en campos de tipo Container",
  "Se pueden definir estado para campos simples que compongan un campo de tipo Container.<br>" +
  "El tipo <b>State</b> deriva de Enum, siendo el array de valores posibles los estados para los campos del container donde se encuentre.",
  '<div data-sivWidget="field-states" data-widgetParams="" data-widgetCode="Test.FieldStates">' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"key":"simpleContainer", "controller":"*self","parent":"*type", "form":"*form"}\'>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="field-states"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        "FieldStates": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              // this.factory = Siviglia.types.TypeFactory;
              // this.self = this;
              // this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  simpleContainer: {
                    "TYPE": "Container",
                    "LABEL": "Campo Container con estados",
                    "FIELDS": {
                      "one": {"TYPE": "String", "LABEL": "Campo uno"},
                      "two": {"TYPE": "String", "LABEL": "Campo dos"},
                      "three": {"TYPE": "String", "LABEL": "Campo tres"},
                      "state": {
                        "TYPE": "State",
                        "LABEL": "Estado",
                        "VALUES": ["state1", "state2", "state3"],
                        "DEFAULT": "state1",
                      }
                    },
                    'STATES': {
                      'FIELD': 'state',
                      'STATES': {
                        'state1': {
                          'FIELDS': {'EDITABLE': ['one', 'two']}
                        },
                        'state2': {
                          'ALLOW_FROM': ["state1"],
                          'FIELDS': {'EDITABLE': ['two', 'three']}
                        },
                        'state3': {
                          'ALLOW_FROM': ["state2"],
                          'FINAL': true,
                          'FIELDS': {'REQUIRED': ['three']}
                        }
                      },
                    }
                  },
                }
              });
              return this.Form$preInitialize({bto: this.formDefinition});
            },
          }
        }
      }
    })
  }
)
runTest("Valor por defecto de los campos de un campo container",
  "Si no se da valor a ninguno de los campos de un container, estos campos permanecen sin valor aunque tengan la propiedad <b>DEFAULT</b>.<br>" +
  "En el momento en el que se le da valor a cualquiera de los campos del container, los campos con la propiedad DEFAULT toman el valor indicado en esta propiedad, salvo que sea el campo modificado.<br>" +
  "Pulsando el botón \"Enviar\" se puede ver por consola cómo el container <i>defaultNotModified</i> no tiene valor en sus campos y como al dar valor al campo no default del container <i>defaultModified</i> tanto este como el campo default tienen valor",
  '<div data-sivWidget="default-values" data-widgetParams="" data-widgetCode="Test.DefaultValues">' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"simpleContainer"}\'' +
  '   >' +
  '   </div>' +
  '   <input type="button" data-sivEvent="click" data-sivCallback="doSubmit" value="Enviar">' +
  '</div>',
  '<div data-sivView="default-values"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        DefaultValues: {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              // this.factory = Siviglia.types.TypeFactory;
              // this.self = this;
              // this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                FIELDS: {
                  simpleContainer: {
                    "TYPE": "Container",
                    "LABEL": "Container con valores por defecto",
                    "FIELDS": {
                      defaultNotModified: {
                        "TYPE": "Container",
                        "LABEL": "Container sin modificar valor",
                        "FIELDS": {
                          defaultField: {
                            "TYPE": "String",
                            "LABEL": "Campo default",
                            "DEFAULT": "ZZZ",
                          },
                          noDefaultField: {
                            "TYPE": "String",
                            "LABEL": "Campo no default",
                          }
                        }
                      },
                      defaultModified: {
                        "TYPE": "Container",
                        "LABEL": "Container con valor modificado",
                        "FIELDS": {
                          defaultField: {
                            "TYPE": "String",
                            "LABEL": "Campo default",
                            "DEFAULT": "ZZZ",
                          },
                          noDefaultField: {
                            "TYPE": "String",
                            "LABEL": "Campo no default",
                          }
                        }
                      },
                    }
                  }
                },
                "INPUTPARAMS": {
                  "/simpleContainer": {
                    "INPUT": "ByFieldContainer",
                  }
                }
              });
              this.formDefinition.setValue({simpleContainer: {defaultModified: {noDefaultField: "AAA"}}})

              return this.Form$preInitialize({bto: this.formDefinition});
            },
            doSubmit: function () {
              this.formDefinition.save();
              console.dir(this.formDefinition.getPlainValue());
            }
          }
        }
      }
    })
  }
)
runTest("Input: uso de INPUTPARAMS en campo de tipo Container",
  "Mismo test anterior, pero utilizando INPUTPARAMS para sobreescribir el widget utilizado para el input Container<br>" +
  "Un formulario puede parametrizar los inputs por defecto, o parametrizarlos, usando el campo INPUTPARAMS, con el path a los inputs que se quieren parametrizar",
  '<div data-sivWidget="container-inputParams" data-widgetParams="" data-widgetCode="Test.ContainerInputParams">' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"simpleContainer"}\'>' +
  '   </div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"gridContainer"}\'>' +
  '   </div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"byFieldContainer"}\'>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="container-inputParams"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        ContainerInputParams: {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  simpleContainer: {
                    "LABEL": "Visualización normal",
                    "TYPE": "Container",
                    "FIELDS": {
                      "field1": {
                        "LABEL": "Campo 1 del container",
                        "TYPE": "String"
                      },
                      "field2": {
                        "LABEL": "Campo 2 del container",
                        "TYPE": "Integer"
                      }
                    }
                  },
                  gridContainer: {
                    "LABEL": "Visualización tipo grid",
                    "TYPE": "Container",
                    "FIELDS": {
                      "field1": {
                        "LABEL": "Campo 1 del container",
                        "TYPE": "String"
                      },
                      "field2": {
                        "LABEL": "Campo 2 del container",
                        "TYPE": "Integer"
                      }
                    }
                  },
                  byFieldContainer: {
                    "LABEL": "Visualización tipo 'por campos'",
                    "TYPE": "Container",
                    "FIELDS": {
                      "field1": {
                        "LABEL": "Campo 1 del container",
                        "TYPE": "String"
                      },
                      "field2": {
                        "LABEL": "Campo 2 del container",
                        "TYPE": "Integer"
                      }
                    }
                  },
                },
                "INPUTPARAMS": {
                  '/gridContainer': {
                    INPUT: 'GridContainer',
                    JQXPARAMS: {width: 700, height: 500},
                  },
                  '/byFieldContainer': {
                    INPUT: 'ByFieldContainer',
                  },
                },
              });
              this.formDefinition.simpleContainer = {"field1": "AAA", "field2": 555};
              this.formDefinition.gridContainer = {"field1": "AAA", "field2": 555};
              this.formDefinition.byFieldContainer = {"field1": "AAA", "field2": 555};

              return this.Form$preInitialize({bto: this.formDefinition});
            },
            initialize: function (params) {
            },
          }
        }
      }
    })
  }
)
runTest("Input: campo de tipo Array",
  "Un campo de tipo array genera una vista con una lista ordenada con los valores",
  '<div data-sivWidget="array-field" data-widgetParams="" data-widgetCode="Test.ArrayField">' +
  '   <div class="label">Input con campo Array:</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"simpleArray"}\'>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="array-field"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        ArrayField: {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  simpleArray: {
                    "LABEL": "Campo array",
                    "TYPE": "Array",
                    "ELEMENTS": {
                      "LABEL": "Elemento del array",
                      "TYPE": "String"
                    }
                  }
                }
              });
              this.formDefinition.simpleArray = ["AAA", "ZZZ"];

              return this.Form$preInitialize({bto: this.formDefinition});
            },
          }
        }
      }
    })
  }
)
runTest("Input: campo de tipo Dictionary",
  "Un diccionario es una agrupación de containers (entradas) que poseen una estructura común, definida en el diccionario.<br>" +
  "En su visualización tiene que poder verse tanto una lista de las entradas como los valores de cada una de ellas.",
  '<div data-sivWidget="dictionary-field" data-widgetParams="" data-widgetCode="Test.DictionaryField">' +
  '   <div class="label">Input con campo Dictionary:</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"simpleDictionary"}\'>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="dictionary-field"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        DictionaryField: {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  simpleDictionary: {
                    LABEL: "Campo diccionario",
                    "TYPE": "Dictionary",
                    "VALUETYPE": {
                      "TYPE": "String"
                    }
                  }
                }
              });
              this.formDefinition.simpleDictionary = {
                'primera entrada': "valor de la primera entrada",
                'segunda entrada': "valor de la segunda entrada"
              };

              return this.Form$preInitialize({bto: this.formDefinition});
            },
          }
        }
      }
    })
  }
)
runTest("Input: campo de tipo TypeSwitcher",
  "Se trata de campos que pueden cambiar su tipo a cualquier otro que tenga definido internamente.<br>" +
  "Se selecciona el campo mediante la clave definida en la clave \"<b>TYPE_FIELD</b>\"<br>" +
  "Para acceder al valor de uno de los tipos definidos se emplea la clave definida en la clave \"<b>CONTENT_FIELD\"</b>",
  '<div data-sivWidget="typeSwitcher-field" data-widgetParams="" data-widgetCode="Test.TypeSwitcherField">' +
  '   <div class="label">Input con campo TypeSwitcher:</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"simpleTypeSwitcher"}\'></div>' +
  '   </div>',
  '<div data-sivView="typeSwitcher-field"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        "TypeSwitcherField": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  simpleTypeSwitcher: {
                    "LABEL": "Campo TypeSwitcher",
                    "TYPE": "TypeSwitcher",
                    "TYPE_FIELD": "typeField",
                    CONTENT_FIELD: 'contentField',
                    "ALLOWED_TYPES": {
                      "stringType": {
                        "TYPE": "String"
                      },
                      "integerType": {
                        "TYPE": "Integer",
                      },
                      decimalType: {
                        TYPE: 'Decimal',
                        NINTEGERS: 3,
                        NDECIMALS: 2
                      },
                      textType: {
                        TYPE: 'Text'
                      },
                      enumType: {
                        TYPE: 'Enum',
                        VALUES: ['uno', 'dos', 'tres']
                      },
                      booleanType: {
                        TYPE: 'Boolean'
                      }
                    }
                  }
                }
              });
              // Cuando el tipo es un objeto, por ejemplo un container, puede establecerse el valor mediante los
              // nombres de los campos, al ser similar a aun path.
              this.formDefinition.simpleTypeSwitcher = {"typeField": "stringType", contentField: "AAA"};

              return this.Form$preInitialize({bto: this.formDefinition});
            },
            initialize: function (params) {
            },
          }
        }
      }
    })
  }
)
runTest("Input: campo de tipo Container con campos complejos",
  "Se crean los campos agrupados en el container",
  '<div data-sivWidget="container-field-complex-fields" data-widgetParams="" data-widgetCode="Test.ContainerFieldComplexFields">' +
  '   <div class="label">Container con campos complejos:</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"simpleContainer"}\'>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="container-field-complex-fields"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        ContainerFieldComplexFields: {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  simpleContainer: {
                    "LABEL": "Campo container principal",
                    "TYPE": "Container",
                    "FIELDS": {
                      containerfield: {
                        "LABEL": "Campo tipo container",
                        "TYPE": "Container",
                        FIELDS: {
                          innerField1: {
                            LABEL: 'Campo 1 del container interno',
                            TYPE: 'String',
                          },
                          innerField2: {
                            LABEL: 'Campo 2 del container interno',
                            TYPE: 'Integer',
                          },
                        },
                      },
                      arrayField: {
                        "LABEL": "Campo tipo array",
                        "TYPE": "Array",
                        "ELEMENTS": {
                          "LABEL": "Elemento del array",
                          "TYPE": "String"
                        }
                      },
                      dictionaryField: {
                        LABEL: "Campo tipo diccionario",
                        "TYPE": "Dictionary",
                        "VALUETYPE": {
                          "TYPE": "String"
                        }
                      },
                      typeSwitcherField: {
                        "LABEL": "Campo tipo TypeSwitcher",
                        "TYPE": "TypeSwitcher",
                        "TYPE_FIELD": "typeField",
                        "ALLOWED_TYPES": {
                          "typeOne": {
                            "TYPE": "String"
                          },
                          "typeTwo": {
                            "TYPE": "Container",
                            "FIELDS": {
                              field1: {
                                "LABEL": "Campo 1",
                                "TYPE": "String"
                              },
                              field2: {
                                "LABEL": "Campo 2",
                                "TYPE": "Integer"
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              });
              this.formDefinition.simpleContainer = {
                containerfield: {
                  innerField1: 'valor1',
                  innerField2: 222,
                },
                arrayField: ['AAA', 'ZZZ']
              };
              this.formDefinition.simpleContainer.dictionaryField = {
                'primera entrada': "valor de la primera entrada",
                'segunda entrada': "valor de la segunda entrada"
              }
              this.formDefinition.simpleContainer.typeSwitcherField = {
                "typeField": "typeTwo",
                "field1": "AAA",
                "field2": 77
              };

              return this.Form$preInitialize({bto: this.formDefinition});
            },
            initialize: function (params) {
            },
          }
        }
      }
    })
  }
)
runTest("Input: campo de tipo Array con campos complejos",
  "Cuando los elementos del array son complejos, los elementos de la lista se identifican por su número de orden únicamente.<br>" +
  "A su lado se crea un área donde mostrar el contenido de los elementos según se van seleccionando",
  '<div data-sivWidget="array-field-complex-fields" data-widgetParams="" data-widgetCode="Test.ArrayFieldComplexFields">' +
  '   <div class="label">Array de Container:</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"containerArray"}\'>' +
  '   </div>' +
  '   <div class="label">Array de Array:</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"arrayArray"}\'>' +
  '   </div>' +
  '   <div class="label">Array de Dictionary:</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"dictionaryArray"}\'>' +
  '   </div>' +
  '   <div class="label">Array de TypeSwitcher:</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"typeSwitcherArray"}\'>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="array-field-complex-fields"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        ArrayFieldComplexFields: {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  containerArray: {
                    "LABEL": "Array de Containers",
                    "TYPE": "Array",
                    "ELEMENTS": {
                      "TYPE": "Container",
                      "FIELDS": {
                        "field1": {
                          "LABEL": "Campo 1",
                          "TYPE": "String"
                        },
                        "field2": {
                          "LABEL": "Campo 2",
                          "TYPE": "Integer"
                        }
                      }
                    }
                  },
                  arrayArray: {
                    LABEL: 'Array de arrays',
                    TYPE: 'Array',
                    ELEMENTS: {
                      TYPE: 'Array',
                      ELEMENTS: {
                        TYPE: 'String',
                      }
                    }
                  },
                  dictionaryArray: {
                    LABEL: 'Array de diccionarios',
                    TYPE: 'Array',
                    ELEMENTS: {
                      TYPE: 'Dictionary',
                      VALUETYPE: {
                        TYPE: 'String',
                      }
                    }
                  },
                  typeSwitcherArray: {
                    LABEL: 'Array de TypeSwitchers',
                    TYPE: 'Array',
                    ELEMENTS: {
                      TYPE: 'TypeSwitcher',
                      "TYPE_FIELD": "typeField",
                      CONTENT_FIELD: 'contentField',
                      "ALLOWED_TYPES": {
                        "typeOne": {
                          "TYPE": "String"
                        },
                        "typeTwo": {
                          "TYPE": "Container",
                          "FIELDS": {
                            field1: {
                              "LABEL": "Campo 1",
                              "TYPE": "String"
                            },
                            field2: {
                              "LABEL": "Campo 2",
                              "TYPE": "Integer"
                            }
                          }
                        }
                      }
                    }
                  }
                }
              });
              this.formDefinition.containerArray = [
                {"field1": "AAA", "field2": 25},
                {"field1": "ZZZ", "field2": 30}
              ];
              this.formDefinition.arrayArray = [
                ['aaa', 'zzz'],
                ['AAA', 'ZZZ'],
              ]
              this.formDefinition.dictionaryArray = [
                {
                  'primera entrada': "valor de la primera entrada",
                  'segunda entrada': "valor de la segunda entrada"
                },
                {
                  'entrada 1': "valor de la primera entrada",
                  'entrada 2': "valor de la segunda entrada"
                },
              ]
              this.formDefinition.typeSwitcherArray = [
                {"typeField": "typeOne", contentField: "AAA"},
                {"typeField": "typeTwo", "field1": "AAA", "field2": 77}
              ]

              return this.Form$preInitialize({bto: this.formDefinition});
            },
            initialize: function (params) {
            },
          }
        }
      }
    })
  }
)
runTest("Input: campo de tipo Dictionary con campos complejos",
  "En este ejemplo se crea un input con campo diccionario para cada tipo complejo.<br>" +
  "A su lado se crea un área donde mostrar el contenido de los elementos según se van seleccionando.<br>" +
  "En este caso no se ha dado un valor inicial por lo que los campos aparecen vacíos. Para ver cómo quedaría no hay más que ir rellenándolos desde el UI.",
  '<div data-sivWidget="dictionary-field-complex-fields" data-widgetParams="" data-widgetCode="Test.DictionaryFieldComplexFields">' +
  '   <div class="label">Dictionario de Container:</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"containerDictionary"}\'>' +
  '   </div>' +
  '   <div class="label">Dictionario de Array:</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"arrayDictionary"}\'>' +
  '   </div>' +
  '   <div class="label">Dictionario de Dictionary:</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"dictionaryDictionary"}\'>' +
  '   </div>' +
  '   <div class="label">Dictionario de TypeSwitcher:</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"typeSwitcherDictionary"}\'>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="dictionary-field-complex-fields"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        DictionaryFieldComplexFields: {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  containerDictionary: {
                    LABEL: "Diccionario de container",
                    "TYPE": "Dictionary",
                    "VALUETYPE": {
                      "TYPE": "Container",
                      "FIELDS": {
                        "field1": {
                          "LABEL": "Campo 1",
                          "TYPE": "String"
                        },
                        "field2": {
                          "LABEL": "Campo 2",
                          "TYPE": "Integer"
                        }
                      }
                    }
                  },
                  arrayDictionary: {
                    LABEL: "Diccionario de array",
                    TYPE: 'Dictionary',
                    VALUETYPE: {
                      TYPE: 'Array',
                      ELEMENTS: {
                        TYPE: 'String',
                      }
                    }
                  },
                  dictionaryDictionary: {
                    LABEL: "Diccionario de diccionario",
                    TYPE: 'Dictionary',
                    VALUETYPE: {
                      TYPE: 'Dictionary',
                      VALUETYPE: {
                        "TYPE": "Container",
                        "FIELDS": {
                          "field1": {
                            "LABEL": "Campo 1",
                            "TYPE": "String"
                          },
                          "field2": {
                            "LABEL": "Campo 2",
                            "TYPE": "Integer"
                          }
                        }
                      }
                    },
                  },
                  typeSwitcherDictionary: {
                    LABEL: "Diccionario de typeSwitcher",
                    TYPE: 'Dictionary',
                    VALUETYPE: {
                      TYPE: 'TypeSwitcher',
                      "TYPE_FIELD": "typeField",
                      CONTENT_FIELD: 'contentField',
                      "ALLOWED_TYPES": {
                        "typeOne": {
                          "TYPE": "String"
                        },
                        "typeTwo": {
                          "TYPE": "Container",
                          "FIELDS": {
                            field1: {
                              "LABEL": "Campo 1",
                              "TYPE": "String"
                            },
                            field2: {
                              "LABEL": "Campo 2",
                              "TYPE": "Integer"
                            }
                          }
                        }
                      }
                    }
                  },
                }
              });
              this.formDefinition.simpleDictionary = {
                'Primera entrada': {"field1": "AAA", "field2": 555},
                'Segunda entrada': {"field1": "ZZZ", "field2": 666}
              };

              return this.Form$preInitialize({bto: this.formDefinition});
            },
            initialize: function (params) {
            },
          }
        }
      }
    })
  }
)
runTest("Input: campo de tipo TypeSwitcher con campos complejos",
  "En este ejemplo se crea un único input con campo typeSwitcher todos los campos complejos definidos como opciones.<br>" +
  "Dentro de él se crea un área donde mostrar el contenido de los elementos según se van seleccionando.<br>" +
  "Cuando el tipo es un objeto, por ejemplo un container, puede establecerse el valor mediante los nombres de los campos, al ser similar a aun path.<br>" +
  "En este caso no se ha dado un valor inicial por lo que los campos aparecen vacíos. Para ver cómo quedaría no hay más que ir rellenándolos desde el UI.",
  '<div data-sivWidget="typeSwitcher-field-complex-fields" data-widgetParams="" data-widgetCode="Test.TypeSwitcherFieldComplexFields">' +
  '   <div class="label">TypeSwitcher con campos complejos:</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"simpleTypeSwitcher"}\'>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="typeSwitcher-field-complex-fields"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        TypeSwitcherFieldComplexFields: {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  simpleTypeSwitcher: {
                    "LABEL": "Campo TypeSwitcher",
                    "TYPE": "TypeSwitcher",
                    "TYPE_FIELD": "typeField",
                    CONTENT_FIELD: 'contentField',
                    "ALLOWED_TYPES": {
                      "containerType": {
                        "TYPE": "Container",
                        LABEL: 'Campo Container',
                        FIELDS: {
                          field1: {
                            LABEL: 'Campo 1',
                            TYPE: 'String',
                          },
                          field2: {
                            LABEL: 'Campo 2',
                            TYPE: 'Integer'
                          }
                        }
                      },
                      "arrayType": {
                        "TYPE": "Array",
                        LABEL: 'Campo Array',
                        ELEMENTS: {
                          TYPE: 'String'
                        }
                      },
                      dictionaryType: {
                        TYPE: 'Dictionary',
                        LABEL: 'Campo Dictionary',
                        VALUETYPE: {
                          TYPE: 'String'
                        }
                      },
                      // ToDo: no se está mostrando el contenido del TypeSwitcher interior
                      typeSwitcherType: {
                        TYPE: 'TypeSwitcher',
                        LABEL: 'Campo TypeSwitcher',
                        ALLOWED_TYPES: {
                          typeOne: {
                            TYPE: 'String',
                            LABEL: 'Campo String',
                          },
                          typeTwo: {
                            TYPE: 'Integer',
                            LABEL: 'Campo Integer',
                          }
                        }
                      },
                    }
                  }
                }
              });
              this.formDefinition.simpleTypeSwitcher = {typeField: 'containerType', field1: 'AAA', field2: 4}

              return this.Form$preInitialize({bto: this.formDefinition});
            },
            initialize: function (params) {
            },
          }
        }
      }
    })
  }
)
runTest("Validación del valor de un campo",
  "Se establece un valor invalido y se ve si el input inmediatamente muestra el error.<br>" +
  "",
  '<div data-sivWidget="input-validation" data-widgetParams="" data-widgetCode="Test.InputValidation">' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"fieldName"}\'>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="input-validation"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        InputValidation: {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  fieldName: {
                    TYPE: "String",
                    MINLENGTH: 3,
                    LABEL: "Campo a validar",
                  }
                }
              });
              // Aqui ignoramos la excepcion, queremos que se pinte el input con el error.
              try {
                this.formDefinition.fieldName = "ab";
              } catch (e) {
              }

              return this.Form$preInitialize({bto: this.formDefinition});
            },
            initialize: function (params) {
            },
            // show: function () {},
          }
        }
      }
    })
  }
)
runTest("Mostrar campos mediante paths",
  "Para mostrar un campo que no está renderizado se emplea la funcion <b>showPath\(<i>path</i>\)</b>.<br>" +
  "Esta función refresca el render de la plantilla para mostrar el elemento indicado por el path que se le pasa como parámetro.",
  '<div data-sivWidget="showPath-method" data-widgetCode="Test.ShowPathMethod">' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"key":"ROOT","parent":"*type","form":"*self"}\'>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="showPath-method"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        "ShowPathMethod": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                  "FIELDS": {
                    "ROOT": {
                      "LABEL": "Container externo",
                      "TYPE": "Dictionary",
                      "VALUETYPE": {
                        "LABEL": "Container interno",
                        "TYPE": "Container",
                        "FIELDS": {
                          "stringType": {
                            "LABEL": "Campo string",
                            "TYPE": "String",
                          },
                          "arrayType": {
                            "LABEL": "Campo array",
                            "TYPE": "Array",
                            "ELEMENTS": {
                              "LABEL": "array label",
                              "TYPE": "TypeSwitcher",
                              "TYPE_FIELD": "selectedType",
                              "ALLOWED_TYPES": {
                                "customType1": {
                                  "LABEL": "Tipo custom 1",
                                  "TYPE": "Container",
                                  "FIELDS": {
                                    "customType1-field1": {
                                      "LABEL": "Campo 1 en tipo 1",
                                      "TYPE": "String"
                                    },
                                    "customType1-field2": {
                                      "LABEL": "Campo 2 en tipo 1",
                                      "TYPE": "Integer"
                                    },
                                    // Dani => no tengo claro que hace este fragmento
                                    // "TYPE": {
                                    //   "LABEL": "Tipo",
                                    //   "TYPE": "String",
                                    //   "FIXED": "customType1"
                                    // }
                                  }
                                },
                                "customType2": {
                                  "LABEL": "Tipo custom 2",
                                  "TYPE": "Container",
                                  "FIELDS": {
                                    "customType2-field1": {
                                      "LABEL": "Campo 1 en tipo 2",
                                      "TYPE": "Enum",
                                      VALUES: ['uno', 'dos', 'tres'],
                                    },
                                    "customType2-field2": {
                                      "LABEL": "Campo 2 en tipo 2",
                                      "TYPE": "Boolean"
                                    },
                                    "TYPE": {
                                      "LABEL": "Tipo",
                                      "TYPE": "String",
                                      "FIXED": "customType2"
                                    }
                                  }
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                  }
                }
              );
              this.formDefinition.setValue({
                "ROOT": {
                  "dict1": {
                    stringType: "Valor para dict 1",
                    arrayType: [
                      {
                        "selectedType": "customType1",
                        'customType1-field1': "dic1->campo custom1",
                        "customType1-field2": 1
                      },
                      {"selectedType": "customType2", 'customType2-field1': "uno", "customType2-field2": false},
                    ]
                  },
                  "dict2": {
                    stringType: "Valor para dict 2",
                    arrayType: [
                      {
                        "selectedType": "customType1",
                        'customType1-field1': "dic2->campo custom1",
                        "customType1-field2": 2
                      },
                      {"selectedType": "customType2", 'customType2-field1': "dos", "customType2-field2": true},
                      {
                        "selectedType": "customType1",
                        'customType1-field1': "dic2->campo custom2",
                        "customType1-field2": 2
                      },
                    ]
                  },
                  "dict3": {
                    stringType: "Valor para dict 3",
                    arrayType: [
                      {
                        "selectedType": "customType1",
                        'customType1-field1': "dic3->campo custom1",
                        "customType1-field2": 3
                      },
                      {"selectedType": "customType2", 'customType2-field1': "tres", "customType2-field2": true},
                      {
                        "selectedType": "customType1",
                        'customType1-field1': "dic3->campo custom2",
                        "customType1-field2": 3
                      },
                      {
                        "selectedType": "customType1",
                        'customType1-field1': "dic3->campo custom3",
                        "customType1-field2": 3
                      },
                    ]
                  },
                }
              });
              return this.Form$preInitialize({bto: this.formDefinition});
            },
            initialize: function (params) {
              // Se comenta para que no salte la página hasta este punto cuando se muestran todos los tests
              // Para ver el funcionamiento de la función solo hay que descomentar el siguiente bloque
              /*var paths = [
                "/ROOT/dict1/stringType",
                "/ROOT/dict2/arrayType/0/customType1-field1",
                "/ROOT/dict3/arrayType/1/customType2-field2"
              ]
              var idx = 0;
              var m = this;
              setInterval(function(){
                 m.showPath(paths[idx]);
                 idx=(idx+1)%paths.length;
              },3000);*/
            },
            show: function () {
            },
          }
        }
      }
    })
  }
)
runTest("ComboBox: definición (SOURCE tipo array)",
  "Cuando la fuente de un input es un array se genera un input con un comboBox de opciones asociado.<br>" +
  "El array debe contener un objeto para cada opción en donde se indique cuál es el valor que adopta el campo y cual es la etiqueta que debe mostarse.<br>",
  '<div data-sivWidget="comboBox-input" data-widgetParams="" data-widgetCode="Test.ComboBoxInput">' +
  '   <div class="label">ComboBox</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"fieldName"}\'>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="comboBox-input"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        ComboBoxInput: {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  fieldName: {
                    LABEL: "Input comboBox",
                    "TYPE": "Integer",
                    "SOURCE": {
                      "TYPE": "Array",
                      "LABEL": "labelKey",
                      "VALUE": "valueKey",
                      "DATA": [
                        {"valueKey": 1, "labelKey": "Opción 1"},
                        {"valueKey": 2, "labelKey": "Opción 2"},
                        {"valueKey": 3, "labelKey": "Opción 3"},
                        {"valueKey": 4, "labelKey": "Opción 4"},
                      ],
                    }
                  },
                }
              });
              return this.Form$preInitialize({bto: this.formDefinition});
            },
            initialize: function (params) {
            },
          }
        }
      }
    })
  }
)
runTest("ComboBox: ComboBox dependientes",
  "Una fuente de un input puede tener varios conjuntos de opciones, seleccionándose uno u otro según una clave externa, indicada en \"SOURCE/PATH/\"<br>" +
  "Esta clave externa tiene que tratarse de otro campo con su propia fuente. De esta forma las fuentes quedan relacionadas.",
  '<div data-sivWidget="dependant-comboBox" data-widgetParams="" data-widgetCode="Test.DependantComboBox">' +
  '   <div class="label">ComboBox origen</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"originComboBox"}\'>' +
  '   </div>' +
  '   <div class="label">ComboBox dependiente de comboBox origen</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"firstDependantComboBox"}\'>' +
  '   </div>' +
  '   <div class="label">ComboBox dependiente de comboBox anterior</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"*self","parent":"*type","form":"*form","key":"secondDependantComboBox"}\'>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="dependant-comboBox"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
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
                    "LABEL": "Combo fuente origen",
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
                    "LABEL": "Combo dependiente de origen",
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
                    "LABEL": "Combo dependiente segundo nivel",
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

              return this.Form$preInitialize({bto: this.formDefinition});
            },
            initialize: function (params) {
            },
            // show: function () {},
          }
        }
      }
    })
  }
)
runTest("ComboBox: SOURCE remoto",
  "Puede establecerse un modelo remoto como SOURCE del ComboBox, ya que el widget Model deriva directamente del widget ComboBox.",
  '<div data-sivWidget="remote-source" data-widgetCode="Test.RemoteSource">' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"key":"dataSourceSource", "controller":"/*self","parent":"/*type","form":"/*form"}\'>' +
  '   </div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"key":"dataSourceParams", "controller":"/*self","parent":"/*type","form":"/*form"}\'>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="remote-source"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        RemoteSource: {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              // this.factory = Siviglia.types.TypeFactory;
              // this.self = this;
              // this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  dataSourceSource: {
                    "LABEL": "DataSource como fuente",
                    "TYPE": "String",
                    "SOURCE": {
                      "TYPE": "DataSource",
                      // El valor de LABEL es el la clave dentro de la respuesta que se va tomar para mostrar las
                      // opciones en el comboBox. En este caso:
                      // http://metadata.adtopy.com//model/web/Page/datasources/FullList/definition > FIELDS > name
                      "LABEL": "name",
                      // El campo value es igual que LABEL pero para el valor que va a tomar el campo cuando se
                      // seleccione la opción.
                      "VALUE": "id_page",
                      "MODEL": "/model/web/Page",
                      "DATASOURCE": "FullList",
                    }
                  },
                  dataSourceParams: {
                    "LABEL": "DataSource con query parameters",
                    "TYPE": "String",
                    "SOURCE": {
                      "TYPE": "DataSource",
                      "LABEL": "NAME",
                      "VALUE": "NAME",
                      "MODEL": "/model/reflection/Model",
                      "DATASOURCE": "FieldList",
                      // Si es necesario incluir parámetros adicionales en la petición de la obtención del datasource,
                      // esto se hace mediante la clave PARAMS.
                      "PARAMS": {
                        "model": "/model/ads/Comscore"
                      },
                    }
                  },
                }
              });
              return this.Form$preInitialize({bto: this.formDefinition});
            },
            initialize: function (params) {
            },
            // show: function () {},
          }
        }
      }
    })
  }
)
runTest("ComboBox: SOURCE remotos dependientes",
  "Al igual que con los SOURCE de tipo Array, se pueden hacer depender los SOURCE remotos mediante los parámetros de los dataSource dependientes.",
  '<div data-sivWidget="dependant-remote-source" data-widgetCode="Test.DependantRemoteSource">' +
  '   <div class="label">Campo con source remoto DATASOURCE</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"modelSelector"}\'>' +
  '   </div>' +
  '   <div class="label">Campo con source remoto DATASOURCE dependiente</div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"controller":"/*self","parent":"/*type","form":"/*form","key":"fieldSelector"}\'>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="dependant-remote-source"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        DependantRemoteSource: {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              // this.factory = Siviglia.types.TypeFactory;
              // this.self = this;
              // this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  modelSelector: {
                    // El tipo modelo no necesita definir su SOURCE, ya que se encuentra dentro de la propia
                    // definición del tipo.
                    "TYPE": "Model",
                    "LABEL": "Modelo",
                  },
                  fieldSelector: {
                    "LABEL": "Campo",
                    "TYPE": "String",
                    "SOURCE": {
                      "TYPE": "DataSource",
                      "LABEL": "NAME",
                      "VALUE": "NAME",
                      "MODEL": "/model/reflection/Model",
                      "DATASOURCE": "FieldList",
                      "PARAMS": {
                        // Se toma como valor de la clave el valor del campo indicado en la PS.
                        "model": "[%#modelSelector%]"
                      },
                    }
                  },
                }
              });
              return this.Form$preInitialize({bto: this.formDefinition});
            },
            initialize: function (params) {
            },
            // show: function () {},
          }
        }
      }
    })
  }
)
runTest("Definición remota de campo",
  "Se puede emplear una definción remota de un campo dándole a la clave TYPE (o similar, como VALUETYPE) el valor del path remoto donde se encuentre la definición.",
  '<div data-sivWidget="remote-field-definition" data-widgetParams="" data-widgetCode="Test.RemoteFieldDefintion">' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"key":"remoteField","controller":"/*self","parent":"/*type","form":"/*form"}\'>' +
  '   </div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"key":"remoteDictionary","controller":"/*self","parent":"/*type","form":"/*form"}\'>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="remote-field-definition"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        RemoteFieldDefintion: {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              // this.factory = Siviglia.types.TypeFactory;
              // this.self = this;
              // this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  remoteField: {
                    "TYPE": "/model/reflection/Types/types/BaseType",
                  },
                  remoteDictionary: {
                    TYPE: 'Dictionary',
                    VALUETYPE: '/model/reflection/Types/types/BaseType',
                  }
                }
              });
              return this.Form$preInitialize({bto: this.formDefinition});
            },
            initialize: function (params) {
            },
            // show: function () {},
          }
        }
      }
    })
  }
)
runTest("Definición remota de formulario",
  "En vez de declararse explicitamente la definición del formulario a mostrar, puede especificarse el <b>modelo</b> al que pertenece dicho formulario y su <b>nombre</b>, encargándose el framework de obtener la definición necesaria para que la vista pueda formarse.<br>" +
  "Si se desea completar el formulario con un valor concreto, como puede ser en el caso de un formulario de edición, se puede especificar mediante el empleo de <b>keys</b>.<br>",
  '<div data-sivWidget="remote-form-definition" data-widgetCode="Test.RemoteFormDefinition">' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"name","parent":"*type","form":"*form","controller":"*self"}\'></div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"tag","parent":"*type","form":"*form","controller":"*self"}\'></div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"id_site","parent":"*type","form":"*form","controller":"*self"}\'></div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"isPrivate","parent":"*type","form":"*form","controller":"*self"}\'></div>' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer" data-sivParams=\'{"key":"path","parent":"*type","form":"*form","controller":"*self"}\'></div>' +
  '   <div><input type="button" data-sivEvent="click" data-sivCallback="submit" value="Guardar"></div>' +
  '</div>',
  '<div data-sivView="remote-form-definition" data-sivParams=\'{"id_page":1}\'></div>',
  function () {
    Siviglia.Utils.buildClass({
      "context": "Test",
      "classes": {
        RemoteFormDefinition: {
          "inherits": "Siviglia.inputs.jqwidgets.Form",
          "methods": {
            preInitialize: function (params) {
              this.self = this;
              var formDefinition = {
                "model": "/model/web/Page",
                "form": "Edit",
                "keys": params,
              }
              return this.Form$preInitialize(formDefinition);
            }
          }
        }
      }
    });
  }
)
runTest("Definición remota de widget",
  "Puede declararse un widget completo desde la vista, invocándolo mediante el propio nombre de la vista.<br>" +
  "Al no especificase los campos que se quieren mostrar de entre los que traiga la definición remota, se creará un formulario con todos ellos.<br>" +
  "Como puede verse en el ejemplo, no es necesario disponer de plantilla o clase, y el namespace del formulario es el que espera el servidor.",

  '',
  '<div data-sivView="Siviglia.model.web.Page.forms.Edit" data-sivParams=\'{"id_page":2}\'></div>',
  function () {
  }
)

runTest("Source exclusivo", "Prueba de source exclusivo, donde los elementos de un source, no pueden repetirse en el valor asociado. <br>",
  '<div data-sivWidget="Test.ExSource" data-widgetParams="" data-widgetCode="Test.ExSource">' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"key":"exSource", "controller":"*self","parent":"*type","form":"*form"}\'></div>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="Test.ExSource"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        "ExSource": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {

              this.factory = Siviglia.types.TypeFactory;
              this.self = this;
              this.typeCol = [];
              /* STRING **************************/
              this.typedObj = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  exSource:
                    {
                      "LABEL": "FieldSelector",
                      "TYPE": "Array",
                      "ELEMENTS": {
                        "TYPE": "Integer"
                      },
                      "SOURCE": {
                        "TYPE": "Array",
                        "UNIQUE": true,
                        "DATA": [
                          {"a": 1, "message": "Opcion 1"},
                          {"a": 2, "message": "Opcion 2"},
                          {"a": 3, "message": "Opcion 3"},
                          {"a": 4, "message": "Opcion 4"},
                          {"a": 5, "message": "Opcion 5"},
                          {"a": 6, "message": "Opcion 6"},
                          {"a": 7, "message": "Opcion 7"}
                        ],
                        "LABEL": "message",
                        "VALUE": "a"
                      }
                    }
                },
                "INPUTPARAMS": {
                  "/exSource": {
                    "INPUT": "SourcedArray",
                  }
                }
              });

              return this.Form$preInitialize({bto: this.typedObj});
            },
            initialize: function (params) {
            },
            show: function () {
            }

          }
        }

      }

    })
  }
)
runTest("Lista recursiva de DataSource",
  "Este widget muestra el contenido de un DataSource en una lista.<br>" +
  "Para crearlo es necesario que la clase del widget padre incluya una variable listValue, que toma el valor de los elementos de las listas clicados, y un objeto innerListParams con las siguientes claves:<br>" +
  "  - model, dataSource y keys: definen el DS que se va a mostrar" +
  "  - label: campo del DS que se va a mostrar en la lista<br>" +
  "  - value: campo del DS que se va a tomar como valor<br>" +
  "  - listParam: qué elemento de la definición del DS anidado (model, datasource o keys) se va a completar con el valor de la lista actual<br>" +
  "  - keyParams: en el caso de que listParam sea \"keys\", se emplea para definir la clave del valor dentro de keys<br>" +
  "  - innerListParams: un objeto con los mismos elementos que los descritos, para generar la lista anidada.",
  '<div data-sivWidget="recursive-ds-list" data-widgetParams="" data-widgetCode="Test.RecursiveDSList">' +
  '   <div data-sivView="Siviglia.widgets.jqwidgets.RecursiveDSList"\n' +
  '       data-sivParams=\'{"innerListParams":"*innerListParams"}\'></div>' +
  '   <div data-sivValue="[%*listValue%]"></div>' +
  '   </div>' +
  '</div>',
  '<div data-sivView="recursive-ds-list"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        RecursiveDSList: {
          inherits: 'Siviglia.UI.Expando.View',
          methods: {
            preInitialize: function () {
              this.innerListParams = {
                model: '/model/reflection/Model',
                dataSource: 'PackageList',
                // keys: {},
                label: 'name',
                value: 'name',
                listParam: 'keys',
                keysParam: 'package',
                innerListParams: {
                  model: '/model/reflection/Model',
                  dataSource: 'FullList',
                  keys: {},
                  label: 'name',
                  value: 'smallName',
                }
              }
              this.listValue = 'valor inicial'
            }
          },
        },
      }
    })
  }
)
checkTests()