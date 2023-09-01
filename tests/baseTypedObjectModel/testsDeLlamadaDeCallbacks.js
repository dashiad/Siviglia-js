(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "one": {
          "TYPE": "String",
          "MINLENGTH": 2,
          "MAXLENGTH": 4
        },
        "two": {
          "TYPE": "String",
          "MINLENGTH": 2,
          "MAXLENGTH": 4
        },
        "three": {
          "TYPE": "String",
          "MINLENGTH": 2,
          "MAXLENGTH": 4
        },
        "four": {
          "TYPE": "String",
          "MINLENGTH": 2,
          "MAXLENGTH": 4
        },
        "five": {
          "TYPE": "String",
          "MINLENGTH": 2,
          "MAXLENGTH": 4
        },
        "status": {
          "TYPE": "State",
          "VALUES": [
            "None",
            "Other",
            "Another",
            "Last"
          ],
          "DEFAULT": "None"
        }
      },
      "STATES": {
        "LISTENER_TAGS": {
          "ONE": {
            "TYPE": "METHOD",
            "METHOD": "callback_one"
          },
          "TWO": {
            "TYPE": "METHOD",
            "METHOD": "callback_two"
          },
          "THREE": {
            "TYPE": "METHOD",
            "METHOD": "callback_three"
          },
          "FAIL_TEST": {
            "TYPE": "METHOD",
            "METHOD": "test_nok"
          },
          "TEST_OK": {
            "TYPE": "METHOD",
            "METHOD": "test_ok"
          },
          "P_ONE": {
            "TYPE": "PROCESS",
            "CALLBACKS": [
              "ONE",
              "TWO"
            ]
          }
        },
        "STATES": {
          "None": {
            "LISTENERS": {
              "ON_LEAVE": {
                "STATES": {
                  "Other": [
                    "ONE"
                  ]
                }
              },
              "TESTS": [
                "TEST_OK"
              ]
            },
            "FIELDS": {
              "EDITABLE": [
                "one",
                "three"
              ]
            }
          },
          "Other": {
            "ALLOW_FROM": [
              "None",
              "Another"
            ],
            "LISTENERS": {
              "ON_ENTER": {
                "STATES": {
                  "None": [
                    "TWO"
                  ],
                  "Another": [
                    "THREE"
                  ]
                }
              }
            },
            "FIELDS": {
              "EDITABLE": [
                "two",
                "three"
              ],
              "FIXED": [
                "one"
              ]
            }
          },
          "Another": {
            "LISTENERS": {
              "TESTS": [
                "FAIL_TEST"
              ]
            },
            "FIELDS": {
              "EDITABLE": [
                "one"
              ],
              "REQUIRED": [
                "three"
              ]
            }
          },
          "Last": {
            "FINAL": 1,
            "LISTENERS": {
              "ON_ENTER": {
                "STATES": {
                  "None": [
                    "THREE",
                    "P_ONE"
                  ]
                }
              },
              "TESTS": [
                "TEST_OK"
              ]
            },
            "FIELDS": {
              "EDITABLE": [
                "one"
              ],
              "REQUIRED": [
                "three"
              ]
            }
          }
        },
        "FIELD": "status",
        "DEFAULT": "None"
      }
    }
//definitionEnd

//codeInit
  var obj = new Test.SimpleTypedObject(def);
  obj.status = "Other";
  var result = true;
  // Se ha abandonado el estado None, por lo que se ha tenido que ejecutar
  // el LISTENER_TAG ONE, es decir, el callback_one, y la variable __one debe valer "one".
  // Tambien se ha entrado en el estado Other, desde el estado None,
  // por lo que se ha tenido que ejecutar el LISTENER_TAG "TWO"
  result = result && "one" == obj.__one;
  result = result && "set" == obj.__two;
  result = result && null == obj.__three;
  obj.cleanDirtyFields();
  // Se pasa de "Other" a "Another": Debe fallar porque el test_nok se
  // ejecuta, y devuelve false.Se comprueba la excepcion, y que se ha
  // ejecutado el test.
  obj.three = "thr";
  var thrown = false;
  try {
    obj.status = "Another";

  } catch (e) {
    thrown = true;
    result = e.type == 'BaseTypedException';
    result = result && e.code == Siviglia.model.BaseTypedException.ERR_CANT_CHANGE_STATE;
  }
  obj.destruct();
  return result && thrown;

//codeEnd
//callbackEnd
})