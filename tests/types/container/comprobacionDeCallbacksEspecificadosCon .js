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
            "METHOD": "callback_two",
            "PARAMS": [
              "set"
            ]
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
                  ],
                  "*": [
                    "TWO"
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
              "ON_ENTER": [
                "THREE"
              ]
            },
            "FIELDS": {
              "EDITABLE": [
                "two"
              ],
              "FIXED": [
                "one"
              ]
            }
          },
          "Another": {
            "LISTENERS": {
              "ON_ENTER": {
                "STATES": {
                  "None": [
                    "THREE",
                    "P_ONE"
                  ]
                }
              }
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
  // Se ha tenido que ejecutar el callback "TWO" via el estado "*"
  result = result && "set" == obj.__two;
  obj.destruct();
  return result;

//codeEnd
//callbackEnd
})