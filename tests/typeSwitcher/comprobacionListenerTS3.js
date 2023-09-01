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
  var fire1 = 0, fire2 = 0, fire3 = 0, f3FiredFirst = false;
  t1["*f4"].addListener("CHANGE", null, function () {
    flag++;
    fire1 = 1;
  }, "Listener t4");
  t1["*f4"]["*value"].addListener("CHANGE", null, function () {
    flag++;
    fire2 = 1;
  }, "Listener value")
  t1.f4.value["*f3"].addListener("CHANGE", null, function () {
    flag++;
    fire3 = 1;
    if (fire1 == 0)
      f3FiredFirst = true;
  }, "Listener f3")
  var n = countListeners();
  t1.f4.value.f3 = "adios";
  t1.f4.TYPE = "TIPO7";
  t1.destruct();
  var n2 = countListeners();
  return flag == 2 && n2 == 0 && n == 3 && f3FiredFirst == true && fire3 == 1 && fire1 == 1;

//codeEnd
//callbackEnd
})