(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "F1": {
          "TYPE": "Container",
          "FIELDS": {
            "TYPE": {
              "TYPE": "String",
              "FIXED": "T1"
            },
            "CAMPO": {
              "TYPE": "String"
            },
            "CAMPO2": {
              "TYPE": "String"
            }
          }
        }
      }
    }
//definitionEnd

//codeInit
  var t1 = new Siviglia.model.BaseTypedObject(
    def
  );
  var a = {};
  t1.F1 = a;
  var nChanges = 0;
  t1.F1["*CAMPO2"].addListener("CHANGE", function (ev, params) {
    nChanges++;
  })
  a.CAMPO2 = "lala";
  t1.destruct();
  return nChanges == 1;

//codeEnd
//callbackEnd
})