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
  t1.f2 = {TYPE: "TIPO1", "f3": "aaa"};
  var s = t1.f2.f3;
  var status = typeof t1.f2.f3 !== "undefined" && typeof t1.f2["*f3"] !== "undefined" &&
    typeof t1.f2.f4 !== "undefined" && typeof t1.f2["*f4"] !== "undefined";
  t1.destruct();
  return status;

//codeEnd
//callbackEnd
})