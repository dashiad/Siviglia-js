(function () {
//callbackInit
  def =
//definitionInit
    {
      "FIELDS": {
        "f3": {
          "TYPE": "Array",
          "ELEMENTS": {
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
                "TYPE": "String",
                "MAXLENGTH": 2
              }
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
  var arr = [{"TYPE": "T1", "CAMPO": "abcde"}, {"TYPE": "T2", "CAMPO": "abcde"}, {"TYPE": "T3", "CAMPO": "abcde"}];

  // Importante!! Parece que las dos lineas siguientes no van a cambiar lo que hay en arr, pero sí que
  // lo van a hacer!
  t1.f3 = arr;
  arr = t1.f3;

  var nChanges = 0;
  t1["*f3"].addListener("CHANGE", function (evName, params) {
    nChanges++;
  });
  arr.push({"TYPE": "T5", "CAMPO": "qqq"});
  var status = (nChanges === 1 && t1.f3.length === 4);
  var excpThrown = false;
  try {
    // Debe dar excepcion, ya que CAMPO2 tiene un MAXLENGTH
    arr.push({"TYPE": "T6", "CAMPO2": "ssssss"})
  } catch (e) {
    excpThrown = true;
  }
  status = status && excpThrown === true;

  t1.destruct();

  return status && countListeners() == 0;

//codeEnd
//callbackEnd
})