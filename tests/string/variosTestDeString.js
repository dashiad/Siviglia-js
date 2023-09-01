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
  var flag = 0;
  var nExcp = 0;
  var nErrors = 0;
  t1.setValue({});
  t1["*s1"].addListener("ERROR", null, function () {
    nErrors++;

  })
  t1["*s1"].addListener("CHANGE", null, function () {
    flag++;

  })
  try {
    t1.s1 = "aa";
  } catch (e) {
    nExcp++;
  }
  try {
    t1.s1 = "zzzzzz";
  } catch (e) {
    nExcp++;
  }
  t1.s1 = "aaa";

  var n = countListeners();
  t1.destruct();
  var n1 = countListeners();
  return nExcp == 2 && flag === 1 && nErrors == 2 && n == 2 && n1 == 0;

//codeEnd
//callbackEnd
})