runTest("Test Aliases",
  "Prueba de edicion de alises de modelos",
  '<div data-sivWidget="Test.Aliases" data-widgetParams="" data-widgetCode="Test.Aliases">' +
  '   <div data-sivView="Siviglia.inputs.jqwidgets.StdInputContainer"\n' +
  '       data-sivParams=\'{"key":"ALIASES","controller":"*self","parent":"*type","form":"*form"}\'' +
  '   >' +
  '   </div>' +
  '       <input type="button" data-sivEvent="click" data-sivCallback="show" value="Log">' +
  '   </div>' +
  '</div>',
  '<div data-sivView="Test.Aliases"></div>',
  function () {
    Siviglia.Utils.buildClass({
      context: 'Test',
      classes: {
        "Aliases": {
          inherits: "Siviglia.inputs.jqwidgets.Form",
          methods: {
            preInitialize: function (params) {
              // this.factory = Siviglia.types.TypeFactory;
              // this.self = this;
              // this.typeCol = [];
              this.formDefinition = new Siviglia.model.BaseTypedObject({
                "FIELDS": {
                  "FIELDS": {
                    "LABEL": "Campos",
                    "TYPE": "Dictionary",
                    "VALUETYPE": "/model/reflection/Types/types/BaseType",
                    "HELP": "Campos existentes en el modelo"
                  },
                  "ALIASES": {
                    "LABEL": "Aliases",
                    "TYPE": "Dictionary",
                    "VALUETYPE": {
                      "TYPE": "TypeSwitcher",
                      "LABEL": "Tipo de Alias",
                      "TYPE_FIELD": "TYPE",
                      "ALLOWED_TYPES": {
                        "InverseRelation": {
                          "LABEL": "Relacion inversa",
                          "TYPE": "Container",
                          "FIELDS": {
                            "TYPE": {
                              "LABEL": "Type",
                              "TYPE": "String",
                              "FIXED": "InverseRelation"
                            },
                            "MODEL": {
                              "LABEL": "Model",
                              "TYPE": "Model",
                              "REQUIRED": true
                            },
                            "FIELDS": {
                              "LABEL": "Campos",
                              "HELP": "Campos que definen esta relacion (local=>remoto)",
                              "TYPE": "Dictionary",
                              "SOURCE": {
                                "TYPE": "Path",
                                "PATH": "#../../../FIELDS/[[KEYS]]",
                                "LABEL": "LABEL",
                                "VALUE": "LABEL"
                              },
                              "VALUETYPE": {
                                "LABEL": "Campo remoto",
                                "TYPE": "String",
                                "SOURCE": {
                                  "TYPE": "DataSource",
                                  "MODEL": "\/model\/reflection\/Model",
                                  "DATASOURCE": "FieldList",
                                  "PARAMS": {
                                    "model": "[%#..\/MODEL%]"
                                  },
                                  "LABEL": "NAME",
                                  "VALUE": "NAME"
                                }
                              },
                              "REQUIRED": true
                            },
                            "MULTIPLICITY": {
                              "LABEL": "Multiplicidad",
                              "TYPE": "String",
                              "SOURCE": {
                                "TYPE": "Array",
                                "DATA": [{
                                  "Label": "1:N"
                                }, {
                                  "Label": "0-1:N"
                                }
                                ],
                                "LABEL": "Label",
                                "VALUE": "Label"
                              }
                            },
                            "HELP": {
                              "LABEL": "Ayuda",
                              "TYPE": "Text",
                              "KEEP_KEY_ON_EMPTY": false
                            },
                            "CARDINALITY": {
                              "LABEL": "Cardinalidad",
                              "TYPE": "Integer",
                              "HELP": "Numero aproximado de elementos del modelo remoto que apuntan a 1 elemento del modelo actual."
                            },
                            "KEEP_KEY_ON_EMPTY": {
                              "LABEL": "Permitir valor vac\u00edo",
                              "TYPE": "Boolean",
                              "KEEP_KEY_ON_EMPTY": false
                            },
                            "REQUIRED": {
                              "TYPE": "Boolean",
                              "DEFAULT": false,
                              "LABEL": "Requerido",
                              "KEEP_KEY_ON_EMPTY": false
                            },
                            "DEFAULT": {
                              "TYPE": "String",
                              "LABEL": "Valor por defecto",
                              "KEEP_KEY_ON_EMPTY": false
                            }
                          }
                        },
                        "RelationMxN": {
                          "LABEL": "Relacion Multiple",
                          "HELP": "Una relaci\u00f3n m\u00faltiple requiere que exista un modelo intermedio, con  ROLE tipo MULTIPLE_RELATIONSHIP, que almacena los campos relacionados.",
                          "TYPE": "Container",
                          "FIELDS": {
                            "TYPE": {
                              "LABEL": "Type",
                              "TYPE": "String",
                              "FIXED": "RelationMxN"
                            },
                            "MODEL": {
                              "LABEL": "Modelo intermedio",
                              "TYPE": "Model",
                              "HELP": "Modelo que contiene la relaci\u00f3n",
                              "REQUIRED": true
                            },
                            "REMOTE_MODEL": {
                              "LABEL": "Modelo remoto",
                              "HELP": "Modelo que est\u00e1 en el otro extremo de la relaci\u00f3n, con el que se relaciona a traves del modelo intermedio",
                              "TYPE": "Model",
                              "REQUIRED": true
                            },
                            "FIELDS": {
                              "LABEL": "Campos",
                              "HELP": "Campos que definen esta relacion (local=>remoto)",
                              "TYPE": "Dictionary",
                              "SOURCE": {
                                "TYPE": "Path",
                                "PATH": "#..\/..\/..\/FIELDS\/[[KEYS]]",
                                "LABEL": "LABEL",
                                "VALUE": "LABEL"
                              },
                              "VALUETYPE": {
                                "LABEL": "Campo remoto (en la tabla intermedia)",
                                "TYPE": "String",
                                "SOURCE": {
                                  "TYPE": "DataSource",
                                  "MODEL": "\/model\/reflection\/Model",
                                  "DATASOURCE": "FieldList",
                                  "PARAMS": {
                                    "model": "[%#..\/MODEL%]"
                                  },
                                  "LABEL": "NAME",
                                  "VALUE": "NAME"
                                }
                              },
                              "REQUIRED": true
                            },
                            "RELATIONS_ARE_UNIQUE": {
                              "LABEL": "Relaciones unicas",
                              "TYPE": "Boolean",
                              "DEFAULT": false
                            },
                            "CARDINALITY": {
                              "LABEL": "Cardinalidad",
                              "TYPE": "Integer"
                            },
                            "KEEP_KEY_ON_EMPTY": {
                              "LABEL": "Permitir valor vac\u00edo",
                              "TYPE": "Boolean",
                              "KEEP_KEY_ON_EMPTY": false
                            },
                            "REQUIRED": {
                              "TYPE": "Boolean",
                              "DEFAULT": false,
                              "LABEL": "Requerido",
                              "KEEP_KEY_ON_EMPTY": false
                            },
                            "DEFAULT": {
                              "TYPE": "String",
                              "LABEL": "Valor por defecto",
                              "KEEP_KEY_ON_EMPTY": false
                            }
                          }
                        }
                      }
                    },
                    "HELP": "Aliases (relaciones inversas y multiples)"
                  },

                }
              });
              this.formDefinition.FIELDS = {
                "uno": {"TYPE": "Boolean"},
                "dos": {"TYPE": "String"}
              };

              return this.Form$preInitialize({bto: this.formDefinition});
            },
            initialize: function (params) {
            },
            show: function () {
              console.log(this.formDefinition.getPlainValue());
            }

          }
        }

      }

    })
  }
)
checkTests()