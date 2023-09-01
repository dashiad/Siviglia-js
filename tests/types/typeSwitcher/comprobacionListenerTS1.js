(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f1": {
          "TYPE": "Array",
          "VALUETYPE": {
            "TYPE": "String"
          }
        },
        "f2": {
          "TYPE": "TypeSwitcher",
          "TYPE_FIELD": "TYPE",
          "ALLOWED_TYPES": {
            "TIPO1": {
              "TYPE": "Container",
              "FIELDS": {
                "TYPE": {
                  "TYPE": "String"
                },
                "f3": {
                  "TYPE": "String",
                  "MINLENGTH": 2
                },
                "f4": {
                  "TYPE": "Integer"
                }
              }
            },
            "TIPO2": {
              "TYPE": "Container",
              "FIELDS": {
                "TYPE": {
                  "TYPE": "String"
                },
                "f1": {
                  "TYPE": "String",
                  "MINLENGTH": 2
                },
                "f2": {
                  "TYPE": "Integer"
                }
              }
            }
          }
        },
        "f3": {
          "TYPE": "TypeSwitcher",
          "TYPE_FIELD": "TYPE",
          "IMPLICIT_TYPE": "TIPO3",
          "ALLOWED_TYPES": {
            "TIPO3": {
              "TYPE": "Container",
              "FIELDS": {
                "TYPE": {
                  "TYPE": "String"
                },
                "f3": {
                  "TYPE": "String",
                  "MINLENGTH": 2
                },
                "f4": {
                  "TYPE": "Integer"
                }
              }
            },
            "TIPO4": {
              "TYPE": "Container",
              "FIELDS": {
                "TYPE": {
                  "TYPE": "String"
                },
                "f1": {
                  "TYPE": "String",
                  "MINLENGTH": 2
                },
                "f2": {
                  "TYPE": "Integer"
                }
              }
            }
          }
        },
        "f4": {
          "TYPE": "TypeSwitcher",
          "TYPE_FIELD": "TYPE",
          "CONTENT_FIELD": "value",
          "IMPLICIT_TYPE": "TIPO6",
          "ALLOWED_TYPES": {
            "TIPO6": {
              "TYPE": "Container",
              "FIELDS": {
                "TYPE": {
                  "TYPE": "String"
                },
                "f3": {
                  "TYPE": "String",
                  "MINLENGTH": 2
                },
                "f4": {
                  "TYPE": "Integer"
                }
              }
            },
            "TIPO7": {
              "TYPE": "Container",
              "FIELDS": {
                "TYPE": {
                  "TYPE": "String"
                },
                "f1": {
                  "TYPE": "String",
                  "MINLENGTH": 2
                },
                "f2": {
                  "TYPE": "Integer"
                }
              }
            }
          }
        },
        "s1": {
          "TYPE": "String",
          "MINLENGTH": 3,
          "MAXLENGTH": 4
        },
        "s2": {
          "TYPE": "String",
          "REGEXP": "/aa/"
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(def);
  t1.f4 = {value: {f3: "hola", f4: 25}};
  var flag = 0;
  t1["*f4"].addListener("CHANGE", null, function () {
    flag = 1;
  })
  var n = countListeners();
  t1.f4.TYPE = "TIPO7";
  t1.destruct();
  var n2 = countListeners();
  return flag == 1 && n2 == 0 && n == 1;

//codeEnd
//callbackEnd
})