(function () {
//callbackInit
  def =
//definitionInit
    {
      "TYPE": "Container",
      "FIELDS": {
        "VALUES": {
          "TYPE": "Array",
          "ELEMENTS": {
            "TYPE": "Container",
            "FIELDS": {
              "VALUE": {
                "TYPE": "Integer"
              },
              "LABEL": {
                "TYPE": "String"
              }
            }
          }
        },
        "DEFAULT": {
          "TYPE": "String",
          "SOURCE": {
            "TYPE": "Path",
            "PATH": "#../VALUES",
            "LABEL": "LABEL",
            "VALUE": "LABEL"
          }
        }
      }
    }
//definitionEnd

//codeInit

  var cnt = Siviglia.types.TypeFactory.getType("", def, null, null);
  var thrown = false;
  try {
    cnt.setValue(
      {
        "VALUES": [
          {"VALUE": 1, "LABEL": "Pepito"},
          {"VALUE": 2, "LABEL": "Juanito"}
        ],
        "DEFAULT": "Pepito"
      }
    );
  } catch (e) {
    thrown = true;
  }
  var res = thrown === false;
  // Se intenta ahora ponerle un valor que NO existe en la fuente:
  var thrown = false;
  try {
    cnt.DEFAULT = "Invalid"
  } catch (e) {
    thrown = true;

  }
  cnt.destruct();
  var t = countListeners();
  return res && thrown && t == 0;

//codeEnd
//callbackEnd
})