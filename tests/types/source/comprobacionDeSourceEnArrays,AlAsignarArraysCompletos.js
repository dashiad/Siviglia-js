(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "a1": {
          "TYPE": "Array",
          "ELEMENTS": {
            "TYPE": "String"
          },
          "SOURCE": {
            "TYPE": "Path",
            "PATH": "#../a2"
          }
        },
        "a2": {
          "TYPE": "Array",
          "ELEMENTS": {
            "TYPE": "Container",
            "FIELDS": {
              "LABEL": {
                "TYPE": "String"
              },
              "VALUE": {
                "TYPE": "String"
              }
            }
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(def);
  t1.a2 = [{"LABEL": "uno", "VALUE": "uno"},
    {"LABEL": "dos", "VALUE": "dos"},
    {"LABEL": "tres", "VALUE": "tres"},
  ];
  var thrown = false;
  try {
    t1.a1 = ["cuatro"];
  } catch (e) {
    thrown = true;
  }
  var status = thrown === true;
  t1.a2.push(
    {"LABEL": "cuatro", "VALUE": "cuatro"}
  );
  status = status && t1["*a1"].__isErrored() === false;
  t1.destruct();
  return status;

//codeEnd
//callbackEnd
})