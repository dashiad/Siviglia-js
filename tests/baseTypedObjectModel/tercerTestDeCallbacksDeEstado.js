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

  obj.three = "thr";
  obj.status = "Last";
  var result = true;
  // Se ejecuta tanto el tag THREE como el proceso P_ONE
  result = result && "three" == obj.__three;
  result = result && "one" == obj.__one;
  result = result && "set" == obj.__two;
  //Ademas, al haber entrado al estado Last, tiene que haberse ejecutado
  // el TEST_OK, por lo que __testedOk debe ser true
  result = result && true == obj.__testedOk;
  obj.destruct();
  return result;

//codeEnd
//callbackEnd
})