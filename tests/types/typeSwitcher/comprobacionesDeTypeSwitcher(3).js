(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "TypeSwitcher",
          "TYPE_FIELD": "TYPE",
          "IMPLICIT_TYPE": "ModelReference",
          "ALLOWED_TYPES": {
            "String": {
              "TYPE": "Container",
              "FIELDS": {
                "TYPE": {
                  "TYPE": "String",
                  "FIXED": "String"
                },
                "MINLENGTH": {
                  "TYPE": "Integer"
                },
                "MAXLENGTH": {
                  "TYPE": "Integer"
                },
                "REGEXP": {
                  "TYPE": "String"
                }
              }
            },
            "Integer": {
              "TYPE": "Container",
              "FIELDS": {
                "TYPE": {
                  "TYPE": "String",
                  "FIXED": "Integer"
                },
                "MIN": {
                  "TYPE": "Integer"
                },
                "MAX": {
                  "TYPE": "Integer"
                }
              }
            },
            "*": {
              "TYPE": "Container",
              "FIELDS": {
                "TYPE": {
                  "TYPE": "String"
                },
                "F1": {
                  "TYPE": "Integer"
                },
                "F2": {
                  "TYPE": "Integer"
                }
              }
            }
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(def);
  var nChanges = 0;
  t1["*f3"].addListener("CHANGE", null, function () {
    nChanges++
  });
  t1.f3 = {"TYPE": "Integer", "MAX": 10};
  var status = nChanges === 1 && 10 === t1.f3.MAX;

  t1.f3 = {"TYPE": "NO_DEFINIDO", "F1": 15};
  status = status && 15 === t1.f3.F1 && "NO_DEFINIDO" === t1.f3.TYPE;

  // Asignamos el tipo "inline", asignando al campo, no llamando a setValue
  t1.f3.TYPE = "OOOTRO";
  t1.f3.F1 = 22;
  status = status && "OOOTRO" === t1.f3.TYPE;
  // Cambiamos el tipo a traves de los campos:
  t1.f3.TYPE = "String";
  t1.f3.MAXLENGTH = 10;
  status = status && "String" === t1.f3.TYPE;
  // No se puede acceder a los campos del tipo "*"
  exceptionThrown = false;

  var p = t1.f3;
  var thrown = false;
  try {
    var q = p.F1;
  } catch (e) {
    thrown = true;
  }
  status = status && thrown;
  t1.destruct();
  return status && countListeners() === 0;

//codeEnd
//callbackEnd
})