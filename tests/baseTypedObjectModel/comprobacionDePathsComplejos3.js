(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "one": {
          "TYPE": "String"
        },
        "two": {
          "TYPE": "String"
        },
        "state": {
          "TYPE": "State",
          "VALUES": [
            "E1",
            "E2",
            "E3"
          ],
          "DEFAULT": "E1"
        },
        "inner": {
          "TYPE": "Container",
          "REQUIRED": true,
          "FIELDS": {
            "one": {
              "TYPE": "String"
            },
            "two": {
              "TYPE": "String"
            },
            "three": {
              "TYPE": "String"
            },
            "state": {
              "TYPE": "State",
              "VALUES": [
                "E1",
                "E2",
                "E3"
              ],
              "DEFAULT": "E1"
            }
          },
          "STATES": {
            "STATES": {
              "E1": {
                "FIELDS": {
                  "EDITABLE": [
                    "one",
                    "two",
                    "inner"
                  ]
                }
              },
              "E2": {
                "ALLOW_FROM": [
                  "E1"
                ],
                "FIELDS": {
                  "EDITABLE": [
                    "two",
                    "three"
                  ]
                }
              },
              "E3": {
                "ALLOW_FROM": [
                  "E2"
                ],
                "FINAL": true,
                "FIELDS": {
                  "REQUIRED": [
                    "three"
                  ]
                }
              }
            },
            "FIELD": "state"
          }
        }
      },
      "STATES": {
        "STATES": {
          "E1": {
            "FIELDS": {
              "EDITABLE": [
                "one",
                "two",
                "inner"
              ]
            }
          },
          "E2": {
            "ALLOW_FROM": [
              "E1"
            ],
            "FIELDS": {
              "EDITABLE": [
                "two",
                "three"
              ]
            }
          },
          "E3": {
            "ALLOW_FROM": [
              "E2"
            ],
            "FINAL": true,
            "FIELDS": {
              "REQUIRED": [
                "three"
              ]
            }
          }
        },
        "FIELD": "state"
      }
    }
//definitionEnd

//codeInit
  var t = new Siviglia.model.BaseTypedObject(def);
  // Se intenta asignar un valor no valido del container interno:
  var thrown = false;
  var result = true;
  try {
    t.setValue({"one": "aa", "state": "E1", "inner": {"three": "zzz", "state": "E1"}});
  } catch (e) {
    thrown = true;
    var isErrored = t.__isErrored();
    var isErrored2 = t["*inner"].__isErrored();
    var isErrored3 = t.inner["*three"].__isErrored();
    result = result && isErrored && isErrored2 && isErrored3;
    var e1 = t.getErroredFields();
    var path = e1[0].__getFieldPath();
    var e2 = t["*inner"].getErroredFields();
    var path2 = e2[0].__getFieldPath();
    result = result && "/inner" === path;
    result = result && "/inner/three" === path2;
  }
  result = result && thrown;
  // Arreglamos el campo inner:
  t.inner = {"one": "qq", "state": "E1"};
  var isErrored = t.__isErrored();
  var isErrored2 = t["*inner"].__isErrored();

  var isErrored3 = t.inner["*three"].__isErrored();

  result = result && false == (isErrored || isErrored2 || isErrored3);
  // Se pasa el inner al estado E2, y luego a E3, donde deberia dar un error, ya que three es requerido
  t.inner.state = "E2";
  thrown = false;
  try {
    t.inner.state = "E3";
  } catch (e) {
    thrown = true;
    result = e.type == 'BaseTypedException';
    result = result && e.code == Siviglia.model.BaseTypedException.ERR_INVALID_STATE_TRANSITION;
    isErrored = t.__isErrored();
    isErrored2 = t["*inner"].__isErrored();
    var e1 = t.getErroredFields();
    var path = e1[0].__getFieldPath();
    var e2 = t["*inner"].getErroredFields();
    path2 = e2[0].__getFieldPath();
    result = result && "/inner" == path;
    result = result && "/inner/three" == path2;
  }
  result = result && thrown;
  t.destruct();
  return result;

//codeEnd
//callbackEnd
})